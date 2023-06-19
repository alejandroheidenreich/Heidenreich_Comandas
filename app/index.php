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
require_once './middlewares/Autentificador.php';

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
$errorMiddleware = function ($request, $exception, $displayErrorDetails) use ($app) {
  $statusCode = 500;
  $errorMessage = $exception->getMessage();

  // Lógica adicional para determinar el código de estado y el mensaje de error según la excepción

  $response = $app->getResponseFactory()->createResponse($statusCode);
  $response->getBody()->write(json_encode(['error' => $errorMessage]));

  return $response->withHeader('Content-Type', 'application/json');
};

$app->addErrorMiddleware(true, true, true)
  ->setDefaultErrorHandler($errorMiddleware);



// $parametros = $request->getParsedBody();

//     $token = $parametros['token'];

//     $usuarioAutorizado = Roles::ValidarSoloUnRole("socio", $token);

//     if ($usuarioAutorizado != null) {

//       $usuario = $parametros['usuario'];
//       $clave = $parametros['clave'];
//       $rol = $parametros['rol'];

//       if (Usuario::ValidarRol($rol) && Usuario::ValidarUserName($usuario) == null) {
//         $payload = json_encode(array("error" => "Creacion de usuario fallida"));
//       } else {
//         $user = new Usuario();
//         $user->usuario = $usuario;
//         $user->clave = $clave;
//         $user->rol = $rol;

//         Usuario::crear($user);
//         $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
//       }
//     } else {
//       $payload = json_encode(array("mensaje" => "No tienes permisos para realizar esta accion", "usuario" => $usuarioAutorizado));
//     }

//     $response->getBody()->write($payload);
//     return $response
//       ->withHeader('Content-Type', 'application/json');


// ABM Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocioGet');
  $group->get('/{usuario}', \UsuarioController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocioGet');
  $group->post('[/]', \UsuarioController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocioPost');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocioGet');
  $group->get('/consulta', \ProductoController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocioGet');
  $group->post('[/]', \ProductoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocioPost');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocioGet');
  $group->get('/consulta', \MesaController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocioGet');
  $group->post('[/]', \MesaController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocioPost');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarMozoGet');
  $group->get('/pendientes', \PedidoController::class . '::TraerPendientes')->add(\Autentificador::class . '::ValidarPreparadorGet');
  $group->get('/consulta', \PedidoController::class . '::TraerPorPropiedad')->add(\Autentificador::class . '::ValidarMozoGet');
  $group->post('[/]', \PedidoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarMozoPost');
});



// LOG IN
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . '::LogIn');
});

// ADMIN
$app->group('/admin', function (RouteCollectorProxy $group) {
  $group->get('[/]', function ($request, $response, $args) {
    $user = new Usuario();
    $user->usuario = "admin";
    $user->clave = "admin";
    $user->rol = "socio";

    Usuario::crear($user);
    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });
});

// JWT test routes
$app->group('/jwt', function (RouteCollectorProxy $group) {

  $group->post('/crearToken', function (Request $request, Response $response) {
    $parametros = $request->getParsedBody();

    $usuario = $parametros['usuario'];
    $perfil = $parametros['perfil'];
    $rol = $parametros['rol'];

    $datos = array('usuario' => $usuario, 'perfil' => $perfil, 'rol' => $rol);

    $token = AutentificadorJWT::CrearToken($datos);
    $payload = json_encode(array('jwt' => $token));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/devolverPayLoad', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    try {
      $payload = json_encode(array('payload' => AutentificadorJWT::ObtenerPayLoad($token)));
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/devolverDatos', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    try {
      $payload = json_encode(array('datos' => AutentificadorJWT::ObtenerData($token)));
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/verificarToken', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $esValido = false;

    try {
      AutentificadorJWT::verificarToken($token);
      $esValido = true;
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    if ($esValido) {
      $payload = json_encode(array('valid' => $esValido));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });
});


$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("TP" => "Comanda"));
  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();