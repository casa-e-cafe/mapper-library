<?php

namespace CasaCafe\Unit\Library\Mapper\Types;

class FloatTypeConverter implements TypeConverterInterface
{
    public function convert(string $stringValue = null)
    {
        $convertedValue = null;

        if (!is_null($stringValue)) {
            $convertedValue = (float) $stringValue;
        }
        return $convertedValue;
    }
}
