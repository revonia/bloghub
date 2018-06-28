<?php

namespace Revonia\BlogHub\Transformers;

use Michelf\MarkdownExtra;
use Revonia\BlogHub\Interfaces\Transformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HtmlTransformer implements Transformer
{
    public function transform($data)
    {
        return MarkdownExtra::defaultTransform($data);
    }

    public static function boot(ContainerBuilder $container)
    {
        $container->autowire(MarkdownExtra::class)
            ->setPublic(true)
            ->setShared(false);
    }
}