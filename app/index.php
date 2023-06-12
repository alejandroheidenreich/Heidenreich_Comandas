<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/AutentificadorJWT.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
//$app->setBasePath('/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);


// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . '::TraerTodos');
  $group->get('/{propiedad}/{valor}', \UsuarioController::class . '::TraerUnoPorPropiedad');
  $group->post('[/]', \UsuarioController::class . '::CargarUno');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . '::TraerTodos');
  $group->get('/consulta', \ProductoController::class . '::TraerUnoPorPropiedad');
  $group->post('[/]', \ProductoController::class . '::CargarUno');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . '::TraerTodos');
  $group->get('/consulta', \MesaController::class . '::TraerPorPropiedad');
  $group->post('[/]', \MesaController::class . '::CargarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . '::TraerTodos');
  $group->get('/consulta', \PedidoController::class . '::TraerPorPropiedad');
  $group->post('[/]', \PedidoController::class . '::CargarUno');
});

// // JWT test routes
// $app->group('/jwt', function (RouteCollectorProxy $group) {

//   $group->post('/crearToken', function (Request $request, Response $response) {    
//     $parametros = $request->getParsedBody();

//     $usuario = $parametros['usuario'];
//     $perfil = $parametros['perfil'];
//     $alias = $parametros['alias'];

//     $datos = array('usuario' => $usuario, 'perfil' => $perfil, 'alias' => $alias);

//     $token = AutentificadorJWT::CrearToken($datos);
//     $payload = json_encode(array('jwt' => $token));

//     $response->getBody()->write($payload);
//     return $response
//       ->withHeader('Content-Type', 'application/json');
//   });

//   $group->get('/devolverPayLoad', function (Request $request, Response $response) {
//     $header = $request->getHeaderLine('Authorization');
//     $token = trim(explode("Bearer", $header)[1]);

//     try {
//       $payload = json_encode(array('payload' => AutentificadorJWT::ObtenerPayLoad($token)));
//     } catch (Exception $e) {
//       $payload = json_encode(array('error' => $e->getMessage()));
//     }

//     $response->getBody()->write($payload);
//     return $response
//       ->withHeader('Content-Type', 'application/json');
//   });

//   $group->get('/devolverDatos', function (Request $request, Response $response) {
//     $header = $request->getHeaderLine('Authorization');
//     $token = trim(explode("Bearer", $header)[1]);

//     try {
//       $payload = json_encode(array('datos' => AutentificadorJWT::ObtenerData($token)));
//     } catch (Exception $e) {
//       $payload = json_encode(array('error' => $e->getMessage()));
//     }

//     $response->getBody()->write($payload);
//     return $response
//       ->withHeader('Content-Type', 'application/json');
//   });

//   $group->get('/verificarToken', function (Request $request, Response $response) {
//     $header = $request->getHeaderLine('Authorization');
//     $token = trim(explode("Bearer", $header)[1]);
//     $esValido = false;

//     try {
//       AutentificadorJWT::verificarToken($token);
//       $esValido = true;
//     } catch (Exception $e) {
//       $payload = json_encode(array('error' => $e->getMessage()));
//     }

//     if ($esValido) {
//       $payload = json_encode(array('valid' => $esValido));
//     }

//     $response->getBody()->write($payload);
//     return $response
//       ->withHeader('Content-Type', 'application/json');
//   });
// });


$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("TP" => "Comanda"));
  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();