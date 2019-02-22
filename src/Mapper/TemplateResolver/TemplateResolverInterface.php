<?php

namespace CasaCafe\Library\Mapper\TemplateResolver;

interface TemplateResolverInterface
{
    public function resolve(string $template, array $context);
}
