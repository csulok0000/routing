# Routing

![Status: in development](https://img.shields.io/badge/Status-in%20development-red)

> ⚠️ This project is currently under development and **not recommended for production use**.

Simple PHP Router

Author: Tibor Csik <csulok0000@gmail.com>

## Install

Install Composer and run following command in your project's root directory:

~~~bash
$ composer require csulok0000/routing "dev-main"
~~~

## Getting Started

~~~php
use Csulok0000\Routing\Router;

$router = new Router();

$router->get('/', function () {
    echo "<h1>Home</h1>";
});

$router->get('/contents/{id}', function ($id) {
    echo "<h1>Content: $id</h1>";
});

try {
    $response = $route->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Exception $e) {

}

$response?->send();
~~~



## Routes

~~~php
use Csulok0000\Routing\Route;
use Csulok0000\Routing\Enums\Method;

// The HTTP method can be specified in several ways
new Route('GET', '/a/{b}', fn($b) => 'Hello ' . $b);
new Route(['GET'], '/a/{b}', fn($b) => 'Hello ' . $b);
new Route(Method::Get, '/a/{b}', fn($b) => 'Hello ' . $b);
new Route([Method::Get], '/a/{b}', fn($b) => 'Hello ' . $b);

// ...or multiple methods at once
new Route(['GET', 'POST'], '/a/{b}', fn($b) => 'Hello ' . $b);

~~~

There are also multiple ways to define the action:
~~~php
new Route('GET', '/', fn($b) => 'Hello ' . $b);
new Route('GET', '/', [TestController::class, 'index']);
new Route('GET', '/', Closure::fromCallable([TestController::class, 'index']));
~~~

### Named Routes

Routes can be given names, allowing us to reference them later — for example, when generating a URL.

~~~PHP
new Route('GET', '/', fn($b) => 'Hello ' . $b, 'route1');
// or
(new Route('GET', '/', fn($b) => 'Hello ' . $b))->setName('route1');
~~~

## Router class

### Adding a route

~~~PHP
...
$router->addRoute(new Route('GET', '/', fn() => ''));
~~~

### Helper methods

The Router class includes several helper methods to simplify adding routes.

~~~PHP
...
// For individual HTTP methods
$router->get('/', fn () => '');
$router->head('/', fn () => '');
$router->post('/', fn () => '');
$router->put('/', fn () => '');
$router->patch('/', fn () => '');
$router->delete('/', fn () => '');
$router->options('/', fn () => '');

// For all HTTP methods
$router->any('/', fn () => '');
~~~
