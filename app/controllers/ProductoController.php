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
    $prod->descripcion = $descripcion;
    $prod->tipo = $tipo;
    $prod->precio = $precio;

    Producto::crear($prod);

    $payload = json_encode(array("mensaje" => "Producto creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerUno($request, $response, $args)
  {
    $valor = $args['valor'];
    $product = Producto::obtenerUno($valor);
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

    $id = $args['id'];

    $producto = Producto::obtenerUno($id);

    if ($producto != false) {
      $parametros = $request->getParsedBody();

      $actualizado = false;
      if (isset($parametros['descripcion'])) {
        $actualizado = true;
        $producto->descripcion = $parametros['descripcion'];
      }
      if (isset($parametros['tipo'])) {
        $actualizado = true;
        $producto->tipo = $parametros['tipo'];
      }
      if (isset($parametros['precio'])) {
        $actualizado = true;
        $producto->precio = $parametros['precio'];
      }

      if ($actualizado) {
        Producto::modificar($producto);
        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
      } else {
        $payload = json_encode(array("mensaje" => "Producto no modificar por falta de campos"));
      }
    } else {
      $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Producto"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];

    if (Producto::obtenerUno($id)) {
      Producto::borrar($id);
      $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
    } else {

      $payload = json_encode(array("mensaje" => "ID no coincide con un Producto"));
    }


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function Descargar($request, $response, $args)
  {
    $productos = Producto::obtenerTodos();

    $stream = fopen('php://temp', 'w+');
    foreach ($productos as $p) {
      fputcsv($stream, get_object_vars($p));
    }

    $response = $response->withHeader('Content-Type', 'application/csv');
    $response = $response->withHeader('Pragma', 'no-cache');
    $response = $response->withHeader('Expires', '0');
    $response = $response->withBody(new \Slim\Psr7\Stream($stream));
    return $response;
  }

  public static function Cargar($request, $response, $args)
  {
    $archivoCSV = $request->getUploadedFiles()['csv'];
    $stream = $archivoCSV->getStream();
    $content = ($stream)->getContents();
    $lineas = explode("\n", $content);

    $productos = [];
    foreach ($lineas as $l) {
      $data = str_getcsv($l);
      if (empty(trim($l))) {
        break;
      }
      if (Producto::ValidarDescripcion($data[0]) != null || !Producto::ValidarTipo($data[1])) {
        throw new Exception("Fallo en la carga por validacion de datos");
      }
      $producto = new Producto();
      $producto->descripcion = $data[0];
      $producto->tipo = $data[1];
      $producto->precio = $data[2];
      $productos[] = $producto;
    }

    Producto::crearLista($productos);
    $payload = json_encode(array("mensaje" => "Archivo cargado con exito"));


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }



}