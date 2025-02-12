# Prueba Técnica: Gestión de Ventas
 
Descripción
Este proyecto es una aplicación web desarrollada para la gestión de ventas. La aplicación permite registrar ventas, consultar ventas por rango de fechas, mostrar totales de ventas en un periodo determinado, generar gráficos y exportar los datos a formatos Excel o PDF. La solución utiliza tecnologías modernas como PHP (con PDO para interacciones con la base de datos), MySQL, Bootstrap, y AJAX para un manejo dinámico de la interfaz de usuario.

Requisitos
PHP 7.0 o superior
MySQL 5.7 o superior
Apache o Nginx (Servidor web)
Bootstrap 4 o superior
jQuery (para manejo de AJAX)
Instalación
Clonar el repositorio:

bash
Copiar
git clone https://github.com/tu_usuario/gestion_ventas.git
Configurar la base de datos:

Crea una base de datos en MySQL llamada ventas_db e importa el archivo SQL de la base de datos que se encuentra en el directorio sql.

sql
Copiar
CREATE DATABASE ventas_db;
Configurar el archivo de conexión PHP:

Abre el archivo config.php en el directorio raíz y configura los detalles de tu base de datos MySQL:

php
Copiar
define('DB_HOST', 'localhost');
define('DB_NAME', 'ventas_db');
define('DB_USER', 'root');
define('DB_PASS', 'password');
Subir los archivos a tu servidor web:

Asegúrate de subir todo el proyecto al directorio de tu servidor web, por ejemplo, en htdocs si estás usando XAMPP.

Acceder a la aplicación:

Una vez que todo esté configurado, accede a la aplicación a través de tu navegador usando la URL configurada en el servidor.

Funcionalidades
Registrar Venta

El usuario puede registrar una venta proporcionando el nombre del producto, cantidad, precio y la fecha de la venta.
El formulario de registro está disponible en la página principal.
php
Copiar
// Ejemplo de código para registrar una venta
$stmt = $pdo->prepare("INSERT INTO ventas (producto, cantidad, precio, fecha_venta) VALUES (?, ?, ?, ?)");
$stmt->execute([$producto, $cantidad, $precio, $fecha_venta]);
Consultar Ventas por Rango de Fechas

Utilizando AJAX, el usuario puede consultar las ventas filtradas por un rango de fechas (inicio y fin).
La consulta de ventas se realiza de forma dinámica sin necesidad de recargar la página.
js
Copiar
// Ejemplo de código AJAX para filtrar ventas por fecha
$.ajax({
    url: 'consultar_ventas.php',
    method: 'POST',
    data: {fecha_inicio: '2025-01-01', fecha_fin: '2025-01-31'},
    success: function(response) {
        // Procesar los datos de la respuesta y mostrar en la interfaz
    }
});
Mostrar Total de Ventas en un Periodo

La aplicación permite calcular y mostrar el total de ventas realizadas en un periodo específico. Este cálculo se puede visualizar en la interfaz después de realizar una consulta por fechas.
php
Copiar
// Ejemplo de cálculo total de ventas
$stmt = $pdo->prepare("SELECT SUM(cantidad * precio) AS
