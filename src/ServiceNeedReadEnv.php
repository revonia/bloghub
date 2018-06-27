<?php


namespace Revonia\BlogHub;


trait ServiceNeedReadEnv
{
    private static function getEnv($name, $defaults = null)
    {
        return Env::get(self::envNameWithPerfix($name), $defaults);
    }

    private static function requiredEnv($name)
    {
        return Env::required(self::envNameWithPerfix($name));
    }

    private static function selectEnv($variables)
    {
        $vars = [];
        foreach ($variables as $name => $default) {
            $vars[self::envNameWithPerfix($name)] = $variables;
        }

        return Env::select($vars);
    }

    public static function envNameWithPerfix($name)
    {
        $class = preg_replace('/Service$/', '', class_basename(static::class));

        return 'BLOG_HUB_' . strtoupper(str_snake($class)) . '_' . $name;
    }


}