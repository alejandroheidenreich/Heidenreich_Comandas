<?php

require_once "./models/Usuario.php";
class Logger
{
    public static function ValidarLogin($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $user = $parametros['usuario'];
        $clave = $parametros['clave'];
        $usuario = Usuario::obtenerUno($user);

        if ($usuario != false && password_verify($clave, $usuario->clave)) {
            return $handler->handle($request);
        }

        throw new Exception("Usuario y/o clave erroneos");
    }
}