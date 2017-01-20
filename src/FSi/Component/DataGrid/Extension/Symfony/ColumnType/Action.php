<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated This class is deprecated since version 1.2. Please use fsi/datagrid-bundle and its
 * FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action
 */
class Action extends ColumnAbstractType
{
    /**
     * Symfony Router to generate urls.
     *
     * @var \Symfony\Component\Routing\Router;
     */
    protected $router;

    /**
     * Service container used to access current request.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected $actionOptionsResolver;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $container->get('router');
        $this->actionOptionsResolver = new OptionsResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'action';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        $return = array();
        $actions = $this->getOption('actions');

        foreach ($actions as $name => $options) {
            $options = $this->actionOptionsResolver->resolve((array) $options);
            $return[$name] = array();
            $parameters = array();
            $urlAttributes = $options['url_attr'];
            $content = $options['content'];

            if (isset($options['parameters_field_mapping'])) {
                foreach ($options['parameters_field_mapping'] as $parameterName => $mappingField) {
                    if ($mappingField instanceof \Closure) {
                        $parameters[$parameterName] = $mappingField($value, $this->getIndex());
                    } else {
                        $parameters[$parameterName] = $value[$mappingField];
                    }
                }
            }

            if (isset($options['additional_parameters'])) {
                foreach ($options['additional_parameters'] as $parameterValueName => $parameterValue) {
                    $parameters[$parameterValueName] = $parameterValue;
                }
            }

            if ($options['redirect_uri'] !== false) {
                if (is_string($options['redirect_uri'])) {
                    $parameters['redirect_uri'] = $options['redirect_uri'];
                }

                if ($options['redirect_uri'] === true) {
                    $parameters['redirect_uri'] = $this->container->get('request')->getRequestUri();
                }
            }

            if ($urlAttributes instanceof \Closure) {
                $urlAttributes = $urlAttributes($value, $this->getIndex());

                if (!is_array($urlAttributes)) {
                    throw new UnexpectedTypeException('url_attr option Clousure must return new array with url attributes.');
                }
            }

            $url = $this->router->generate($options['route_name'], $parameters, $options['absolute']);

            if (!isset($urlAttributes['href'])) {
                $urlAttributes['href'] = $url;
            }

            if (isset($content) && $content instanceof \Closure) {
                $content = (string) $content($value, $this->getIndex());
            }

            // $return[$name]['url'] is deprecated since 1.0 and will be removed in version 1.2
            $return[$name]['url'] = $url;
            $return[$name]['content']  = isset($content) ? $content : $name;
            $return[$name]['field_mapping_values'] = $value;
            $return[$name]['url_attr'] = $urlAttributes;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'actions' => array(),
        ));

        $this->getOptionsResolver()->setAllowedTypes('actions', 'array');

        $this->actionOptionsResolver->setDefaults(array(
            'redirect_uri' => true,
            'absolute' => false,
            'url_attr' => array(),
            'content' => null,
            'parameters_field_mapping' => array(),
            'additional_parameters' => array(),
        ));

        $this->actionOptionsResolver->setAllowedTypes('url_attr', array('array', 'Closure'));
        $this->actionOptionsResolver->setAllowedTypes('content', array('null', 'string', 'Closure'));

        $this->actionOptionsResolver->setRequired(array(
            'route_name',
        ));
    }

    /**
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     */
    public function getActionOptionsResolver()
    {
        return $this->actionOptionsResolver;
    }
}
