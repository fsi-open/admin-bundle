<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Structure;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractDoctrineAdminElement extends AbstractAdminElement implements DoctrineAdminElementInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    /**
     * @var \FSi\Component\DataIndexer\DoctrineDataIndexer
     */
    protected $indexer;

    /**
     * {@inheritdoc}
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * Return repository name bound to this admin object.
     * Repository will be used to create/updated/delete entities.
     *
     * @return string
     */
    abstract public function getClassName();

    /**
     * This function should be used inside of admin objects to retrieve ObjectManager
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function getObjectManager()
    {
        if (!isset($this->registry)) {
            throw new RuntimeException(sprintf('ManagerRegistry is missing in "%s"', $this->getName()));
        }

        if (!isset($this->om)) {
            $this->om = $this->registry->getManagerForClass($this->getClassName());
        }

        if (is_null($this->om)) {
            throw new RuntimeException(sprintf('Registry manager does\'t have manager for class "%s".', $this->getClassName()));
        }

        return $this->om;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        $om = $this->getObjectManager();

        return $om->getRepository($this->getClassName());
    }

    /**
     * This method should be used inside of admin objects to retrieve DoctrineDataIndexer.
     *
     * @return \FSi\Component\DataIndexer\DoctrineDataIndexer
     */
    public function getDataIndexer()
    {
        if (!isset($this->indexer)) {
            $this->indexer = new DoctrineDataIndexer($this->registry, $this->getClassName());
        }

        return $this->indexer;
    }

    /**
     * {@inheritdoc}
     */
    public function save($entity)
    {
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function saveGrid()
    {
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($entity)
    {
        $this->getObjectManager()->remove($entity);
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDataGridActionColumnOptions(DataGridInterface $datagrid)
    {
        $options = array('actions' => array());
        $classMetadata = $this->getObjectManager()->getClassMetadata($this->getClassName());
        $options['field_mapping'] = $classMetadata->getIdentifierFieldNames();
        $options['translation_domain'] = 'FSiAdminBundle';

        if ($datagrid->hasColumnType('gedmo_tree')) {
            $options['actions']['moveup'] = array(
                'route_name' => 'fsi_admin_tree_move_up',
                'parameters_field_mapping' => array(
                    'id' => function($values, $index) {
                        return $index;
                    }
                ),
                'additional_parameters' => array(
                    'element' => $this->getId(),
                    'number' => 1
                )
            );
            $options['actions']['movedown'] = array(
                'route_name' => 'fsi_admin_tree_move_down',
                'parameters_field_mapping' => array(
                    'id' => function($values, $index) {
                        return $index;
                    }
                ),
                'additional_parameters' => array(
                    'element' => $this->getId(),
                    'number' => 1
                )
            );
        }

        if ($this->hasEditForm()) {
            $options['actions']['edit'] = array(
                'route_name' => 'fsi_admin_crud_edit',
                'parameters_field_mapping' => array(
                    'id' => function($values, $index) {
                        return $index;
                    }
                ),
                'additional_parameters' => array(
                    'element' => $this->getId()
                )
            );
        }

        $options['actions']['delete'] = array(
            'route_name' => 'fsi_admin_crud_delete',
            'parameters_field_mapping' => array(
                'id' => function($values, $index) {
                    return $index;
                }
            ),
            'additional_parameters' => array(
                'element' => $this->getId()
            )
        );

        return $options;
    }
}