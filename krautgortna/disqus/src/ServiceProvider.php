<?php

namespace Krautgortna\Disqus;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Tags\DisqusTags::class,
    ];

    public function bootAddon()
    {
    }
}
