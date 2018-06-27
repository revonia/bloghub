<?php


namespace Revonia\BlogHub\Transformers;


use Revonia\BlogHub\Interfaces\Transformer;

class RawTransformer implements Transformer
{

    public function transform($data)
    {
        return $data;
    }
}