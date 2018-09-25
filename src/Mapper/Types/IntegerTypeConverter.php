<?php

namespace CasaCafe\Library\Mapper\Types;

class IntegerTypeConverter implements TypeConverterInterface
{
    public function convert(string $stringValue = null)
    {
        $convertedValue = null;

        if (!is_null($stringValue)) {
            $convertedValue = (int) $stringValue;
        }
        return $convertedValue;
    }
}
