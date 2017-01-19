<?php


namespace spec\AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Event\BatchEvents;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\Form;

class MessagesListenerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function let($flashMessages)
    {
        $this->beConstructedWith($flashMessages);
    }

    public function it_listen_events()
    {
        $this->getSubscribedEvents()->shouldReturn([
            FormEvents::FORM_REQUEST_POST_SUBMIT => 'onFormRequestPostSubmit',
            FormEvents::FORM_DATA_POST_SAVE => 'onFormDataPostSave',
            BatchEvents::BATCH_OBJECTS_POST_APPLY => 'onBatchObjectsPostApply',
        ]);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\Form\Form $form
     * @param \AdminPanel\Symfony\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_not_set_error_message_when_form_is_valid($event, $form, $flashMessages)
    {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $flashMessages->error('messages.form.error')->shouldNotBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\Form\Form $form
     * @param \AdminPanel\Symfony\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_set_error_message_when_form_is_invalid($event, $form, $flashMessages)
    {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(false);
        $flashMessages->error('messages.form.error')->shouldBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \AdminPanel\Symfony\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_add_message_on_post_save($event, $flashMessages)
    {
        $flashMessages->success('messages.form.save')->shouldBeCalled();
        $this->onFormDataPostSave($event);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \AdminPanel\Symfony\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_add_message_on_batch_post_apply($event, $flashMessages)
    {
        $flashMessages->success('messages.batch.success')->shouldBeCalled();
        $this->onBatchObjectsPostApply($event);
    }
}
