<?php

namespace LodService\Identifier;

class LocLdsNamesIdentifier
extends UriIdentifier
{
    protected $name = 'lcnaf';
    protected $prefix = 'lcauth';
    protected $baseUri = 'http://id.loc.gov/authorities/names/';
}
