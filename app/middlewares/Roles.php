<?php

require_once "./models/Usuario.php";

class Roles
{
    public static function ValidarSoloUnRole($role, $token)
    {

        $usuarioAutorizado = Usuario::BuscarPorToken($token);

        if ($usuarioAutorizado != null && $usuarioAutorizado->rol == $role && Usuario::ValidarExpiracionToken($usuarioAutorizado)) {
            return $usuarioAutorizado;
        }

        return null;

    }
}