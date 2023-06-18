<?php
interface IPersistencia
{
	public static function crear($objeto);
	public static function obtenerTodos();
	public static function obtenerUno($valor);
	public static function modificar($objeto);
	public static function borrar($objeto);
}