<?php

//require_once './Estado.php';
class Pedido
{
    public $numeroPedido;
    public $idMesa;
    public $puntaje;
    public $encuesta;


    public function __construct()
    {

    }

    public function __get($propiedad)
    {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        } else {
            return null;
        }
    }

    public function __set($propiedad, $valor)
    {
        if (property_exists($this, $propiedad)) {
            $this->$propiedad = $valor;
        } else {
            echo "No existe " . $propiedad;
        }
    }


    public function crearPedido($idMesa, $puntaje, $encuesta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos ( idMesa, puntaje, encuesta) VALUES (:idMesa, :puntaje,:encuesta)");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntaje', $puntaje, PDO::PARAM_INT);
        $consulta->bindValue(':encuesta', $encuesta, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT numeroPedido, idMesa, puntaje, encuesta FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($propiedad, $valor)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT numeroPedido, idMesa, puntaje, encuesta FROM pedidos WHERE :propiedad = :valor");
        $consulta->bindValue(':propiedad', $propiedad, PDO::PARAM_STR);
        $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET SET idMesa = :idMesa, puntaje = :puntaje, encuesta = :encuesta WHERE numeroPedido = :numeroPedido");
        $consulta->bindValue(':idMesa', $pedido->mesa->id, PDO::PARAM_STR);
        $consulta->bindValue(':encuesta', $pedido->encuesta, PDO::PARAM_STR);
        $consulta->bindValue(':puntaje', $pedido->puntaje, PDO::PARAM_STR);
        $consulta->bindValue(':numeroPedido', $pedido->numeroPedido, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedido($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE numeroPedido = :numeroPedido");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':numeroPedido', $pedido->numeroPedido, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}