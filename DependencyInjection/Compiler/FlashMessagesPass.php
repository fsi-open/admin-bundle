<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminBundle\Message\FlashMessages;
use FSi\Bundle\AdminBundle\Message\RequestStackFlashMessages;
use FSi\Bundle\AdminBundle\Message\SessionFlashMessages;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\RequestStack;

final class FlashMessagesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definitionClass = true === method_exists(RequestStack::class, 'getSession')
            ? RequestStackFlashMessages::class
            : SessionFlashMessages::class
        ;

        $definition = new Definition($definitionClass);
        $definition->setArgument('$prefix', 'fsi_admin');
        $definition->setAutowired(true);
        $definition->setLazy(true);

        $container->addDefinitions([FlashMessages::class => $definition]);
    }
}
