<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUse.php';

class UsuarioController extends Usuario implements IApiUse
{
  public static function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usuario = $parametros['usuario'];
    $clave = $parametros['clave'];
    $rol = $parametros['rol'];

    $user = new Usuario( /*$usuario, $clave, $rol*/);
    $user->usuario = $usuario;
    $user->clave = $clave;
    $user->rol = $rol;
    $user->id = $user->crearUsuario($usuario, $clave, $rol);

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUnoPorPropiedad($request, $response, $args, $propiedad)
  {

    $valor = $args[$propiedad];
    $usuario = Usuario::obtenerUsuario($propiedad, $valor);
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
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    Usuario::modificarUsuario($nombre);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usuarioId = $parametros['usuarioId'];
    Usuario::borrarUsuario($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}