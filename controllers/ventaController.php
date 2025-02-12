<?php
require_once __DIR__ . "../../models/venta.php";

class VentaController {
    private $ventaModel;

    public function __construct() {
        $this->ventaModel = new Venta();
    }

    public function registrarVenta() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $producto = $_POST["producto"] ?? null;
            $cantidad = $_POST["cantidad"] ?? null;
            $precio = $_POST["precio"] ?? null;
            $fecha_venta = $_POST["fecha_venta"] ?? null;
    
            if ($this->ventaModel->registrarVenta($producto, $cantidad, $precio, $fecha_venta)) {
                echo json_encode(["success" => true, "message" => "Venta registrada."]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al registrar la venta."]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Método no permitido."]);
        }
    }

    public function exportarExcel() {
        $fechaInicio = $_GET["fecha_inicio"] ?? null;
        $fechaFin = $_GET["fecha_fin"] ?? null;

        if (!$fechaInicio || !$fechaFin) {
            echo json_encode(["error" => "Parametros fecha_inicio y fecha_fin requeridos"]);
            return;
        }

        $this->ventaModel->exportarExcel($fechaInicio, $fechaFin);
    }

    public function exportarPDF() {
        $fechaInicio = $_GET["fecha_inicio"] ?? null;
        $fechaFin = $_GET["fecha_fin"] ?? null;

        if (!$fechaInicio || !$fechaFin) {
            echo json_encode(["error" => "Parametros fecha_inicio y fecha_fin requeridos"]);
            return;
        }

        $this->ventaModel->exportarPDF($fechaInicio, $fechaFin);
    }
    
    public function obtenerVentas() {
        $fechaInicio = $_GET["fecha_inicio"] ?? null;
        $fechaFin = $_GET["fecha_fin"] ?? null;
    
        if (!$fechaInicio || !$fechaFin) {
            echo json_encode(["error" => "Parametros fecha_inicio y fecha_fin requeridos"]);
            return;
        }
    
        echo json_encode($this->ventaModel->obtenerVentas($fechaInicio, $fechaFin));
    }

    public function obtenerTotalVentas() {
        $fechaInicio = $_GET["fecha_inicio"];
        $fechaFin = $_GET["fecha_fin"];
        echo json_encode($this->ventaModel->obtenerTotalVentas($fechaInicio, $fechaFin));
    }

    public function obtenerVentasPorProducto() {
        header("Content-Type: application/json");
    
        $ventas = $this->ventaModel->obtenerVentasPorProducto();
        echo json_encode($ventas);
    }
    
}
?>
