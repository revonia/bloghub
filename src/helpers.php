<?php

namespace Revonia\BlogHub;

function env($name, $default = null)
{
    return getenv($name) === null ? $default : getenv($name);
}