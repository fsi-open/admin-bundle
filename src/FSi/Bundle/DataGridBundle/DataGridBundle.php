<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FSi\Bundle\DataGridBundle\DependencyInjection\Compiler\DataGridPass;
use FSi\Bundle\DataGridBundle\DependencyInjection\Compiler\TemplatePathPass;
use FSi\Bundle\DataGridBundle\DependencyInjection\FSIDataGridExtension;

/**
 * FSiDataGridBundle.
 *
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class DataGridBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DataGridPass());
        $container->addCompilerPass(new TemplatePathPass());
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FSIDataGridExtension();
        }

        return $this->extension;
    }
}