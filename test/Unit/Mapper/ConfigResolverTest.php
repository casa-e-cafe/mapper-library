<?php

namespace CasaCafe\Unit\Library\Mapper;

use CasaCafe\Library\Mapper\ConfigResolver;
use PHPUnit\Framework\TestCase;

class ConfigResolverTest extends TestCase
{
    public function testMapperOnlyStrings()
    {
        $emptyContext = [];
        $resolver = new ConfigResolver($emptyContext);
        $mappedValue = $resolver->resolve('Oi');

        $this->assertEquals($mappedValue, 'Oi');
    }

    public function testMapperStringReplacement()
    {
        $context = ['nome' => 'Rafael'];
        $resolver = new ConfigResolver($context);
        $mappedValue = $resolver->resolve('Oi {{ nome }}');

        $this->assertEquals($mappedValue, 'Oi Rafael');
    }
}
