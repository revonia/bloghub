<?php

namespace Revonia\BlogHub;

use Psr\Container\ContainerInterface;

class Hub
{
    private $blogServices = [];
    private $transformers = [];

    private $enabled = [];

    public function addBlogService($name, $class)
    {
        $this->blogServices[$name] = $class;
    }

    public function addTransformer($name, $class)
    {
        $this->transformers[$name] = $class;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function resolveBlogService(ContainerInterface $container, $name)
    {
        return $container->get($this->blogServices[$name]);
    }

    public function bootAll(Application $app, Env $env)
    {
        $env->setDefaults([
            'ENABLED_BLOG_SERVICES' => required(),
            'ENABLED_TRANSFORMERS' => 'raw',
        ]);

        $this->enabled = [
            'blogServices' => explode(',', $env['ENABLED_BLOG_SERVICES']),
            'transformers' => explode(',', $env['ENABLED_TRANSFORMERS']),
        ];

        foreach (['blogServices', 'transformers'] as $map) {
            foreach ($this->{$map} as $name => $class) {
                if (!in_array($name, $this->enabled[$map])) {
                    continue;
                }
                $app->getContainer()
                    ->autowire($class)
                    ->setShared(false)
                    ->setPublic(true);

                $boot = [$class, 'boot'];
                if (is_callable($boot)) {
                    call_method_with_injection($app->getContainer(), $class, 'boot');
                }
            }
        }
    }
}
