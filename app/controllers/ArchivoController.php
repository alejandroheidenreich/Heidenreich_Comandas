<?php


class ArchivoController
{

  
  public static function Descargar($request, $response, $args)
  {
    $csvFilePath = './archivos/productos.csv'; // Ruta al archivo CSV que deseas descargar
    $csvFileName = 'productos.csv'; // Nombre del archivo de descarga

    $file = fopen($csvFilePath, 'r');

    $stream = fopen('php://temp', 'w+');
    while (($line = fgetcsv($file)) !== false) {
        fputcsv($stream, $line);
    }

    fclose($file);

    $response = $response->withHeader('Content-Type', 'application/csv');
    $response = $response->withHeader('Content-Disposition', 'attachment; filename=' . $csvFileName);
    $response = $response->withHeader('Pragma', 'no-cache');
    $response = $response->withHeader('Expires', '0');
    $response = $response->withBody(new \Slim\Psr7\Stream($stream));

    return $response;
  }

  public static function Cargar($request, $response, $args){
      $uploadedFiles = $request->getUploadedFiles();

      $csvFile = $uploadedFiles['csv'];

      $targetPath = './archivos_cargados/' . $csvFile->getClientFilename();


      $payload = json_encode(array("mensaje" => "Archivo cargado"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    } 
}
