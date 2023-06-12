<?php

class Factura implements IPersistencia
{
    public $id;
    //public $codigoPedido; // 5 caracteres
    public $precioTotal;
    public $estado;
    public $fotoMesa;
    public $puntaje;
    public $encuesta;
    public $fechaBaja;

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

    public function crear($fotoMesa = 'Sin foto')
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO facturas (estado, fotoMesa) VALUES (:estado, :fotoMesa)");
        $consulta->bindValue(':estado', Estado::PENDIENTE, PDO::PARAM_STR);
        $consulta->bindValue(':fotoMesa', $fotoMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precioTotal, estado, fotoMesa, puntaje, encuesta fechaBaja FROM facturas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Factura');
    }

    public static function obtenerUno($propiedad, $valor)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precioTotal, estado, fotoMesa, puntaje, encuesta, fechaBaja FROM pedidos WHERE :propiedad = :valor");
        $consulta->bindValue(':propiedad', $propiedad, PDO::PARAM_STR);
        $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificar($factura)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE facturas SET SET precioTotal = :precioTotal, estado = :estado, fotoMesa = :fotoMesa, puntaje = :puntaje, encuesta = :estado WHERE id = :id");
        $consulta->bindValue(':precioTotal', $factura->precioTotal, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $factura->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fotoMesa', $factura->fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':puntaje', $factura->puntaje, PDO::PARAM_INT);
        $consulta->bindValue(':encuesta', $factura->encuesta, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrar($factura)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE facturas SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $factura->id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

}