<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessagesListener implements EventSubscriberInterface
{
    /**
     * @var FlashMessages
     */
    private $flashMessages;

    public function __construct(FlashMessages $flashMessages)
    {
        $this->flashMessages = $flashMessages;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::FORM_REQUEST_POST_SUBMIT => 'onFormRequestPostSubmit',
            FormEvents::FORM_DATA_POST_SAVE => 'onFormDataPostSave',
            BatchEvents::BATCH_OBJECTS_POST_APPLY => 'onBatchObjectsPostApply',
        ];
    }

    public function onFormRequestPostSubmit(FormEvent $event)
    {
        if (!$event->getForm()->isValid()) {
            $this->flashMessages->error('messages.form.error');
        }
    }

    public function onFormDataPostSave(AdminEvent $event)
    {
        $this->flashMessages->success('messages.form.save');
    }

    public function onBatchObjectsPostApply(AdminEvent $event)
    {
        $this->flashMessages->success('messages.batch.success');
    }
}
