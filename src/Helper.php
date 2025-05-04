<?php

/**
 * 
 * @author Tibor Csik <csulok0000@gmail.com>
 */
namespace Csulok0000\Routing;

class Helper {
    
    /**
     * 
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function arrayGet(array $array, string $key, mixed $default = null): mixed {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (!str_contains($key, '.')) {
            return $default;
        }
        
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * 
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public static function arraySet(array &$array, string $key, mixed $value): array {
        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}