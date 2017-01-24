<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use FSi\Component\DataSource\Driver\Doctrine\DBAL\Exception\DoctrineDriverException;
use FSi\Component\DataSource\Driver\DriverAbstract;

final class DoctrineDriver extends DriverAbstract
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var QueryBuilder
     */
    private $currentQueryBuilder;

    /**
     * @var string
     */
    private $countField;

    /**
     * @var string
     */
    private $indexBy;

    /**
     * @param array $extensions
     * @param QueryBuilder $queryBuilder
     * @param string $countField
     * @param string $indexBy
     */
    public function __construct(array $extensions, QueryBuilder $queryBuilder, $countField = 'id', $indexBy = 'id')
    {
        parent::__construct($extensions);

        $this->queryBuilder = $queryBuilder;
        $this->countField = $countField;
        $this->indexBy = $indexBy;
    }

    public function getType()
    {
        return 'doctrine-dbal';
    }

    protected function initResult()
    {
        $this->currentQueryBuilder = clone $this->queryBuilder;
    }

    /**
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \Countable, \IteratorAggregate
     */
    protected function buildResult($fields, $first, $max)
    {
        foreach ($fields as $field) {
            if (!$field instanceof DoctrineField) {
                throw new \RuntimeException('doctrine-dbal driver require DoctrineField instances.');
            }

            $field->buildQuery($this->currentQueryBuilder);
        }

        $connection = $this->currentQueryBuilder->getConnection();

        $countQueryBuilder = clone $this->currentQueryBuilder;

        $countQueryBuilder->select(sprintf('COUNT(%s)', $this->countField));
        $countQueryBuilder->resetQueryPart('orderBy');

        if ($max > 0) {
            $this->currentQueryBuilder->setMaxResults($max);
            $this->currentQueryBuilder->setFirstResult($first);
        }

        $result = $connection->fetchAll($this->currentQueryBuilder->getSQL(), $this->currentQueryBuilder->getParameters());
        $indexedResults = [];
        foreach ($result as $sigleRow) {
            $indexedResults[$sigleRow[$this->indexBy]] = $sigleRow;
        }

        $count = $connection->fetchColumn($countQueryBuilder->getSQL(), $this->currentQueryBuilder->getParameters());

        return new Result($indexedResults, $count);
    }

    /**
     * @return QueryBuilder
     * @throws DoctrineDriverException
     */
    public function getQueryBuilder()
    {
        if (!isset($this->currentQueryBuilder)) {
            throw new DoctrineDriverException('Query Builder is accessible only during preGetResult event.');
        }

        return $this->currentQueryBuilder;
    }
}
