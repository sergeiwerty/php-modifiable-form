<?php

use Slim\Factory\AppFactory;
use DI\Container;

require '/composer/vendor/autoload.php';

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$repo = new App\PostRepository();

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/posts', function ($request, $response) {
return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/posts{id}', function ($request, $response) {
$params = [$args['id']];
return $this->get('renderer')->render($response, 'index.phtml');
});