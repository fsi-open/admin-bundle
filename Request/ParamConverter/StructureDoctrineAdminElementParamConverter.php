<?php

namespace FSi\Bundle\AdminBundle\Request\ParamConverter;

use FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface;
use FSi\Bundle\AdminBundle\Structure\GroupManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class StructureDoctrineAdminElementParamConverter implements ParamConverterInterface
{
    /**
     * @var StructureManagerInterface
     */
    private $manager;

    /**
     * @param GroupManagerInterface $manager
     */
    public function __construct(GroupManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @param ConfigurationInterface $configuration
     * @return bool
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $param = $configuration->getName();
        $element = $this->manager->findElementById($request->attributes->get($param, ''));

        if (!isset($element) || !$element instanceof AdminElementInterface) {
            throw new NotFoundHttpException();
        }

        $request->attributes->set($param, $element);

        return true;
    }

    /**
     * @param ConfigurationInterface $configuration
     * @return bool
     */
    public function supports(ConfigurationInterface $configuration)
    {
        return "FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface" === $configuration->getClass();
    }
}