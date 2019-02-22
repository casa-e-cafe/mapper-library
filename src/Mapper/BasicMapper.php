<?php


namespace CasaCafe\Library\Mapper;

use CasaCafe\Library\Mapper\Item\ItemKeySpec;
use CasaCafe\Library\Mapper\TemplateResolver\TemplateResolverInterface;
use CasaCafe\Library\Mapper\Types\TypeConverterManager;

class BasicMapper
{
    private $resolver;
    private $typeConverterManager;
    public function __construct(TemplateResolverInterface $resolver, TypeConverterManager $typeConverterManager)
    {
        $this->resolver = $resolver;
        $this->typeConverterManager = $typeConverterManager;
    }

    public function mapConfigArray(array $configArray, $context) : array
    {
        foreach ($configArray as $configKey => $configItem) {
            if (is_array($configItem)) {
                $resolvedItem = $this->mapConfigArray($configItem, $context);
            } else {
                $itemKey = new ItemKeySpec($configKey);
                unset($configArray[$configKey]);
                $rawResolvedItem = $this->resolver->resolve($configItem, $context);
                $resolvedItem = $this->typeConverterManager->convertValueToType($rawResolvedItem, $itemKey->getType());
                $configKey = $itemKey->getKey();
            }
            $configArray[$configKey] = $resolvedItem;
        }
        return $configArray;
    }
}
