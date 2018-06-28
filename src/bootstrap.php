<?php

use Revonia\BlogHub\BlogServices\CnblogsBlogService;
use Revonia\BlogHub\BlogServices\FileBlogService;
use Revonia\BlogHub\Transformers\HtmlTransformer;
use Revonia\BlogHub\Transformers\RawTransformer;
use Symfony\Component\Filesystem\Filesystem;


$container->autowire(Filesystem::class)
    ->setPublic(true)
    ->setShared(false);

/** @var $hub \Revonia\BlogHub\Hub */

$hub->addBlogService('file', FileBlogService::class);
$hub->addBlogService('cnblogs', CnblogsBlogService::class);

$hub->addTransformer('html', HtmlTransformer::class);
$hub->addTransformer('raw', RawTransformer::class);