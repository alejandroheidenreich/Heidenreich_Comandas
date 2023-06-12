<?php

require_once './models/Usuario.php';
class ValidadorUsuario
{

    public static function ValidarTipos($tipo)
    {
        if ($tipo != 'socio' && $tipo != 'bartender' && $tipo != 'cervecero' && $tipo != 'cocinero' && $tipo != 'mozo') {
            return false;
        }
        return true;
    }

    public static function ValidarUserName($username)
    {
        $usuarios = Usuario::obtenerTodos();

        foreach ($usuarios as $user) {
            if ($user->usuario == $username) {
                return false;
            }
        }
        return true;
    }
}