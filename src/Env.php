<?php

namespace Revonia\BlogHub;

class Env
{
    public static function get($name, $defaults = null)
    {
        return getenv($name) === false ? $defaults : getenv($name);
    }

    public static function required($name)
    {
        if (getenv($name) === false) {
            throw new \RuntimeException('Environment variable \'' . $name . '\' is required.');
        }

        return getenv($name);
    }

    public static function select(array $variables)
    {
        $result = [];
        foreach ($variables as $name => $defaults) {
            $value = getenv($name);
            if ($value === false && is_required($defaults)) {
                throw new \RuntimeException('Environment variable \'' . $name . '\' is required.');
            }
            $result[$name] = $value;
        }

        return $result;
    }
}