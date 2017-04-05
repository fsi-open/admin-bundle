<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DependentCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\FixturesBundle\DataGrid\NewsDataGridBuilder;
use FSi\FixturesBundle\DataSource\NewsDataSourceBuilder;
use FSi\FixturesBundle\Form\NewsType;
use Symfony\Component\Form\FormFactoryInterface;

class CategoryNews extends DependentCRUDElement
{
    public function getId()
    {
        return 'category_news';
    }

    public function getParentId()
    {
        return 'category';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\News';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('category_news');

        NewsDataGridBuilder::buildNewsDataGrid($datagrid);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $queryBuilder = $this->getRepository()
            ->createQueryBuilder('n');

        if ($this->getParentObject()) {
            $queryBuilder->where(':category MEMBER OF n.categories')
                ->setParameter('category', $this->getParentObject());
        }

        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', ['qb' => $queryBuilder], 'category_news');

        NewsDataSourceBuilder::buildNewsDataSource($datasource);

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        if ($data === null) {
            $data = new \FSi\FixturesBundle\Entity\News();
            $data->addCategory($this->getParentObject());
        }

        return $factory->createNamed('news', new NewsType(), $data);
    }
}
