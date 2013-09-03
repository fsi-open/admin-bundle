<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
final class CRUDEvents
{
    const CRUD_LIST_CONTEXT_POST_CREATE = 'admin.crud.list.context.post_create';

    const CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND = 'admin.crud.list.datasource.request.pre_bind';

    const CRUD_LIST_DATASOURCE_REQUEST_POST_BIND = 'admin.crud.list.datasource.request.post_bind';

    const CRUD_LIST_DATAGRID_DATA_PRE_BIND = 'admin.crud.list.datagrid.data.pre_bind';

    const CRUD_LIST_DATAGRID_DATA_POST_BIND = 'admin.crud.list.datagrid.data.post_bind';

    const CRUD_LIST_DATAGRID_REQUEST_PRE_BIND = 'admin.crud.list.datagrid.request.pre_bind';

    const CRUD_LIST_DATAGRID_REQUEST_POST_BIND = 'admin.crud.list.datagrid.request.post_bind';

    const CRUD_LIST_RESPONSE_PRE_RENDER = 'admin.crud.list.response.pre_render';


    const CRUD_CREATE_CONTEXT_POST_CREATE = 'admin.crud.create.context.post_create';

    const CRUD_CREATE_FORM_REQUEST_PRE_SUBMIT = 'admin.crud.create.form.request.pre_submit';

    const CRUD_CREATE_FORM_REQUEST_POST_SUBMIT = 'admin.crud.create.form.request.post_submit';

    const CRUD_CREATE_ENTITY_PRE_SAVE = 'admin.crud.create.entity.pre_save';

    const CRUD_CREATE_ENTITY_POST_SAVE = 'admin.crud.create.entity.post_save';

    const CRUD_CREATE_RESPONSE_PRE_RENDER = 'admin.crud.create.response.pre_render';


    const CRUD_EDIT_CONTEXT_POST_CREATE = 'admin.crud.edit.context.post_create';

    const CRUD_EDIT_FORM_REQUEST_PRE_SUBMIT = 'admin.crud.edit.form.request.pre_submit';

    const CRUD_EDIT_FORM_REQUEST_POST_SUBMIT = 'admin.crud.edit.form.request.post_submit';

    const CRUD_EDIT_ENTITY_PRE_SAVE = 'admin.crud.edit.entity.pre_save';

    const CRUD_EDIT_ENTITY_POST_SAVE = 'admin.crud.edit.entity.post_save';

    const CRUD_EDIT_RESPONSE_PRE_RENDER = 'admin.crud.edit.response.pre_render';


    const CRUD_DELETE_CONTEXT_POST_CREATE = 'admin.crud.delete.context.post_create';

    const CRUD_DELETE_FORM_PRE_SUBMIT = 'admin.crud.delete.form.pre_submit';

    const CRUD_DELETE_FORM_POST_SUBMIT = 'admin.crud.delete.form.post_submit';

    const CRUD_DELETE_ENTITIES_PRE_DELETE = 'admin.crud.delete.entities.pre_delete';

    const CRUD_DELETE_ENTITIES_POST_DELETE = 'admin.crud.delete.entities.post_delete';
}
