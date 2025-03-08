<?php

namespace LodService;

use LodService\Provider\Provider;

class LodService
{
    protected $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function __call($method, $args)
    {
        // TODO: check if provider supports this
        return call_user_func_array([ $this->provider, $method ], $args);
    }
}
