<?php

namespace  CasaCafe\Library\Mapper;

use Adbar\Dot;

class Mapper
{
    private $configResolver;
    private $entity;

    public function __construct(array $context, array $entity = [])
    {
        $this->entity = new Dot($entity);
        $this->configResolver = new ConfigResolver($context);
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
        $this->entity->set($targetPath, $configValue);
    }

    public function getEntity()
    {
        return $this->entity->all();
    }
}
