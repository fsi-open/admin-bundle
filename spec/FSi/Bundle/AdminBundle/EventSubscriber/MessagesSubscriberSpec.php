<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\EventSubscriber;

use FSi\Bundle\AdminBundle\Event\BatchObjectsPostApplyEvent;
use FSi\Bundle\AdminBundle\Event\FormDataPostSaveEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormRequestPostSubmitEvent;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormInterface;

class MessagesSubscriberSpec extends ObjectBehavior
{
    public function let(FlashMessages $flashMessages): void
    {
        $this->beConstructedWith($flashMessages);
    }

    public function it_listen_events(): void
    {
        self::getSubscribedEvents()->shouldReturn([
            FormRequestPostSubmitEvent::class => 'onFormRequestPostSubmit',
            FormDataPostSaveEvent::class => 'onFormDataPostSave',
            BatchObjectsPostApplyEvent::class => 'onBatchObjectsPostApply',
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
