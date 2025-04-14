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
~~~

