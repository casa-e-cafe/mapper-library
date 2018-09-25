<?php

namespace CasaCafe\Unit\Library\Mapper\Types;

class BooleanTypeConverter implements TypeConverterInterface
{
    public function convert(string $stringValue = null)
    {
        $convertedValue = null;

        if (!is_null($stringValue)) {
            $convertedValue = (trim($stringValue) === 'true');
        }
        return $convertedValue;
    }
}
