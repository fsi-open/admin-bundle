<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;

interface FormElement extends ElementInterface, FormAwareInterface
{
    /**
     * @param mixed $data
     * @return \Symfony\Component\Form\Form|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createForm($data = null);

    /**
     * This method should be used inside of admin objects to retrieve DataIndexerInterface.
     *
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer();

    /**
     * This method is called from FormController after form validation is passed in edit and create action.
     * Mostly this method should save updated object in database.
     *
     * @param mixed $object
     */
    public function save($object);

    /**
     * Return route name that will be used to redirect after successful form handling.
     *
     * @return string
     */
    public function getSuccessRoute();

    /**
     * Return array of parameters used with route returned from getSuccessRoute().
     *
     * @return mixed
     */
    public function getSuccessRouteParameters();
}
