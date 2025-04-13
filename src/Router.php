<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing;

use Csulok0000\Routing\Enums\Method;
use Csulok0000\Routing\Route;

class Router {
    
    /**
     * 
     * @var array
     */
    private array $middlewares = [];
    
    /**
     * 
     * @var array
     */
    private array $routes = [
        Method::Delete->value   => [],
        Method::Get->value      => [],
        Method::Patch->value    => [],
        Method::Post->value     => [],
        Method::Put->value      => []
    ];
    
    /**
     * 
     * @param string $prefix
     * @param string $namespace
     * @param array $middlewares
     */
    public function __construct(private string $prefix = '', private string $namespace = '', array $middlewares = [], public ?Router $parent = null) {
        foreach ($middlewares as $middleware) {
            $this->addMiddleware($middleware);
        }
    }
    
    /**
     * 
     * @param string $middleware
     */
    public function addMiddleware(string $middleware) {
        $this->middlewares[] = $middleware;
    }
    
    /**
     * 
     * @param string $prefix
     */
    public function setPrefix(string $prefix) {
        $this->prefix = $prefix;
    }
    
    /**
     * 
     * @param string $namespace
     */
    public function setNamespace(string $namespace) {
        $this->namespace = $namespace;
    }
    
    /**
     * 
     * @param string $uri
     * @param callable\Closure $action
     * @param string $name
     * @return Route
     */
    public function get(string $uri, callable|\Closure $action, ?string $name = null): Route {
        return $this->addRoute(new Route([Method::Get], $uri, $action, $name));
    }
    
    /**
     * 
     * @param string $uri
     * @param callable|\Closure $action
     * @param string|null $name
     * @return Route
     */
    public function post(string $uri, callable|\Closure $action, ?string $name = null): Route {
        return $this->addRoute(new Route([Method::Post], $uri, $action, $name));
    }
    
    /**
     * 
     * @param string $uri
     * @param callable|\Closure $action
     * @param string|null $name
     * @return Route
     */
    public function put(string $uri, callable|\Closure $action, ?string $name = null): Route {
        return $this->addRoute(new Route([Method::Put], $uri, $action, $name));
    }
    
    /**
     * 
     * @param string $uri
     * @param callable|\Closure $action
     * @param string|null $name
     * @return Route
     */
    public function patch(string $uri, callable|\Closure $action, ?string $name = null): Route {
        return $this->addRoute(new Route([Method::Patch], $uri, $action, $name));
    }
    
    /**
     * 
     * @param string $uri
     * @param callable|\Closure $action
     * @param string|null $name
     * @return Route
     */
    public function delete(string $uri, callable|\Closure $action, ?string $name = null): Route {
        return $this->addRoute(new Route([Method::Delete], $uri, $action, $name));
    }
    
    /**
     * 
     * @param string $uri
     * @param callable|\Closure $action
     * @param string|null $name
     * @return Route
     */
    public function any(string $uri, callable|\Closure $action, ?string $name = null): Route {
        return $this->addRoute(new Route([Method::Any], $uri, $action, $name));
    }
    
    /**
     * 
     * @return array
     */
    public function getRoutes(): array {
        return $this->routes;
    }
    
    public function group(array $options, callable $groupCallable): void {
        $group = new Router(
            prefix: $options['prefix'] ?? '',
            namespace: $options['namespace'] ?? '',
            middlewares: $options['middlewares'] ?? [],
            parent: $this
        );
        
        $groupCallable($group);
    }
    
    /**
     * 
     * @return array
     */
    public function getMiddlewares(): array {
        return $this->middlewares;
    }
    
    /**
     * 
     * @return string
     */
    public function getPrefix(): string {
        return $this->prefix;
    }
    
    /**
     * 
     * @return string
     */
    public function getNamespace(): string {
        return $this->namespace;
    }
    
    /**
     * 
     * @param array|string|HttpMethod $methods
     * @param string $uri
     * @param callable\Closure $action
     * @param string $name
     * @return Route
     */
    public function addRoute(Route $route): Route {
        
        /* @var $route Route */
        $route->addMiddleware($this->getMiddlewares());
                
        // Prefix
        if ($this->getPrefix()) {
            $route->setUri(rtrim($this->getPrefix(), '/') . '/' . ltrim($route->getUri(), '/'));
        }
                
        // Namespace
        if ($this->getNamespace() && is_string($route->getAction()) && substr($route->getAction(), 0, 1) != '\\') {
            $route->setAction(rtrim($this->getNamespace(), '\\') . '\\' . $route->getAction());
        }
        
        if ($this->parent) {
            return $this->parent->addRoute($route);
        }
        
        // Összes metódus
        $methods = in_array(Method::Any, $route->getMethods()) ? Method::allowedHttpMethods() : $route->getMethods();
        
        $tokens = explode('/', strtolower(trim($route->getUri(), '/')));
        
        // Minden metódusba eltároljuk
        foreach ($methods as $method) {
            $parent = &$this->routes[$method->value];
            
            foreach ($tokens as $token) {
                if (!array_key_exists($token, $parent)) {
                    $parent[$token] = [];
                }
                $parent = &$parent[$token];
            }
            
            $parent['#'] = $route;
        }
        
        return $route;
    }
    
    /**
     * 
     * @param string $httpMethod
     * @param string $uri
     * @return Route
     * @throws Exceptions\RouteNotFoundException
     */
    public function match(string $httpMethod, string $uri): bool|Route {
        $method = Method::tryFrom(strtolower($httpMethod));
        if (!$method) {
            throw new Exceptions\MethodNotAllowedException('A ' . $httpMethod . ' kérés nem támogatott!');
        }
        
        // Routeok rendezése
        $this->sortRoutes();
        
        // Nincs route az adott kéréshez
        if (!$this->routes[$method->value]) {
            return false;
        }
        
        // Url feldarabolása
        $tokens = explode('/', strtolower(trim($uri, '/')));
        
        // Ellenőrízzük, hogy illeszkedik-e valamelyik route-ra
        $route = $this->tokenCheck($this->routes[$method->value], $tokens);
        
        // Nincs találat
        if (!$route) {
            throw new Exceptions\RouteNotFoundException('A ' . $uri . ' route nem található!');
        }
        
        return $route;
    }
    
    private function tokenCheck(array &$routes, array $tokens, int $index = 0): bool|Route {
        // Ha létezik
        $token = $tokens[$index];
        $route = null;
        $last = count($tokens) - 1 == $index;

        if (isset($routes[$token])) {
            if ($last && ($routes[$token]['#'] instanceof Route)) {
                return $route = $routes[$token]['#'];
            } elseif (!$last) {
                if (($route = $this->tokenCheck($routes[$token], $tokens, $index + 1))) {
                    return $route;
                }
            }
            return false;
        }
        
        // Változókat nézzük végig
        else {
            foreach ($routes as $routeToken => $tmp) {
                // Csak a változós tokenek érdekelnek
                if (strpos($routeToken, '{') !== 0) {
                    continue;
                }
                
                if ($last && ($routes[$routeToken]['#'] instanceof Route)) {
                    return $routes[$routeToken]['#']->bindParameters([trim($routeToken, '{}') => $tokens[$index]]);
                } elseif (!$last) {
                    if (($route = $this->tokenCheck($routes[$routeToken], $tokens, $index + 1))) {
                        return $route->bindParameters(array_merge($route->getParameters(), [trim($routeToken, '{}') => $tokens[$index]]));
                    }
                }
            }
        }
          
        return false;
    }
    
    /**
     * 
     * @return void
     */
    private function sortRoutes(): void {
        $this->recursiveKsort($this->routes);
    }
    
    /**
     * 
     * @param array $array
     */
    private function recursiveKsort(array &$array) {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursiveKsort($value);
            }
        }
        ksort($array);
    }
    
}