<?php

namespace Cruxinator\JitLoading\Facades;

use Illuminate\Support\Facades\Facade;
use Cruxinator\JitLoading\Facades\Contracts\JitLoadingContract;

/**
 * @see \Cruxinator\JitLoading\JitLoading
 */
class JitLoading extends Facade
{
    protected static function getFacadeAccessor()
    {
        return JitLoadingContract::class;
    }
}
