<?php

namespace CasaCafe\Unit\Library\Mapper\Types;

class TypeConverterManager
{
    private $typeConverterMap = [];
    public function __construct(array $extraTypeConverters = [])
    {
        $internalTypeConverters = [
            'int' => new IntegerTypeConverter(),
            'bool' => new BooleanTypeConverter(),
            'boolean' => new BooleanTypeConverter(),
            'float' => new FloatTypeConverter(),
            'string' => new StringTypeConverter()
        ];

        $this->typeConverterMap = array_merge($internalTypeConverters, $extraTypeConverters);
    }

    public function getConverterFromType(string $type) : TypeConverterInterface
    {
        $converter = $this->typeConverterMap[$type] ??  $this->typeConverterMap['string'];
        return $converter;
    }
}
