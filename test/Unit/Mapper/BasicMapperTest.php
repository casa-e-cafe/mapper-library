<?php


namespace CasaCafe\Unit\Library\Mapper;

use CasaCafe\Library\Mapper\BasicMapper;
use CasaCafe\Library\Mapper\TemplateResolver\TwigTemplateResolver;
use CasaCafe\Library\Mapper\Types\TypeConverterManager;
use PHPUnit\Framework\TestCase;

class BasicMapperTest extends TestCase
{
    public function testMapOneDimensionArrayShouldExecuteWithSuccess()
    {
        $config = [
            'keyA' => 'valueA',
            'keyB' => '{{ someKey }}'
        ];
        $context = [
            'someKey' => 'valueB',
            'anotherKey' => 'WRONG'
        ];

        $typeConverterManager = new TypeConverterManager();
        $resolver = new TwigTemplateResolver();
        $mapper = new BasicMapper($resolver, $typeConverterManager);
        $mappedConfig = $mapper->mapConfigArray($config, $context);

        $expectedConfig = [
            'keyA' => 'valueA',
            'keyB' => 'valueB',
        ];

        $this->assertEquals($expectedConfig, $mappedConfig);
    }
    public function testMapMultiDimensionArrayShouldExecuteWithSuccess()
    {
        $config = [
            'keyA' => 'valueA',
            'keyB' => [
                'keyB1' => '{{ someKey }}',
                'keyB2' => 'valueB2'
            ]
        ];
        $context = [
            'someKey' => 'valueB1',
            'anotherKey' => 'WRONG'
        ];

        $typeConverterManager = new TypeConverterManager();
        $resolver = new TwigTemplateResolver();
        $mapper = new BasicMapper($resolver, $typeConverterManager);
        $mappedConfig = $mapper->mapConfigArray($config, $context);

        $expectedConfig = [
            'keyA' => 'valueA',
            'keyB' => ['keyB1' => 'valueB1', 'keyB2' => 'valueB2'],
        ];

        $this->assertEquals($expectedConfig, $mappedConfig);
    }

    public function testTypeMappingExample()
    {
        $configArray =[
            'int_type(int)' => '1',
            'float_type(float)' => '2.25',
            'bool_type(bool)' => 'false',
            'boolean_type(boolean)' => '  true '
        ];

        $typeConverterManager = new TypeConverterManager();
        $resolver = new TwigTemplateResolver();
        $mapper = new BasicMapper($resolver, $typeConverterManager);

        $mappedConfig = $mapper->mapConfigArray($configArray, []);
        $expectedEntity = [
            'int_type' => 1,
            'float_type' => 2.25,
            'bool_type' => false,
            'boolean_type' => true
        ];
        $this->assertEquals($expectedEntity, $mappedConfig);
        $this->assertInternalType('int', $mappedConfig['int_type']);
        $this->assertInternalType('float', $mappedConfig['float_type']);
        $this->assertInternalType('boolean', $mappedConfig['bool_type']);
        $this->assertInternalType('boolean', $mappedConfig['boolean_type']);
    }

    public function testTypeMutiDimensionMappingShoudMap()
    {
        $configArray =[
            'some_key' => [
                'int_type(int)' => '1',
                'float_type(float)' => '2.25',
            ],
            'another_key' => 'value',
            'another_yet_key' => [
                'bool_type(bool)' => 'false',
                'boolean_type(boolean)' => '  true '
            ]
        ];

        $typeConverterManager = new TypeConverterManager();
        $resolver = new TwigTemplateResolver();
        $mapper = new BasicMapper($resolver, $typeConverterManager);

        $mappedConfig = $mapper->mapConfigArray($configArray, []);
        $expectedEntity = [
            'some_key'  => [
                'int_type' => 1,
                'float_type' => 2.25,
            ],
            'another_key' => 'value',
            'another_yet_key' => [
                'bool_type' => false,
                'boolean_type' => true
            ]
        ];
        $this->assertEquals($expectedEntity, $mappedConfig);
        $this->assertInternalType('int', $mappedConfig['some_key']['int_type']);
        $this->assertInternalType('float', $mappedConfig['some_key']['float_type']);
        $this->assertInternalType('boolean', $mappedConfig['another_yet_key']['bool_type']);
        $this->assertInternalType('boolean', $mappedConfig['another_yet_key']['boolean_type']);
    }
}
