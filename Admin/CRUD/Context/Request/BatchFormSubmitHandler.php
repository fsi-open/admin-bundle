<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractFormSubmitHandler;
use FSi\Bundle\AdminBundle\Event\BatchEvents;

class BatchFormSubmitHandler extends AbstractFormSubmitHandler
{
    /**
     * @return string
     */
    protected function getPreSubmitEventName()
    {
        return BatchEvents::BATCH_REQUEST_PRE_SUBMIT;
    }

    /**
     * @return string
     */
    protected function getPostSubmitEventName()
    {
        return BatchEvents::BATCH_REQUEST_POST_SUBMIT;
    }
}
