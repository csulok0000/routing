<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */
use PHPUnit\Framework\TestCase;

use Csulok0000\Routing\Enums\Method;
use Csulok0000\Routing\Router;

class RouterTest extends TestCase {
    
    public function testGet() {
        $router = new Router();
        
        $router->get('/', fn () => 1, 'home');
        $router->get('/teszt', fn () => 1)
            ->setAction(fn () => 2)
            ->setName('home');
        
        $this->assertEquals(1, $router->match('GET', '/')->getAction()());
        $this->assertEquals(2, $router->match('GET', '/teszt')->getAction()());
        $this->assertEquals('home', $router->match('GET', '/teszt')->getName());
    }
    
}