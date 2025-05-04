<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing;

use Csulok0000\Routing\Helper;

class Request {
    
    /**
     * 
     * @param array $get
     * @param array $post
     * @param array $session
     * @param array $cookie
     * @param array $files
     * @param array $server
     */
    public function __construct(
        private array $get = [],
        private array $post = [],
        private ?array &$session = [],
        private array $cookie = [],
        private array $files = [],
        private array $server = []
    ) {
        
    }
    
    /**
     * 
     * @return static
     */
    public static function createFromGlobals(): static {
        return new Static(
            $_GET,
            $_POST,
            $_SESSION,
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed {
        return Helper::arrayGet($this->get, $key, $default);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post(string $key, mixed $default = null): mixed {
        return Helper::arrayGet($this->post, $key, $default);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function session(string $key, mixed $default = null): mixed {
        return Helper::arrayGet($this->session ?? [], $key, $default);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function cookie(string $key, mixed $default = null): mixed {
        return Helper::arrayGet($this->cookie, $key, $default);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function server(string $key, mixed $default = null): mixed {
        return Helper::arrayGet($this->server, $key, $default);
    }
    
    /**
     * 
     * @return string
     */
    public function getMethod(): string {
        return $this->server('REQUEST_METHOD');
    }
    
    /**
     * 
     * @return string
     */
    public function getUri(): string {
        return $this->server('REQUEST_URI');
    }
}