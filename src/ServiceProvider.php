<?php

namespace Krautgortna\Disqus;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Tags\DisqusTags::class,
    ];

    protected $scripts = [
        __DIR__.'/../resources/js/disqus.js'
    ];

    public function bootAddon()
    {
    }
}
