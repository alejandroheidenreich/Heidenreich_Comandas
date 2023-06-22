<?php

require_once "./models/Usuario.php";
require_once './middlewares/AutentificadorJWT.php';

class Validador
{

    public static function ValidarNuevoUsuario($request, $handler)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $rol = $parametros['rol'];
        $clave = $parametros['clave'];
        if (Usuario::ValidarRol($rol) && Usuario::ValidarUserName($usuario) == null) {
            return $handler->handle($request);
        }

        throw new Exception("Error en la creacion del Usuario");
    }

    public static function VerificarArchivo($request, $handler)
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (isset($uploadedFiles['csv'])) {
          
            return $handler->handle($request);
        }

        throw new Exception("Error no se recibio el archivo");
    }


}