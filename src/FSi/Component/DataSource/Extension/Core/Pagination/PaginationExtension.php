<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Core\Pagination;

use FSi\Component\DataSource\DataSourceAbstractExtension;

/**
 * Pagination extension adds to view some options helpfull during view rendering.
 */
class PaginationExtension extends DataSourceAbstractExtension
{
    /**
     * Key for page info.
     */
    const PARAMETER_PAGE = 'page';

    /**
     * Key for results per page.
     */
    const PARAMETER_MAX_RESULTS = 'max_results';

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return array(
            new EventSubscriber\Events(),
        );
    }
}
