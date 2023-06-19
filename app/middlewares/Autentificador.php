<?php

require_once "./models/Usuario.php";
require_once './middlewares/AutentificadorJWT.php';

class Autentificador{

    public static function ValidarSocioPost($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $token = $parametros['token'];
        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);
        //$usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($payload->rol == 'socio' /*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarSocioGet($request, $handler)
    {
        $parametros = $request->getQueryParams();
        $token = $parametros['token'];
        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);
        //$usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($payload->rol == 'socio' /*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);//response
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarMozoPost($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $token = $parametros['token'];
        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);
        //$usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($payload->rol == 'socio' || $payload->rol == 'mozo'/*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarMozoGet($request, $handler)
    {
        $parametros = $request->getQueryParams();
        $token = $parametros['token'];
        $payload = AutentificadorJWT::ObtenerData($token);
        //$usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($payload->rol == 'socio' || $payload->rol == 'mozo'/*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);//response
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarPreparadorPost($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $token = $parametros['token'];
        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);
        //$usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($payload->rol == 'socio' || $payload->rol == 'cocinero'|| $payload->rol == 'cervecero'|| $payload->rol == 'bartender'|| $payload->rol == 'cocinero'/*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarPreparadorGet($request, $handler)
    {
        $parametros = $request->getQueryParams();
        $token = $parametros['token'];
        $payload = AutentificadorJWT::ObtenerData($token);
        //$usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($payload->rol == 'socio' || $payload->rol == 'cocinero'|| $payload->rol == 'cervecero'|| $payload->rol == 'bartender'|| $payload->rol == 'cocinero'/*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);//response
        }

        throw new Exception("Token no valido");
    }
}



// $app->add(function ($request, $handler) {
//     // Obtener el cuerpo analizado de la solicitud actual
//     $parsedBody = $request->getParsedBody();

//     // Agregar un campo adicional al cuerpo analizado
//     $parsedBody['campo_adicional'] = 'valor_adicional';

//     // Crear una nueva instancia de la solicitud con el cuerpo analizado actualizado
//     $request = $request->withParsedBody($parsedBody);

//     // Continuar el procesamiento de la solicitud
//     $response = $handler->handle($request);

//     // Devolver la respuesta resultante
//     return $response;
// });

// public static function Metodo($request, $handler)
//     {
//         $parametros = $request->getParsedBody();
//         $token = $parametros['token'];

//         $usuarioAutorizado = Usuario::BuscarPorToken($token);

//         if (todo ok) {
//             return $handler->handle($request);//response
//         }

//         throw new Exception("Token no valido");

//     }