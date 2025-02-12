<?php
require_once __DIR__ . "../../config/database.php";
use Dompdf\Dompdf;

class Venta {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function registrarVenta($producto, $cantidad, $precio, $fecha) {
        $sql = "INSERT INTO ventas (producto, cantidad, precio, fecha_venta) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$producto, $cantidad, $precio, $fecha]);
    }

    public function obtenerVentas($fechaInicio, $fechaFin) {
        $sql = "SELECT * FROM ventas WHERE fecha_venta BETWEEN ? AND ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVentasPorProducto() {
        $sql = "SELECT producto, SUM(cantidad) AS total_vendido FROM ventas GROUP BY producto";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function obtenerTotalVentas($fechaInicio, $fechaFin) {
        $sql = "SELECT SUM(precio * cantidad) AS total FROM ventas WHERE fecha_venta BETWEEN ? AND ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function exportarExcel($fechaInicio, $fechaFin) {
        $ventas = $this->obtenerVentas($fechaInicio, $fechaFin);
    
        // Configurar cabeceras para Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=ventas.xls");
    
        echo "<table border='1'>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                </tr>";
    
        foreach ($ventas as $venta) {
            echo "<tr>
                    <td>{$venta['producto']}</td>
                    <td>{$venta['cantidad']}</td>
                    <td>{$venta['precio']}</td>
                    <td>{$venta['fecha_venta']}</td>
                  </tr>";
        }
    
        echo "</table>";
        exit;
    }
    

    public function exportarPDF($fechaInicio, $fechaFin) {
        $ventas = $this->obtenerVentas($fechaInicio, $fechaFin);

        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 12);

        $pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->Cell(50, 10, 'Producto', 1);
        $pdf->Cell(30, 10, 'Cantidad', 1);
        $pdf->Cell(30, 10, 'Precio', 1);
        $pdf->Cell(40, 10, 'Fecha', 1);
        $pdf->Ln();

        foreach ($ventas as $venta) {
            $pdf->Cell(50, 10, $venta['producto'], 1);
            $pdf->Cell(30, 10, $venta['cantidad'], 1);
            $pdf->Cell(30, 10, $venta['precio'], 1);
            $pdf->Cell(40, 10, $venta['fecha_venta'], 1);
            $pdf->Ln();
        }

        $filename = "Reporte_Ventas_" . date('Ymd_His') . ".pdf";
        $pdf->Output($filename, 'D');
        exit;
    }
}
?>
