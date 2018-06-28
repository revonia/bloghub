<?php

namespace Revonia\BlogHub;

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

    public function bootAll(Application $app)
    {
        $env = Env::select([
            'BLOG_HUB_ENABLED_BLOG_SERVICES' => required(),
            'BLOG_HUB_ENABLED_TRANSFORMERS' => '',
        ]);

        $this->enabled = [
            'blogServices' => explode(',', $env['BLOG_HUB_ENABLED_BLOG_SERVICES']),
            'transformers' => explode(',', $env['BLOG_HUB_ENABLED_TRANSFORMERS']),
        ];

        foreach (['blogServices', 'transformers'] as $map) {
            foreach ($this->{$map} as $name => $class) {
                if (!in_array($name, $this->enabled[$map])) {
                    continue;
                }
                $boot = [$class, 'boot'];
                if (is_callable($boot)) {
                    call_method_with_injection($app->getContainer(), $class, 'boot');
                }
            }
        }
    }
}
