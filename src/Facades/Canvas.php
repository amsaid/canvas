<?php

namespace Canvas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Canvas\Canvas
 */
class Canvas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Canvas\Canvas::class;
    }
}
