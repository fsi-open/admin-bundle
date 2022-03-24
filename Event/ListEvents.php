<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

final class ListEvents
{
    public const LIST_DATASOURCE_REQUEST_PRE_BIND = ListDataSourcePreBindEvent::class;

    public const LIST_DATASOURCE_REQUEST_POST_BIND = ListDataSourcePostBindEvent::class;

    public const LIST_DATAGRID_DATA_PRE_SET_DATA = ListDataGridPreSetDataEvent::class;

    public const LIST_DATAGRID_DATA_POST_SET_DATA = ListDataGridPostSetDataEvent::class;

    public const LIST_DATAGRID_REQUEST_PRE_SUBMIT = ListDataGridPreSubmitRequestEvent::class;

    public const LIST_DATAGRID_REQUEST_POST_SUBMIT = ListDataGridPostSubmitRequestEvent::class;

    public const LIST_RESPONSE_PRE_RENDER = ListResponsePreRenderEvent::class;
}
