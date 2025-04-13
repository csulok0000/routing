<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing;

use Csulok0000\Routing\Enums\Method;

class Route {
    
    /**
     * 
     * @var array
     */
    private array $methods = [];
    
    /**
     * 
     * @var array
     */
    private array $middlewares = [];
    
    /**
     * 
     * @var \Closure
     */
    private \Closure $action;
    
    /**
     * 
     * @var array
     */
    private array $params = [];
    
    /**
     * 
     * @param string|array|Method $methods
     * @param string $uri
     * @param callable|\Closure $action
     * @param string|null $name
     */
    public function __construct(string|array|Method $methods, private string $uri, callable|\Closure $action, private ?string $name = '') {
        // HTTP methódusok egységes alakra hozása
        if (is_string($methods) || $methods instanceof Method) {
            $methods = [$methods];
        }
        
        foreach ($methods as $method) {
            $this->methods[] = $method instanceof Method ? $method : Method::from($method);
        }
        
        $this->setAction($action);
        
    }
    
    /**
     * 
     * @return array
     */
    public function getMethods(): array {
        return $this->methods;
    }
    
    /**
     * 
     * @return string
     */
    public function getUri(): string {
        return $this->uri;
    }
    
    /**
     * 
     * @return callable
     */
    public function getAction(): \Closure {
        return $this->action;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getName(): string|null {
        return $this->name;
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
     * @return array
     */
    public function getParameters(): array {
        return $this->params;
    }
    
    /**
     * 
     * @param string $uri
     * @return static
     */
    public function setUri(string $uri): static {
        $this->uri = $uri;
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @return static
     */
    public function setName(?string $name): static {
        $this->name = $name;
        return $this;
    }
    
    /**
     * 
     * @param callable|\Closure $action
     * @return static
     */
    public function setAction(callable|\Closure $action): static {
        $this->action = is_callable($action) ? \Closure::fromCallable($action) : $action;
        return $this;
    }
    
    /**
     * 
     * @param string|array $middlewares
     * @return static
     */
    public function addMiddleware(string|array $middlewares): static {
        if (is_string($middlewares)) {
            $this->middlewares[] = $middlewares;
        } elseif (is_array($middlewares)) {
            foreach ($middlewares as $middleware) {
                $this->middlewares[] = $middleware;
            }
        }
        
        return $this;
    }
    
    /**
     * 
     * @param array $parameters
     */
    public function bindParameters(array $parameters): static {
        $this->params = $parameters;
        return $this;
    }
    
}