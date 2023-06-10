<?php
interface IApiUse
{
	public static function CargarUno($request, $response, $args);
	public static function TraerUnoPorPropiedad($request, $response, $args, $propiedad);
	public static function TraerTodos($request, $response, $args);
	public static function BorrarUno($request, $response, $args);
	public static function ModificarUno($request, $response, $args);
}