<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('APP_DIRECTORY', __DIR__ . '/../');



// autoloader de nos classes
require APP_DIRECTORY . 'autoloader.php';

// autoloader des librairies incluses via composer
require APP_DIRECTORY . 'vendor/autoload.php';


// on défini nos routes ici
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    // page d'accueil
    $r->addRoute('GET', '/', IndexController::class . '/index');
    // Page des posts
    $r->addRoute('GET', '/register/', RegisterController::class . '/index');

    // Page détail d'un post
    $r->addRoute('GET', '/login/', LoginController::class . '/index');
});



//




// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

//var_dump($uri);die;


// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);



$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:

        // ... 404 Not Found
        // Todo : definir une page d'erreur
        echo 'PAGE NOT FOUND';
        break;
    

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        die('405');
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($class, $method) = explode("/", $handler, 2);

        // on appelle automatique notre controlleur, avec la bonne méthode et les bons paramètres donnés à notre fonction
        // Exemple pour la syntaxe "IndexController::class . '/index'", voici ce qui sera appelé : "IndexController->index()"

        call_user_func_array(array(new $class, $method), $vars);
        break;
}