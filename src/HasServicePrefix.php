<?php


namespace Revonia\BlogHub;


trait HasServicePrefix
{
    protected static $prefix;

    protected static function getPrefix()
    {
        if (static::$prefix) {
            return static::$prefix;
        }

        return static::$prefix = strtoupper(str_snake(
            preg_replace('/Service$/', '', class_basename(static::class))
        ));
    }
}