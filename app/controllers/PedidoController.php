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
    $propiedad = $args['propiedad'];
    $valor = $args['valor'];
    $pedido = Pedido::obtenerUno($propiedad, $valor);
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
    $parametros = $request->getQueryParams();
        
    $token = $parametros['token'];
    $data = AutentificadorJWT::ObtenerData($token);
    foreach ($listaPendientes as $pedido) {
      
      if((Producto::obtenerUno($pedido->idProducto))->tipo == $data->rol ){
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
}