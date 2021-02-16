<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Request\ParamConverter;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FSi\Bundle\AdminBundle\Admin\Element;

class AdminElementParamConverter implements ParamConverterInterface
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $param = $configuration->getName();
        $id = $request->attributes->get($param, '');

        if (false === $this->manager->hasElement($id)) {
            throw new NotFoundHttpException(sprintf('Admin element with id %s does not exist.', $id));
        }

        $request->attributes->set($param, $this->manager->getElement($id));

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        if (!$configuration->getClass()) {
            return false;
        }

        if (
            false === class_exists($configuration->getClass())
            && false === interface_exists($configuration->getClass())
        ) {
            return false;
        }

        $implements = class_implements($configuration->getClass());

        return in_array(Element::class, $implements, true);
    }
}
