<?php
require_once "./vendor/autoload.php";
echo "<pre>";
$locator = new \Symfony\Component\Config\FileLocator(array(__DIR__ . "/services"));

$container = new \Symfony\Component\DependencyInjection\ContainerBuilder();

$containerLoader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($container, $locator);
$containerLoader->load('content/config/services.yml');
$containerLoader->load('target/config/services.yml');

$context = new \Symfony\Component\Routing\RequestContext();
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$context->fromRequest($request);


$routerLoader = new \Symfony\Component\Routing\Loader\YamlFileLoader($locator);
$collection = $routerLoader->load('content/config/routes.yml');
$collection->addCollection($routerLoader->load('target/config/routes.yml'));

$mainRouterLocator = new \Symfony\Component\Config\FileLocator([__DIR__ . "/config"]);
$mainRouterLoader = new \Symfony\Component\Routing\Loader\YamlFileLoader($mainRouterLocator);
$collection->addCollection($mainRouterLoader->load('routes.yml'));

$router = new \AdServer\Routing\Components\Router(
    $routerLoader,
    $collection,
    [],
    $context
);
$router->setRouteCollection($collection);


$resolver = new \Symfony\Component\HttpKernel\Controller\ControllerResolver();

\AdServer\Engine\Engine::run(
    $container,
    $router,
    $resolver
);
