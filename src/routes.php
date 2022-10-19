<?php


use MZierdt\Albion\Handler\BlackMarketHandler;
use MZierdt\Albion\Handler\CalculateInfoHandler;
use MZierdt\Albion\Handler\ShowResourcePriceHandler;
use Twig\Environment;

$serviceManager = require __DIR__ . '/../container.php';
$twigEnvironment = $serviceManager->get(Environment::class);

/** @var PDO $pdo */
/** @var Environment $twig */
$dispatcher = FastRoute\simpleDispatcher(
    function (FastRoute\RouteCollector $r) use ($serviceManager): void {
        $r->addRoute(['GET', 'POST'], '/[info]', $serviceManager->get(ShowResourcePriceHandler::class));
        $r->addRoute(['GET', 'POST'], '/calculate', $serviceManager->get(BlackMarketHandler::class));
        $r->addRoute(['GET', 'POST'], '/calculate/blackmarket', $serviceManager->get(CalculateInfoHandler::class));
    }
);

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
$pos = strpos($uri, '?');
if ($pos !== false) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo $twigEnvironment->render('error404.html.twig');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo $twigEnvironment->render('error405.html.twig');
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $htmlResponse = $handler->handler($vars, $_REQUEST);
        echo $htmlResponse->getBody();
        break;
}
