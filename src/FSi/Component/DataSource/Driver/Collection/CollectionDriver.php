<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Collection;

use Doctrine\Common\Collections\Criteria;
use FSi\Component\DataSource\Driver\DriverAbstract;
use FSi\Component\DataSource\Driver\Collection\Exception\CollectionDriverException;

class CollectionDriver extends DriverAbstract
{
    private $collection;

    /**
     * Criteria available during preGetResult event.
     *
     * @var \Doctrine\Common\Collections\Criteria
     */
    private $currentCriteria;

    /**
     * @param array $extensions
     * @param array $collection
     */
    public function __construct(array $extensions, array $collection)
    {
        parent::__construct($extensions);
        $this->collection = $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'collection';
    }

    protected function initResult()
    {
        $this->currentCriteria = new Criteria();
    }

    /**
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \FSi\Component\DataSource\Driver\Collection\CollectionResult
     * @throws \FSi\Component\DataSource\Driver\Collection\Exception\CollectionDriverException
     */
    protected function buildResult($fields, $first, $max)
    {
        foreach ($fields as $field) {
            if (!$field instanceof CollectionFieldInterface) {
                throw new CollectionDriverException(sprintf('All fields must be instances of FSi\Component\DataSource\Driver\Collection\CollectionFieldInterface.'));
            }

            $field->buildCriteria($this->currentCriteria);
        }

        if ($max > 0) {
            $this->currentCriteria->setMaxResults($max);
            $this->currentCriteria->setFirstResult($first);
        }

        return new CollectionResult($this->collection, $this->currentCriteria);
    }

    /**
     * Returns criteria.
     *
     * If criteria is set to null (so when getResult method is NOT executed at the moment) exception is throwed.
     *
     * @throws Exception\CollectionDriverException
     * @return \Doctrine\Common\Collections\Criteria
     */
    public function getCriteria()
    {
        if (!isset($this->currentCriteria)) {
            throw new CollectionDriverException('Criteria is accessible only during preGetResult event.');
        }

        return $this->currentCriteria;
    }
}
