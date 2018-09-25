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
        $this->assertEquals($entity['name']['first'], 'Rafael');
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
}
