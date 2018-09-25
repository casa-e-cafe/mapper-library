<?php

namespace  CasaCafe\Library\Mapper;

use Adbar\Dot;
use CasaCafe\Library\Mapper\Types\TypeConverterManager;

class Mapper
{
    private $configResolver;
    private $entity;
    private $typeConverterManager;

    public function __construct(array $context, array $entity = [])
    {
        $this->entity = new Dot($entity);
        $this->configResolver = new ConfigResolver($context);
        $this->typeConverterManager = new TypeConverterManager();
    }

    public function processConfigArray(array $configArray, $currentPath = '')
    {
        foreach ($configArray as $targetPath => $config) {
            $path = empty($currentPath) ? $targetPath : $currentPath . '.' . $targetPath;
            if (is_array($config)) {
                $this->processConfigArray($config, $path);
                continue;
            }
            $this->processConfig($config, $path);
        }
    }

    public function processConfig(string $config, string $targetPath)
    {
        $configValue = $this->configResolver->resolve($config);
        $target = $this->getTarget($targetPath);
        $convertedValue = $this->convertValueToType($configValue, $target->type);

        $this->entity->set($target->path, $convertedValue);
    }

    private function getTarget(string $targetPath) : \stdClass
    {
        $matches = [];
        $typeRegex = '/(\S+)\s*\((int|float|bool|boolean|string)\)/';
        preg_match($typeRegex, $targetPath, $matches);

        $target = new \stdClass();
        $target->path = $matches[1] ?? $targetPath;
        $target->type = $matches[2] ?? 'string';

        return $target;
    }

    private function convertValueToType(string $configValue, string $type)
    {
        $converter = $this->typeConverterManager->getConverterFromType($type);
        $configValue = $configValue !== '' ? $configValue : null;
        $convertedValue = $converter->convert($configValue);
        return $convertedValue;
    }

    public function getEntity()
    {
        return $this->entity->all();
    }
}
