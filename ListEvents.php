<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle;

class ListEvents
{
    const LIST_DATASOURCE_REQUEST_PRE_BIND = 'list.datasource.request.pre_bind';

    const LIST_DATASOURCE_REQUEST_POST_BIND = 'list.datasource.request.post_bind';

    const LIST_DATAGRID_DATA_PRE_BIND = 'list.datagrid.data.pre_bind';

    const LIST_DATAGRID_DATA_POST_BIND = 'list.datagrid.data.post_bind';

    const LIST_DATAGRID_REQUEST_PRE_BIND = 'list.datagrid.request.pre_bind';

    const LIST_DATAGRID_REQUEST_POST_BIND = 'list.datagrid.request.post_bind';

    const LIST_RESPONSE_PRE_RENDER = 'list.response.pre_render';

}
