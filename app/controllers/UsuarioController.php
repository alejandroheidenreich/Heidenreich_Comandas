<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUse.php';
//require_once './middlewares/Roles.php';

class UsuarioController extends Usuario implements IApiUse
{
  public static function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usuario = $parametros['usuario'];
    $rol = $parametros['rol'];
    $clave = $parametros['clave'];
      if (!Usuario::ValidarRol($rol) && Usuario::ValidarUserName($usuario) == null) {
        $payload = json_encode(array("error" => "Creacion de usuario fallida"));
      } else {
        $user = new Usuario();
        $user->usuario = $usuario;
        $user->clave = $clave;
        $user->rol = $rol;

        Usuario::crear($user);
        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
      }
    // } else {
    //   $payload = json_encode(array("mensaje" => "No tienes permisos para realizar esta accion", "usuario" => $usuarioAutorizado));
    // }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $token = $parametros['token'];

    $usuarioAutorizado = Roles::ValidarSoloUnRole("socio", $token);
    if ($usuarioAutorizado != null) {
      $usuario = $args['usuario'];

      $usuario = Usuario::obtenerUno($usuario);
      $payload = json_encode($usuario);
    } else {
      $payload = json_encode(array("mensaje" => "No tienes permisos para realizar esta accion", "usuario" => $usuarioAutorizado));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function TraerTodos($request, $response, $args)
  {
    
    
      $lista = Usuario::obtenerTodos();
      $payload = json_encode(array("listaUsuario" => $lista));
    

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function ModificarUno($request, $response, $args)
  {
    
    $nombre = $parametros['nombre'];
    $usuario = Usuario::obtenerUno($nombre);

    if ($usuario != null) {
      Usuario::modificar($usuario);
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
    } else {
      $payload = json_encode(array("error" => "Usuario no existe"));
    }
  
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function BorrarUno($request, $response, $args)
  {
    
    $usuarioId = $parametros['usuarioId'];
    Usuario::borrar($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
     
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function LogIn($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $user = $parametros['usuario'];
    $clave = $parametros['clave'];

    $usuario = Usuario::obtenerUno($user);

    if ($usuario != null) {
      if (password_verify($clave, $usuario->clave)) {
        $data = array('usuario' => $usuario->usuario, 'rol' => $usuario->rol, 'clave' => $usuario->clave);
        $creacion = AutentificadorJWT::CrearToken($data);
        $usuario->token = $creacion['jwt'];
        $usuario->expiracionToken = $creacion['token']['exp'];

        Usuario::modificar($usuario);

      
        $payload = json_encode(array("mensaje" => "Usuario logeado", "token" => $usuario->token));
      } else {
        $payload = json_encode(array("mensaje" => "Usuario invalido", "Error" => "Clave invalida"));
      }

    } else {
      $payload = json_encode(array("mensaje" => "Usuario invalido", "Error" => "Usuario no existe"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}