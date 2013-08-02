<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Structure;

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
     * etd.
     *
     * @return string
     */
    public function getId();

    /**
     * Name is a simple string that can be translated.
     *
     * @return string
     */
    public function getName();

    /**
     * Return route name that will be used to generate element url in menu..
     *
     * @return string
     */
    public function getBaseRouteName();

    /**
     * Return array of parameters.
     * Element id always exists in this array under element key
     *
     * @return mixed
     */
    public function getBaseRouteParameters();

    /**
     * Initialize options.
     *
     * @param array $options
     */
    public function initOptions($options = array());

    /**
     * @param OptionsResolverInterface $resolver
     * @return mixed
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * Get option by name.
     *
     * @param $name
     * @return mixed
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
     * @param $name
     * @return boolean
     */
    public function hasOption($name);
}