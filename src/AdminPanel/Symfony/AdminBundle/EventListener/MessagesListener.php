<?php


namespace AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\BatchEvents;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use AdminPanel\Symfony\AdminBundle\Message\FlashMessages;
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
