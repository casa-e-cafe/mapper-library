<?php

namespace  CasaCafe\Library\Mapper;

use Adbar\Dot;
use CasaCafe\Library\Mapper\Types\BooleanTypeConverter;

class Mapper extends SimpleMapper
{
    private $entity;
    public function __construct(array $context, array $entity = [])
    {
        parent::__construct($context, $entity);
        $this->entity = new Dot($entity);
    }

    public static function evaluateBoolExpression(string $expression, $context = [])
    {
        $resolver = new ConfigResolver($context);
        $config = sprintf('{{ %s ? "true" : "false" }}', $expression);
        $stringResult = $resolver->resolve($config);

        $boolConverter = new BooleanTypeConverter();
        return $boolConverter->convert($stringResult);
    }

    protected function setEntityPath($targetPath, $value)
    {
        $this->entity->set($targetPath, $value);
    }

    public function getEntity()
    {
        $this->entity->all();
    }
}
