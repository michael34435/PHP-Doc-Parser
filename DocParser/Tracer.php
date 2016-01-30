<?php

namespace DocParser;

class Tracer
{
    public static function extract($className)
    {
        $reflector = new \ReflectionClass($className);
        $path      = $reflector->getFileName();
        return file_get_contents($path);
    }
}
