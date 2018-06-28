<?php

namespace Revonia\BlogHub;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * @param ContainerInterface $container
 * @param $class
 * @param $method
 * @param array $arguments
 * @return mixed
 * @throws \ReflectionException
 */
function call_method_with_injection(ContainerInterface $container, $class, $method, array $arguments = [])
{
    $refection = new \ReflectionMethod($class, $method);

    $params = $refection->getParameters();

    $args = [];
    foreach ($params as $param) {
        $name = $param->getName();
        if ($param->isOptional()) {
            isset($arguments[$name]) && $args[] = $arguments[$name];
            continue;
        }

        try {
            $args[] = $container->get($param->getClass()->getName());
        } catch (\Exception $e) {
            if (!isset($arguments[$name])) {
                throw new \BadMethodCallException('Can not get argument ' . $name . ' for ' . $class . '::' . $method);
            }
            $args[] = $arguments[$name];
        }
    }

    return \call_user_func_array([$class, $method], $args);
}

function required()
{
    return (object) ['__blog_hub_required__' => true];
}

function is_required($value)
{
    return is_object($value) && property_exists($value, '__blog_hub_required__');
}

function class_basename($class)
{
    $class = \is_object($class) ? \get_class($class) : $class;

    return basename(str_replace('\\', '/', $class));
}

function str_snake($value, $delimiter = '_')
{
    $value = preg_replace('/\s+/u', '', ucwords($value));

    return strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
}