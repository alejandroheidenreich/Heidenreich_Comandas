<?php
require_once './models/Pedido.php';
require_once './models/Factura.php';
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

    if (isset($parametros['idFactura'])) {
      $idFactura = $parametros['idFactura'];
    } else {

      $factura = new Factura();
      // TODO: como manejar img con slim
      $fotoMesa = $request->getUploadedFiles();
      if (is_uploaded_file($parametros['fotoMesa'])) {
        $fechaHoy = new DateTime();
        $fecha = date_format($fechaHoy, 'Y-m-d H:i:s');
        $factura->GuardarImagen($parametros['fotoMesa']['tmp_name'], "{$nombreCliente}_{$fecha}");
      }
      $idFactura = Factura::crear($factura);
    }

    $pedido = new Pedido();
    $pedido->idMesa = $idMesa;
    $pedido->idProducto = $idProducto;
    $pedido->nombreCliente = $nombreCliente;
    $pedido->idFactura = intval($idFactura);

    Pedido::crear($pedido);

    $payload = json_encode(array("mensaje" => $idFactura));

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