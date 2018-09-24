<?php

namespace CasaCafe\Library\Mapper;

use \Twig_Loader_Array as TemplateLoader;
use \Twig_Environment as TemplateEnv;

class ConfigResolver
{
    private $context;
    public function __construct(array $context)
    {
        $this->context = $context;
    }

    public function resolve(string $config)
    {
        $loader = new TemplateLoader(['template' => $config]);
        $twig = new TemplateEnv($loader);
        $resolvedTemplate = $twig->render('template', $this->context);

        return $resolvedTemplate;
    }
}
