<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing;

interface DispatcherInterface {
    
    /**
     * 
     * @param Route $route
     */
    public function dispatch(Route $route);
    
}