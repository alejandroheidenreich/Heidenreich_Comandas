<?php

use Slim\Http\Request;
use Slim\Http\Response;

require_once './models/Usuario.php';
require_once './interfaces/IApiUse.php';

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


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUno($request, $response, $args)
  {

    $usuario = $args['usuario'];

    $usuario = Usuario::obtenerUno($usuario);
    $payload = json_encode($usuario);

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

    $id = $args['id'];

    $usuario = Usuario::obtenerUnoPorID($id);

    if ($usuario != null) {
      $parametros = $request->getParsedBody();

      $actualizado = false;
      if (isset($parametros['usuario'])) {
        $actualizado = true;
        $usuario->usuario = $parametros['usuario'];
      }
      if (isset($parametros['clave'])) {
        $actualizado = true;
        $usuario->clave = password_hash($parametros['clave'], PASSWORD_DEFAULT);
      }
      if (isset($parametros['rol'])) {
        $actualizado = true;
        $usuario->rol = $parametros['rol'];
      }

      if ($actualizado) {
        Usuario::modificar($usuario);
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
      } else {
        $payload = json_encode(array("mensaje" => "Usuario no modificar por falta de campos"));
      }

    } else {
      $payload = json_encode(array("error" => "Usuario no existe"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function BorrarUno($request, $response, $args)
  {
    $usuarioId = $args['id'];

    if (Usuario::obtenerUnoPorID($usuarioId)) {

      Usuario::borrar($usuarioId);
      $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
    } else {

      $payload = json_encode(array("mensaje" => "ID no coincide con un usuario"));
    }


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

        $response = $response->withHeader('Set-Cookie', 'token=' . $usuario->token);

        $payload = json_encode(array("mensaje" => "Usuario logeado, cookie entregada", "token" => $usuario->token));
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