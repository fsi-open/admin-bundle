<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormInterface;

class MessagesListenerSpec extends ObjectBehavior
{
    public function let(FlashMessages $flashMessages): void
    {
        $this->beConstructedWith($flashMessages);
    }

    public function it_listen_events(): void
    {
        $this->getSubscribedEvents()->shouldReturn([
            FormEvents::FORM_REQUEST_POST_SUBMIT => 'onFormRequestPostSubmit',
            FormEvents::FORM_DATA_POST_SAVE => 'onFormDataPostSave',
            BatchEvents::BATCH_OBJECTS_POST_APPLY => 'onBatchObjectsPostApply',
        ]);
    }

    public function it_not_set_error_message_when_form_is_valid(
        FormEvent $event,
        FormInterface $form,
        FlashMessages $flashMessages
    ): void {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $flashMessages->error('messages.form.error')->shouldNotBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    public function it_set_error_message_when_form_is_invalid(
        FormEvent $event,
        FormInterface $form,
        FlashMessages $flashMessages
    ): void {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(false);
        $flashMessages->error('messages.form.error')->shouldBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    public function it_add_message_on_post_save(FormEvent $event, FlashMessages $flashMessages): void
    {
        $flashMessages->success('messages.form.save')->shouldBeCalled();
        $this->onFormDataPostSave($event);
    }

    public function it_add_message_on_batch_post_apply(FormEvent $event, FlashMessages $flashMessages): void
    {
        $flashMessages->success('messages.batch.success')->shouldBeCalled();
        $this->onBatchObjectsPostApply($event);
    }
}
