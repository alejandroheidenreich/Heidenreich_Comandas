<?php

require_once "./models/Usuario.php";
require_once './middlewares/AutentificadorJWT.php';

class Autentificador
{

    public static function ValidarSocio($request, $handler)
    {
        $cookies = $request->getCookieParams();
        $token = $cookies['token'];

        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);

        if ($payload->rol == 'socio' /*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarMozo($request, $handler)
    {
        $cookies = $request->getCookieParams();
        $token = $cookies['token'];

        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);


        if ($payload->rol == 'socio' || $payload->rol == 'mozo' /*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }


    public static function ValidarPreparador($request, $handler)
    {
        $cookies = $request->getCookieParams();
        $token = $cookies['token'];
        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);

        if ($payload->rol == 'socio' || $payload->rol == 'cocinero' || $payload->rol == 'cervecero' || $payload->rol == 'bartender' || $payload->rol == 'candybar' /*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }

}