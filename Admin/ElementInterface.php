<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface ElementInterface
{
    /**
     * ID will appear in routes:
     * - http://example.com/admin/{name}/list
     * - http://example.com/admin/{name}/edit
     * etc.
     *
     * @return string
     */
    public function getId();

    /**
     * Return route name that will be used to generate element url in menu.
     *
     * @return string
     */
    public function getRoute();

    /**
     * Return array of parameters.
     * Element id always exists in this array under element key.
     *
     * @return mixed
     */
    public function getRouteParameters();

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     * @return mixed
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * Get option by name.
     *
     * @param string $name
     * @return mixed
     * @throws \FSi\Bundle\AdminBundle\Exception\MissingOptionException
     */
    public function getOption($name);

    /**
     * Get options array.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Check if option exists.
     *
     * @param string $name
     * @return boolean
     */
    public function hasOption($name);
}
