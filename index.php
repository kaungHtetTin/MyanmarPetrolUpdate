<?php

require_once 'Core/Router.php';
require_once 'Core/Controller.php';
require_once 'Core/Model.php';

spl_autoload_register(function($class) {
    $class = str_replace("\\", "/", $class);
    include($class . ".php");
});

use Core\Config;
Config::loadEnv();

$router = new Router();

// Define routes


$requestUri = $_SERVER['REQUEST_URI'];
// Define your API route prefix
$apiPrefix = '/petrol/api';
$webPrefix = "petrol";
// if (strpos($requestUri, $apiPrefix) === 0) {
//     $receivedApiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
//     $validApiKey = getenv('API_KEY');
//     if ($receivedApiKey !== $validApiKey) {
//         http_response_code(401);
//         echo json_encode(["message" => "Unauthorized"]);
//         exit;
//     }
// }

require_once 'Route/api.php';
require_once 'Route/web.php';

// Dispatch the current route
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$router->dispatch($url, $requestMethod);

?>