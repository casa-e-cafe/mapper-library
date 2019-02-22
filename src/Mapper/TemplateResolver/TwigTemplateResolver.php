<?php

namespace CasaCafe\Library\Mapper\TemplateResolver;

use \Twig_Loader_Array as TemplateLoader;
use \Twig_Environment as TemplateEnv;

class TwigTemplateResolver implements TemplateResolverInterface
{
    /**
     * @param string $template
     * @param array $context
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function resolve(string $template, array $context)
    {
        $loader = new TemplateLoader(['template' => $template]);
        $twig = new TemplateEnv($loader);
        $resolvedTemplate = $twig->render('template', $context);
        return $resolvedTemplate;
    }
}
