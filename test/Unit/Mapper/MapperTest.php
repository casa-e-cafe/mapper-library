<?php

namespace CasaCafe\Unit\Library\Mapper;

use CasaCafe\Library\Mapper\Mapper;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    public function testConfigShouldSetValueToTarget()
    {
        $entity = [];
        $context = ['lastName' => 'Girolineto', 'firstName' => 'Rafael'];
        $mapper = new Mapper($context, $entity);
        $entityPath = 'name.first';
        $config = '{{ firstName }}';

        $mapper->processConfig($config, $entityPath);
        $entity = $mapper->getEntity();
        $this->assertEquals('Rafael', $entity['name']['first']);
    }

    public function testConfigArrayShouldSetValueToTarget()
    {
        $entity = [];
        $context = ['lastName' => 'Girolineto', 'firstName' => 'Rafael'];
        $mapper = new Mapper($context, $entity);
        $config = ['name.first' => '{{ firstName }}', 'name.last' => '{{ lastName }}', 'type' => 'user'];

        $mapper->processConfigArray($config);
        $entity = $mapper->getEntity();

        $expectedArray = [
            'name' => [
                'first' => 'Rafael',
                'last' => 'Girolineto'
            ],
            'type' => 'user'
        ];
        $this->assertEquals($expectedArray, $entity);
    }

    public function testMetricMappingExample()
    {
        $configArray =[
            'entity_type' => 'user',
            'entity_id' => 'c_{{ hirer.id }}',
            'reference_type' => 'item',
            'reference_id' => '{{ item.id }}',
            'additional_info.use_date' => '{{ item.use_date }}'
        ];

        $context = [
            'hirer' => ['id' => 123543, 'name' => 'Sandy & Junior'],
            'item' => ['id' => 'cfba12d1s123', 'type'=> 'chat']
        ];

        $entity = [];
        $mapper = new Mapper($context, $entity);

        $mapper->processConfigArray($configArray);

        $expectedEntity = [
            'entity_type' => 'user',
            'entity_id' => 'c_123543',
            'reference_type' => 'item',
            'reference_id' => 'cfba12d1s123',
            'additional_info' => ['use_date' => null]
        ];
        $entity = $mapper->getEntity();
        $this->assertEquals($expectedEntity, $entity);
    }

    public function testTypeMappingExample()
    {
        $configArray =[
            'int_type(int)' => '1',
            'float_type(float)' => '2.25',
            'bool_type(bool)' => 'false',
            'boolean_type(boolean)' => '  true '
        ];

        $mapper = new Mapper([], []);

        $mapper->processConfigArray($configArray);

        $expectedEntity = [
            'int_type' => 1,
            'float_type' => 2.25,
            'bool_type' => false,
            'boolean_type' => true
        ];
        $entity = $mapper->getEntity();
        $this->assertEquals($expectedEntity, $entity);
        $this->assertInternalType('int', $entity['int_type']);
        $this->assertInternalType('float', $entity['float_type']);
        $this->assertInternalType('boolean', $entity['bool_type']);
        $this->assertInternalType('boolean', $entity['boolean_type']);
    }

    public function testTypeMappingWithSpace()
    {
        $configArray =[
            'int_type (int)' => '1',
            'boolean_type     (boolean)' => 'true'
        ];

        $mapper = new Mapper([], []);

        $mapper->processConfigArray($configArray);

        $expectedEntity = [
            'int_type' => 1,
            'boolean_type' => true
        ];
        $entity = $mapper->getEntity();
        $this->assertEquals($expectedEntity, $entity);
        $this->assertInternalType('int', $entity['int_type']);
        $this->assertInternalType('boolean', $entity['boolean_type']);
    }

    public function testTypeMappingNullWithTypes()
    {
        $configArray =[
            'int_type1 (int)' => '',
            'int_type2 (int)' => '0',
            'boolean_type1 (boolean)' => '',
            'boolean_type2 (boolean)' => 'false',
            'float_type1 (float)' => '',
            'float_type2 (float)' => '0.0',
            'string_type1 (string)' => '',
            'string_type2 (string)' => ' ',
        ];

        $mapper = new Mapper([], []);

        $mapper->processConfigArray($configArray);

        $expectedEntity = [
            'int_type1' => null,
            'int_type2' => 0,
            'boolean_type1' => null,
            'boolean_type2' => false,
            'float_type1' => null,
            'float_type2' => 0.0,
            'string_type1' => null,
            'string_type2' => ' ',
        ];
        $entity = $mapper->getEntity();
        $this->assertEquals($expectedEntity, $entity);
        $this->assertInternalType('int', $entity['int_type2']);
        $this->assertInternalType('boolean', $entity['boolean_type2']);
        $this->assertInternalType('float', $entity['float_type2']);
        $this->assertInternalType('string', $entity['string_type2']);
        $this->assertNull($entity['int_type1']);
        $this->assertNull($entity['boolean_type1']);
        $this->assertNull($entity['float_type1']);
        $this->assertNull($entity['string_type1']);
    }

    public function testResolveBoolExpressionShouldReturTrue()
    {
        $expression = 'var.value == 2';
        $context = ['var' => ['value' => 2]];
        $result = Mapper::evaluateBoolExpression($expression, $context);
        $this->assertTrue($result);
    }

    public function testResolveBoolExpressionShouldReturnFalse()
    {
        $expression = 'var.value == 2';
        $context = ['var' => ['value' => 1]];
        $result = Mapper::evaluateBoolExpression($expression, $context);
        $this->assertFalse($result);
    }
}
