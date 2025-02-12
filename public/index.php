<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/ventaController.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($uri, '/export/') === 0) {
    return false; 
}

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/venta', 'ventaController@obtenerVentas');
    $r->addRoute('GET', '/', 'viewVentas');
    $r->addRoute('GET', '/venta/total', 'ventaController@obtenerTotalVentas');
    $r->addRoute('GET', '/venta/grafico', 'ventaController@obtenerVentasPorProducto');
    $r->addRoute('POST', '/venta/registrar', 'ventaController@registrarVenta');
    $r->addRoute('GET', '/venta/export/excel', 'ventaController@exportarExcel');
    $r->addRoute('GET', '/venta/export/pdf', 'ventaController@exportarPDF');


});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo json_encode(["error" => "Método no permitido"]);
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        if ($handler === 'viewVentas') {
            require_once __DIR__ . '/../views/viewVentas.php';
        } else {
            [$controllerClass, $method] = explode('@', $handler);
            
            require_once __DIR__ . "/../controllers/{$controllerClass}.php";
            
            $controllerInstance = new $controllerClass();
            call_user_func_array([$controllerInstance, $method], $vars);
        }
        break;
}
