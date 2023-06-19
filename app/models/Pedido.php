<?php

require_once './models/Estado.php';


class Pedido implements IPersistencia
{
    public $id;
    public $codigoPedido; // 5 caracteres
    public $idMesa;
    public $idProducto;
    public $nombreCliente;
    public $estado;
    public $tiempoEstimado;
    public $tiempoInicio;
    public $tiempoEntregado;
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


    public static function crear($pedido)
    {
        //TODO: agregar id factura
        //$codigo = GenerarCodigo(5);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoPedido, idMesa, idProducto, nombreCliente, estado) VALUES (:codigoPedido, :idMesa, :idProducto, :nombreCliente, :estado)");
        $consulta->bindValue(':codigoPedido', $pedido->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $pedido->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::PENDIENTE, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoEntregado, fechaBaja FROM pedidos");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerUno($valor)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoEntregado, fechaBaja FROM pedidos WHERE id = :valor");
        //$consulta->bindValue(':propiedad', $propiedad, PDO::PARAM_STR);
        $consulta->bindValue(':valor', $valor, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPendientes()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoEntregado, fechaBaja FROM pedidos WHERE estado = :valor");
        //$consulta->bindValue(':propiedad', $propiedad, PDO::PARAM_STR);
        $consulta->bindValue(':valor', Estado::PENDIENTE, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function modificar($pedido)
    {
    
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET codigoPedido = :codigoPedido, idMesa = :idMesa, idProducto = :idProducto, nombreCliente = :nombreCliente, estado = :estado, tiempoEstimado = :tiempoEstimado, tiempoInicio = :tiempoInicio, tiempoEntregado = :tiempoEntregado, fechaBaja = :fechaBaja WHERE id = :id");
        $consulta->bindValue(':codigoPedido', $pedido->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $pedido->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $pedido->tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoInicio', $pedido->tiempoInicio, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEntregado', $pedido->tiempoEntregado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', $pedido->fechaBaja, PDO::PARAM_STR);
        // $consulta->bindValue(':encuesta', $pedido->encuesta, PDO::PARAM_STR);
        // $consulta->bindValue(':puntaje', $pedido->puntaje, PDO::PARAM_STR);
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrar($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function obtenerUltimoCodigo($idMesa){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT codigoPedido FROM pedidos WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn();
    }

}