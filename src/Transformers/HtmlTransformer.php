<?php

namespace Revonia\BlogHub\Transformers;

use Michelf\MarkdownExtra;
use Revonia\BlogHub\Interfaces\Transformer;

class HtmlTransformer implements Transformer
{
    public function transform($data)
    {
        return MarkdownExtra::defaultTransform($data);
    }
}