<?php

namespace CasaCafe\Library\Mapper\Types;

interface TypeConverterInterface
{
    public function convert(string $stringValue = null);
}
