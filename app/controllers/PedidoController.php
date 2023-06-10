<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUse.php';

class PedidoController extends Pedido implements IApiUse
{
  public static function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $idMesa = $parametros['idMesa'];
    $puntaje = $parametros['puntaje'];
    $encuesta = $parametros['encuesta'];

    $pedido = new Pedido();

    $pedido->crearPedido($idMesa, $puntaje, $encuesta);

    $payload = json_encode(array("mensaje" => "Pedido creada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUnoPorPropiedad($request, $response, $args, $propiedad)
  {

    $valor = $args[$propiedad];
    $Pedido = Pedido::obtenerPedido($propiedad, $valor);
    $payload = json_encode($Pedido);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function TraerTodos($request, $response, $args)
  {
    $lista = Pedido::obtenerTodos();
    $payload = json_encode(array("listaPedidos" => $lista));

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