<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUse.php';

class MesaController extends Mesa implements IApiUse
{
  public static function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $estado = $parametros['estado'];

    $mesa = new Mesa();

    $mesa->estado = $estado;

    Mesa::crear($mesa);


    $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUnoPorPropiedad($request, $response, $args)
  {

    $propiedad = $args['propiedad'];
    $valor = $args['valor'];
    $mesa = Mesa::obtenerUno($propiedad, $valor);
    $payload = json_encode($mesa);

    $response->getBody()->write('$payload');
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::obtenerTodos();
    $payload = json_encode(array("listaMesas" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    Mesa::modificar($nombre);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $mesaId = $parametros['mesaId'];
    Mesa::borrar($mesaId);

    $payload = json_encode(array("mensaje" => "mesa borrada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}