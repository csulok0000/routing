<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing;

class Response {
    
    /**
     * 
     * @param string $content
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(private string $content = '', private int $statusCode = Enums\HttpStatusCode::Ok->value, private array $headers = []) {
        ;
    }
    
    /**
     * 
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void {
        $this->content = $content;
    }
    
    /**
     * 
     * @param int $statusCode
     * @return void
     */
    public function setStatusCode(int $statusCode): void {
        $this->statusCode = $statusCode;
    }
    
    /**
     * 
     * @param string $uri
     * @param int $statusCode
     * @return static
     */
    public function redirect(string $uri, int $statusCode = Enums\HttpStatusCode::MovedPermanently->value): Static {
        $this->headers['Location'] = $uri;
        $this->statusCode = $statusCode;
        return $this;
    }
    
    /**
     * 
     * @return void
     */
    public function send(): void {
        if (!headers_sent()) {
            header('HTTP/1.1 ' . $this->statusCode);
            foreach ($this->headers as $header => $value) {
                header($header . ': ' . $value);
            }
        }
        
        echo $this->content;
    }
    
    /**
     * 
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }
}