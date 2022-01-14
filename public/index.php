<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/users/new', function ($request, $response, $args)  {
    $params = [
        'users' => []
    ];
    return $this->get('renderer')->render($response, 'users/user.phtml', $params);
})->setName('new');

$app->get('/users', function ($request, $response) {
    print_r(file_get_contents('userData.json'));
    $params = ['users' => json_decode(file_get_contents('userData.json'), true)];
    return $this->get('renderer')->render($response, 'users/users.phtml', $params);
});

$app->post('/users', function ($request, $response) {
    $user = $request->getParsedBodyParam('user');
    $id = uniqid();
    $filesize = filesize('userData.json');

    $contentArr = [];
    if ($filesize) {
        $fileContent = file_get_contents('userData.json');
        $contentArr = json_decode($fileContent, true);
    }
    $contentArr[$id] = $user;

    file_put_contents('userData.json', json_encode($contentArr));
    return $response->withRedirect('users', 302);
})->setName('users');

$router = $app->getRouteCollector()->getRouteParser();

$app->get('/', function ($request, $response) use ($router) {
    $myUrl = $router->urlFor('new');
    $params = ['myUrl' => $myUrl];
    return $this->get('renderer')->render($response, 'users/user.phtml', $params);
});

$app->run();

