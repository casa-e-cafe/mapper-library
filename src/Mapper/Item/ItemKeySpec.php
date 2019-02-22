<?php


namespace CasaCafe\Library\Mapper\Item;

class ItemKeySpec
{
    private $key;
    private $type;

    public function __construct(string $typedItemKey)
    {
        $this->setupKeySpec($typedItemKey);
    }

    private function setupKeySpec(string $configKey)
    {
        $matches = [];
        $typeRegex = '/(\S+)\s*\((int|float|bool|boolean|string)\)/';
        preg_match($typeRegex, $configKey, $matches);

        $this->key = $matches[1] ?? $configKey;
        $this->type = $matches[2] ?? 'string';
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
