<?php

use Revonia\BlogHub\Services\Cnblogs;
use Revonia\BlogHub\Services\MarkdownHtml;

/** @var $hub \Revonia\BlogHub\Hub */

$hub->addService('markdown-html', MarkdownHtml::class);
$hub->addService('cnblogs', Cnblogs::class);