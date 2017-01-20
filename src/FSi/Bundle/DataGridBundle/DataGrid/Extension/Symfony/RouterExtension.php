<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Bundle\DataGridBundle\Datagrid\Extension\Symfony\EventSubscriber;
use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RouterExtension extends DataGridAbstractExtension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     */
    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypes()
    {
        return array(
            new ColumnType\Action($this->router, $this->requestStack),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function loadSubscribers()
    {
        return array(
            new EventSubscriber\BindRequest(),
        );
    }
}
