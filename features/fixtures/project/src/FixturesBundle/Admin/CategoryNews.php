<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use Doctrine\ORM\EntityRepository;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DependentCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\DataGrid\NewsDataGridBuilder;
use FSi\FixturesBundle\DataSource\NewsDataSourceBuilder;
use FSi\FixturesBundle\Entity;
use FSi\FixturesBundle\Form\NewsType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @template-extends DependentCRUDElement<Entity\News, Entity\News, Entity\Category>
 */
class CategoryNews extends DependentCRUDElement
{
    public function getId(): string
    {
        return 'category_news';
    }

    public function getParentId(): string
    {
        return 'category';
    }

    public function getClassName(): string
    {
        return Entity\News::class;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        $datagrid = $factory->createDataGrid($this->getId());

        NewsDataGridBuilder::buildNewsDataGrid($datagrid);

        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.news.list.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'route_name' => 'fsi_admin_form',
                    'additional_parameters' => ['element' => $datagrid->getName()],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
                'display' => [
                    'element' => CategoryNewsDisplay::ID
                ]
            ]
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        /** @var EntityRepository<Entity\News> $repository */
        $repository = $this->getRepository();

        $queryBuilder = $repository->createQueryBuilder('n');

        if ($this->getParentObject()) {
            $queryBuilder->where(':category MEMBER OF n.categories')
                ->setParameter('category', $this->getParentObject());
        }

        $datasource = $factory->createDataSource(
            'doctrine-orm',
            ['qb' => $queryBuilder],
            $this->getId()
        );

        NewsDataSourceBuilder::buildNewsDataSource($datasource);

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $category = $this->getParentObject();

        if (null === $data) {
            $data = new Entity\News();

            if (null !== $category) {
                $data->addCategory($category);
            }
        }

        return $factory->createNamed('news', NewsType::class, $data);
    }
}
