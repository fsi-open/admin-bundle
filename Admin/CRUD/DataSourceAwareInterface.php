<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Component\DataSource\DataSourceFactoryInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface DataSourceAwareInterface
{
    /**
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory);
}