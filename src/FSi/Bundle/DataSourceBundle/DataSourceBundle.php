<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FSi\Bundle\DataSourceBundle\DependencyInjection\Compiler\DataSourcePass;
use FSi\Bundle\DataSourceBundle\DependencyInjection\Compiler\TemplatePathPass;
use FSi\Bundle\DataSourceBundle\DependencyInjection\FSIDataSourceExtension;

/**
 * FSiDataSourceBundle.
 *
 * @author Lukasz Cybula <lukasz@fsi.pl>
 */
class DataSourceBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DataSourcePass());
        $container->addCompilerPass(new TemplatePathPass());
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FSIDataSourceExtension();
        }

        return $this->extension;
    }
}
