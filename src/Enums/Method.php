<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */

namespace Csulok0000\Routing\Enums;

enum Method: string {
    
    case Any        = 'any';
    case Delete     = 'delete';
    case Get        = 'get';
    case Head       = 'head';
    case Options    = 'options';
    case Patch      = 'patch';
    case Post       = 'post';
    case Put        = 'put';
    
    /**
     * 
     * @return array
     */
    public static function allowedHttpMethods(): array {
        return [
            self::Delete,
            self::Get,
            self::Head,
            self::Options,
            self::Patch,
            self::Post,
            self::Put
        ];
    }
    
}