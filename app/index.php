<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Cookie\Cookie;


require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/Autentificador.php';
require_once './middlewares/Validador.php';
require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
//$app->setBasePath('/app');

// Add error middleware
$errorMiddleware = function ($request, $exception, $displayErrorDetails) use ($app) {
  $statusCode = 500;
  $errorMessage = $exception->getMessage();
  $response = $app->getResponseFactory()->createResponse($statusCode);
  $response->getBody()->write(json_encode(['error' => $errorMessage]));

  return $response->withHeader('Content-Type', 'application/json');
};

$app->addErrorMiddleware(true, true, true)
  ->setDefaultErrorHandler($errorMiddleware);

$app->addBodyParsingMiddleware();


// ABM Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/{usuario}', \UsuarioController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->post('[/]', \UsuarioController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio')->add(\Validador::class . '::ValidarNuevoUsuario');
  $group->put('/{id}', \UsuarioController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \UsuarioController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/consulta', \ProductoController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->post('[/]', \ProductoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \ProductoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \ProductoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->post('/load', \ProductoController::class . '::Cargar')->add(\Validador::class . '::VerificarArchivo');
  $group->get('/download', \ProductoController::class . '::Descargar')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/cuenta/{codigoPedido}', \MesaController::class . '::CuentaMesa')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/cobrar/{codigoPedido}', \MesaController::class . '::CobrarMesa')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/cerrar/{id}', \MesaController::class . '::CerrarMesa')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/usos', \MesaController::class . '::UsosMesa')->add(\Autentificador::class . '::ValidarSocio');
  $group->post('[/]', \MesaController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \MesaController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \MesaController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/buscar/{id}', \PedidoController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/listos', \PedidoController::class . '::TraerListos')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/pendientes', \PedidoController::class . '::TraerPendientes')->add(\Autentificador::class . '::ValidarPreparador');
  $group->post('/inicio/{id}', \PedidoController::class . '::IniciarPedido')->add(\Autentificador::class . '::ValidarPreparador');
  $group->post('/final/{id}', \PedidoController::class . '::FinalizarPedido')->add(\Autentificador::class . '::ValidarPreparador');
  $group->post('/entregar/{id}', \PedidoController::class . '::EntregarPedido')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/{codigoMesa}-{codigoPedido}', \PedidoController::class . '::TraerPedidosMesa');
  $group->post('[/]', \PedidoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarMozo');
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EncuestaController::class . '::CargarUno');
  $group->get('/mejores', \EncuestaController::class . '::TraerMejores')->add(\Autentificador::class . '::ValidarSocio');
});

// LOG IN 
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . '::LogIn')->add(\Logger::class . '::ValidarLogin');
});

// ADMIN
$app->group('/admin', function (RouteCollectorProxy $group) {
  $group->get('[/]', function ($request, $response, $args) {
    $user = new Usuario();
    $user->usuario = "admin";
    $user->clave = "admin";
    $user->rol = "socio";

    Usuario::crear($user);
    $payload = json_encode(array("mensaje" => "Admin creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });
});

$app->group('/csv', function (RouteCollectorProxy $group) {

  $group->get('/download[/]', \ArchivoController::class . '::Descargar');
});


$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("TP" => "Comanda"));
  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();