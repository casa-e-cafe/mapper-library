<?php


namespace CasaCafe\Library\Mapper\Types;

class StringTypeConverter implements TypeConverterInterface
{
    public function convert(string $stringValue = null)
    {
        return $stringValue;
    }

}
