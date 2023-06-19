<?php

require_once "./models/Usuario.php";

class Autentificador{

    public static function ValidarSocio($request, $response/*, $next*/)
    {
        $parametros = $request->getParsedBody();
        $token = $parametros['token'];

        $usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($usuarioAutorizado != null && $usuarioAutorizado->rol == 'socio' /*&& Usuario::ValidarExpiracionToken($usuarioAutorizado)*/) {
            return $next($request, $response);
        }

        throw new Exception("Token no valido");

    }
}
