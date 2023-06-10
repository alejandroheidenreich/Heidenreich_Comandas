<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUse.php';

class ProductoController extends Producto implements IApiUse
{

  public static function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $descripcion = $parametros['descripcion'];
    $tipo = $parametros['tipo'];
    $precio = $parametros['precio'];

    $prod = new Producto();

    $prod->crearProducto($descripcion, $tipo, $precio);

    $payload = json_encode(array("mensaje" => "Producto creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUnoPorPropiedad($request, $response, $args, $propiedad)
  {
    $valor = $args[$propiedad];
    $product = Producto::obtenerProducto($propiedad, $valor);
    $payload = json_encode($product);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("listaMesas" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    Producto::modificarProducto($nombre);

    $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $productoId = $parametros['productoId'];
    Producto::borrarProducto($productoId);

    $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}