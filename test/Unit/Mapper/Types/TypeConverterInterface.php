<?php

namespace CasaCafe\Unit\Library\Mapper\Types;

interface TypeConverterInterface
{
    public function convert(string $stringValue = null);
}
