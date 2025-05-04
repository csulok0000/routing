<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing;

class RouteDispatcher implements DispatcherInterface {
    
    #[\Override]
    public function dispatch(Route $route): Response {
        // Midlewares
        $middlewares = $route->getMiddlewares();
        
        foreach ($middlewares as $middleware) {
            $tmp = new $middleware;
            $res = $tmp->handle(Request::createFromGlobals());
            if ($res instanceof Response) {
                return $res;
            }
            
            if (!$res) {
                throw new \Exception('Middleware fail: ' . $tmp);
            }
        }
        
        // Run
        $action = $route->getAction();
        
        if (is_callable($action)) {
            $response = $action();
        } elseif (is_array($action)) {
            $class = $action[0];
            $method = $action[1] ?? '';
            $controller = new $class();
            $response = call_user_func_array([$controller, $method], $route->getParameters());
        }

        if ($response instanceof Response) {
            return $response;
        }
        
        return new Response($response);
    }
}