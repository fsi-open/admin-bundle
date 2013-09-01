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
final class ResourceEvents
{
    const RESOURCE_CONTEXT_POST_CREATE = 'resource.context.post_create';

    const RESOURCE_FORM_REQUEST_PRE_SUBMIT = 'resource.form.request.pre_submit';

    const RESOURCE_FORM_REQUEST_POST_SUBMIT = 'resource.form.request.post_submit';

    const RESOURCE_PRE_SAVE = 'resource.pre_save';

    const RESOURCE_POST_SAVE = 'resource.post_save';

    const RESOURCE_RESPONSE_PRE_RENDER = 'resource.response.pre_render';
}