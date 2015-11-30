<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\Form;

class MessagesListenerSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Message\FlashMessages $flashMessages
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
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\Form\Form $form
     * @param \FSi\Bundle\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_not_set_error_message_when_form_is_valid($event, $form, $flashMessages)
    {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $flashMessages->error('messages.form.error')->shouldNotBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\Form\Form $form
     * @param \FSi\Bundle\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_set_error_message_when_form_is_invalid($event, $form, $flashMessages)
    {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(false);
        $flashMessages->error('messages.form.error')->shouldBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \FSi\Bundle\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_add_message_on_post_save($event, $flashMessages)
    {
        $flashMessages->success('messages.form.save')->shouldBeCalled();
        $this->onFormDataPostSave($event);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \FSi\Bundle\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_add_message_on_batch_post_apply($event, $flashMessages)
    {
        $flashMessages->success('messages.batch.success')->shouldBeCalled();
        $this->onBatchObjectsPostApply($event);
    }
}
