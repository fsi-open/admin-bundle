<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\DataSource;

use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\Entity\News;

class NewsDataSourceBuilder
{
    /**
     * @param DataSourceInterface<News> $datasource
     * @return DataSourceInterface<News>
     */
    public static function buildNewsDataSource(DataSourceInterface $datasource): DataSourceInterface
    {
        $datasource->addField('title', 'text', [
            'comparison' => 'like',
            'sortable' => false,
            'form_options' => [
                'label' => 'admin.news.list.title',
            ]
        ]);

        $datasource->addField('created_at', 'date', [
            'comparison' => 'between',
            'field' => 'createdAt',
            'sortable' => true,
            'form_from_options' => [
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_from',
            ],
            'form_to_options' => [
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_to',
            ]
        ]);

        $datasource->addField('visible', 'boolean', [
            'comparison' => 'eq',
            'sortable' => false,
            'form_options' => [
                'label' => 'admin.news.list.visible',
            ]
        ]);

        $datasource->addField('creator_email', 'text', [
            'comparison' => 'like',
            'field' => 'creatorEmail',
            'sortable' => true,
            'form_options' => [
                'label' => 'admin.news.list.creator_email',
            ]
        ]);

        $datasource->setMaxResults(10);

        return $datasource;
    }
}
