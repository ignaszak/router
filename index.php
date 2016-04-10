<?php

use Ignaszak\Router\Collection\Route;
use Ignaszak\Router\Router;
use Ignaszak\Router\Conf\Host;
use Ignaszak\Router\ResponseStatic;
use Ignaszak\Router\Collection\Cache;

include __DIR__ . '/autoload.php';

// Create Router instance to collect routes
$route = Route::start();

// Add new route
// First parameter: name (is not required but if is defined
// it must be unique for each defined routes).
// Second: pattern
// Third: http method (it is possible to compine all http methods e.g.:
// 'GET|POST', not required, if is empty - route match for all methods)
$route->add('test', '/test/(\w+)/', 'GET');

// There are two more add methods:
$route->get('get', '/match/only/get');
$route->post('post', '/match/only/post');

// Add tokens
$route->add(null, '/tokens/{token1}/{token2}/')->tokens([
    'token1' => '(\w+)',
    'token2' => '(\d+)'
]);

// It is possible to define global token avilable for all routes.
$route->addTokens([
    'slug' => '(\w+)',
    'user' => '(\w+)',
    'page' => '(\d+)'
]);

// Add controller
$route->add('user', '/user/{user}/')->controller('UserController');

// Define controller from route
$route->add(null, '/test/{controller}/{action}')
    ->controller('\\Namespace\\{controller}::{action}')
    ->tokens([
        'controller' => '([a-zA-Z]+)',
        'action' => '([a-zA-Z]+)'
    ]);

// Add attachment
$route->add('attach', '/attach/{name}/(\w+)/{id}/')
    ->tokens([
        'name' => '(\w+)',
        'id' => '(\d+)'
    ])
    ->attach(function ($name, $string, $id) {
        echo "{$name}, {$string}, {$id}";
    });
// Disable auto calling
$route->add(null, '/attach/test/')->attach(function () {
    /* Do something */
}, false);

// Group routes
// Every added route after this method will be in the same group
$route->group('groupName');
// Disable group
$route->group();

// Add defined regular expressions
// Router provides some defined regular expressions such as:
//   @base     - use to define default route
//   @notfound - not found
//   @digit    - digits [0-9]
//   @alpha    - alphabetic characters [A-Za-z_-]
//   @alnum    - alphanumeric characters [A-Za-z0-9_-]
$route->add('defined', '/regex/@alpha/{id}')->tokens(['id' => '@digit']);

// Add default route
$route->add('default', '/@base')->controller('DefaultController');

// Not found
$route->add('error', '/@notfound')->attach(function () {
    throw new Exception('404 Not Found');
});

// Define custom regular expression. It will be avilable for all routes
$route->addPatterns([
    'day' => '([0-9]{2})',
    'month' => '([0-9]{2})',
    'year' => '([0-9]{4})'
]);
$route->add(null, '/@year/@month/@day/');

// Generate cache
$cache = new Cache($route);
$cache->tmpDir = __DIR__; // optional, default dir: ./src/Collection

// Get response
$router = new Router($cache);

// Start parsing
// Router::run([Host $host [, string $baseQuery [, string HttpMethod]])
$response = $router->run(new Host());
// Class Ignaszak\Router\Host([string $baseQuery])
// provides current request and http method
// $baseQuery argument defines folder via site is avilable:
// http://fullSite.com/Adress => $baseQuery = /Adress (without slash on end)
// It is possible to define custom request and http method:
// $router->run(null, '/customRequest', 'GET');

// Display response (also avilable via static methods)
// Display matched params
echo 'Routes:<pre>';
print_r($response->getParams());
ResponseStatic::getParams();
echo '</pre>';

// Get concrete param
echo $response->getParam('token');
ResponseStatic::getParam('token');

// Get route name
echo 'Route name: ';
echo $response->getName();
ResponseStatic::getName();
echo '<br />';

// Get route group
echo 'Route group: ';
echo $response->getGroup();
ResponseStatic::getGroup();
echo '<br />';

// Get route controller
echo 'Controller: ';
echo $response->getController();
ResponseStatic::getController();
echo '<br />';

// Get attachment
$attachment = $response->getAttachment();
$attachment();
ResponseStatic::getAttachment();

// Get link
echo 'Link: ';
echo $response->getLink('user', [
    'user' => 'UserName'
]);
ResponseStatic::getLink('user', [
    'user' => 'UserName'
]);
