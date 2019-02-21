<?php

namespace CasaCafe\Library\Mapper;

use CasaCafe\Library\Mapper\Types\TypeConverterManager;

class SimpleMapper
{
    protected $configResolver;
    private $entity;
    protected $typeConverterManager;


    public function __construct(array $context, array $entity = [])
    {
        $this->entity = $entity;
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

        $this->setEntityPath($target->path, $convertedValue);
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

    protected function setEntityPath($targetPath, $value)
    {
        $this->entity[$targetPath]  = $value;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
