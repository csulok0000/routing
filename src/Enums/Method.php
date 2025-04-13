<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing\Enums;

enum Method: string {
    
    case Get    = 'get';
    case Post   = 'post';
    case Put    = 'put';
    case Patch  = 'patch';
    case Delete = 'delete';
    case Any    = 'any';
    
    /**
     * 
     * @return array
     */
    public static function allowedHttpMethods(): array {
        return [
            self::Delete,
            self::Get,
            self::Patch,
            self::Post,
            self::Put
        ];
    }
    
}