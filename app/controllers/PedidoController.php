<?php
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './models/Producto.php';
require_once './models/Estado.php';
require_once './models/GeneradorCodigo.php';
require_once './interfaces/IApiUse.php';

class PedidoController extends Pedido implements IApiUse
{
  public static function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    //$idMesa, $idProducto, $nombreCliente
    $idMesa = $parametros['idMesa'];
    $idProducto = $parametros['idProducto'];
    $nombreCliente = $parametros['nombreCliente'];


    $pedido = new Pedido();
    $pedido->idMesa = $idMesa;

    $pedido->idProducto = $idProducto;
    $pedido->nombreCliente = $nombreCliente;

    $mesa = Mesa::obtenerUno($idMesa);

    if ($mesa->estado == Estado::CERRADA) {
      $pedido->codigoPedido = GenerarCodigo(5);
      $mesa->estado = Estado::ESPERANDO;
      Mesa::modificar($mesa);
    } else {
      $pedido->codigoPedido = Pedido::obtenerUltimoCodigo($idMesa);
    }

    Pedido::crear($pedido);

    $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUno($request, $response, $args)
  {
    $valor = $args['valor'];
    $pedido = Pedido::obtenerUno($valor);
    $payload = json_encode($pedido);

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

  public static function TraerPendientes($request, $response, $args)
  {
    $listaPendientes = Pedido::obtenerPendientes();
    $lista = [];
    $cookies = $request->getCookieParams();


    $token = $cookies['token'];
    $data = AutentificadorJWT::ObtenerData($token);
    foreach ($listaPendientes as $pedido) {

      if ((Producto::obtenerUno($pedido->idProducto))->tipo == $data->rol) {
        $lista[] = $pedido;
      }
    }
    $payload = json_encode(array("listaPedidosPendientes" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    Pedido::modificar($nombre);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $pedidoId = $parametros['pedidoId'];
    Pedido::borrar($pedidoId);

    $payload = json_encode(array("mensaje" => "pedido borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function IniciarPedido($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $args['id'];

    $tiempoEstimado = $parametros['tiempoEstimado'];
    $pedido = Pedido::obtenerUno($id);
    if ($pedido) {
      $cookies = $request->getCookieParams();
      $token = $cookies['token'];
      $data = AutentificadorJWT::ObtenerData($token);
      //echo (Producto::obtenerUno($pedido->idProducto))->tipo . " == " . $data->rol;
      if ((Producto::obtenerUno($pedido->idProducto))->tipo == $data->rol) {
        Pedido::iniciar($id, $tiempoEstimado);
        $payload = json_encode(array("mensaje" => "Pedido en Preparacion"));
      } else {
        $payload = json_encode(array("mensaje" => "No tienes permisos para iniciar este pedido"));
      }

    } else {
      $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public static function FinalizarPedido($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $args['id'];


    $pedido = Pedido::obtenerUno($id);
    if ($pedido) {
      $cookies = $request->getCookieParams();
      $token = $cookies['token'];
      $data = AutentificadorJWT::ObtenerData($token);

      if ((Producto::obtenerUno($pedido->idProducto))->tipo == $data->rol && $pedido->estado == Estado::PREPARACION) {
        Pedido::finalizar($id);
        $payload = json_encode(array("mensaje" => "Pedido listo para servir"));
      } else {
        $payload = json_encode(array("mensaje" => "No tienes permisos para finalizar este pedido"));
      }

    } else {
      $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public static function EntregarPedido($request, $response, $args)
  {
    $id = $args['id'];

    $pedido = Pedido::obtenerUno($id);
    if ($pedido) {

      if ($pedido->estado == Estado::LISTO) {
        Pedido::entregar($id);
        $payload = json_encode(array("mensaje" => "Pedido entregado"));
      } else {
        $payload = json_encode(array("mensaje" => "El pedido no esta listo"));
      }

    } else {
      $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}