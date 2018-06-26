<?php


namespace Revonia\BlogHub;


class Hub
{
    private $serviceMap = [];

    public function addService($name, $class)
    {
        $this->serviceMap[$name] = $class;
    }

    public function bootServices()
    {
        foreach ($this->serviceMap as $service => $class) {
            $boot = [$class, 'boot'];
            if (is_callable($boot)) {
                $boot();
            }
        }
    }
}
