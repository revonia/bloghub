<?php

use Revonia\BlogHub\BlogServices\CnblogsBlogService;
use Revonia\BlogHub\BlogServices\FileBlogService;
use Revonia\BlogHub\Transformers\HtmlTransformer;
use Revonia\BlogHub\Transformers\RawTransformer;

/** @var $hub \Revonia\BlogHub\Hub */

$hub->addBlogService('file', FileBlogService::class);
$hub->addBlogService('cnblogs', CnblogsBlogService::class);

$hub->addTransformer('html', HtmlTransformer::class);
$hub->addTransformer('raw', RawTransformer::class);