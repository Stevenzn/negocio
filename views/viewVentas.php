<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negocio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Negocio</h2>

    <div class="card shadow p-4 mb-4">
        <h4 class="text-primary">Registrar Venta</h4>
        <form id="formVenta" class="row g-3">
            <div class="col-md-6">
                <input type="text" id="producto" name="producto" class="form-control" placeholder="Producto" required>
            </div>
            <div class="col-md-3">
                <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" id="precio" name="precio" class="form-control" placeholder="Precio" required>
            </div>
            <div class="col-md-6">
                <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100">Registrar Venta</button>
            </div>
        </form>
    </div>

    <div class="card shadow p-4 mb-4">
        <h4 class="text-secondary">Filtrar Ventas</h4>
        <div class="row g-3">
            <div class="col-md-5">
                <input type="date" id="fechaInicio" class="form-control" required>
            </div>
            <div class="col-md-5">
                <input type="date" id="fechaFin" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button id="btnFiltrar" class="btn btn-success w-100">Buscar</button>
            </div>
        </div>
    </div>

    <div class="text-center mb-4">
        <button onclick="exportarExcel()" class="btn btn-success me-2">Exportar a Excel</button>
        <button onclick="exportarPDF()" class="btn btn-danger">Exportar a PDF</button>
    </div>

    <div class="card shadow p-4 mb-4">
        <h4 class="text-secondary">Total de Ventas</h4>
        <p id="totalVentas" class="fs-5 fw-bold text-success">Total: $0.00</p>
    </div>


    <div class="card shadow p-4">
        <h4 class="text-info">Listado de Ventas</h4>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody id="tablaVentas">
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow p-4 mb-4">
    <h4 class="text-info">Ventas por Producto</h4>
    <canvas id="graficoVentas"></canvas>
</div>

</div>

<script>

function exportarExcel() {
    let fechaInicio = document.getElementById("fechaInicio").value;
    let fechaFin = document.getElementById("fechaFin").value;
    window.location.href = `/venta/export/excel?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
}

function exportarPDF() {
    let fechaInicio = document.getElementById("fechaInicio").value;
    let fechaFin = document.getElementById("fechaFin").value;
    window.location.href = `/venta/export/pdf?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
}

    document.getElementById("formVenta").addEventListener("submit", function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        fetch("/venta/registrar", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Venta registrada correctamente");
                filtrarVentas();
                document.getElementById("formVenta").reset();
            } else {
                alert("Error al registrar la venta: " + data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    });

    function obtenerTotalVentas() {
    let fechaInicio = document.getElementById("fechaInicio").value;
    let fechaFin = document.getElementById("fechaFin").value;

    fetch(`/venta/total?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Error: " + data.error);
            return;
        }
        document.getElementById("totalVentas").innerText = `Total: $${data.total}`;
    })
    .catch(error => console.error("Error en fetch:", error));
    }

    function generarGraficoVentas() {
    fetch("/venta/grafico")
    .then(response => response.json())
    .then(data => {

        let productos = data.map(venta => venta.producto);
        let cantidades = data.map(venta => venta.total_vendido);

        let ctx = document.getElementById("graficoVentas").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: productos,
                datasets: [{
                    label: "Ventas por Producto",
                    data: cantidades,
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgb(40, 134, 197)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    })
    .catch(error => console.error("Error al obtener datos:", error));
}

document.addEventListener("DOMContentLoaded", generarGraficoVentas);

    function filtrarVentas() {
        let fechaInicio = document.getElementById("fechaInicio").value;
        let fechaFin = document.getElementById("fechaFin").value;

        fetch(`/venta?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
        .then(response => response.json())
        .then(data => {
            let tbody = document.querySelector("#tablaVentas");
            tbody.innerHTML = "";
            data.forEach(venta => {
                tbody.innerHTML += `<tr>
                    <td>${venta.producto}</td>
                    <td>${venta.cantidad}</td>
                    <td>${venta.precio}</td>
                    <td>${venta.fecha_venta}</td>
                </tr>`;
            });
        });
        obtenerTotalVentas();
    }

    document.getElementById("btnFiltrar").addEventListener("click", filtrarVentas);
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>