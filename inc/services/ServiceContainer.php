<?php

namespace Commerce\Services;

class ServiceContainer {

    protected static array $services = [];

    public static function register(string $key, object $service): void{
        self::$services[$key] = $service;
    }

    public static function get(string $key) : ?object{
        return self::$services[$key] ?? null; 
    }

    public static function has(string $key): bool{
        return isset(self::$services[$key]);
    }


}