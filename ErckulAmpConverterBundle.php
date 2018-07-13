<?php

namespace Erckul\AmpConverterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Erckul\AmpConverterBundle\DependencyInjection\Compiler\TagConverterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErckulAmpConverterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TagConverterCompilerPass());
    }
}
