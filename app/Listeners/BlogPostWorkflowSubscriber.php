<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ZeroDaHero\LaravelWorkflow\Events\GuardEvent;

class BlogPostWorkflowSubscriber
{
    // /**
    //  * Create the event listener.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // /**
    //  * Handle the event.
    //  *
    //  * @param  object  $event
    //  * @return void
    //  */
    // public function handle($event)
    // {
    //     //
    // }

    /**
     * Handle workflow guard events.
     */
    public function onGuard(GuardEvent $event)
    {
        /** Symfony\Component\Workflow\Event\GuardEvent */
        $originalEvent = $event->getOriginalEvent();

        /** @var App\BlogPost $post */
        $post = $originalEvent->getSubject();
        $title = $post->title;
        var_dump('evt.guard:', $title);

        if (empty($title)) {
            // Posts with no title should not be allowed
            $originalEvent->setBlocked(true);
        }
    }

    /**
     * Handle workflow leave event.
     */
    public function onLeave($event)
    {
        // The event can also proxy to the original event
        $subject = $event->getSubject(); // BlogPost object
        // is the same as:
        $subject = $event->getOriginalEvent()->getSubject();
        // var_dump('evt.leave:', $subject);
        var_dump('evt.leave');
    }

    /**
     * Handle workflow transition event.
     */
    public function onTransition($event) {
        var_dump('evt.transition');
    }

    /**
     * Handle workflow enter event.
     */
    public function onEnter($event) {
        var_dump('evt.enter');
    }

    /**
     * Handle workflow entered event.
     */
    public function onEntered($event) {
        var_dump('evt.entered');
    }

    /**
     * Handle workflow entered event.
     */
    public function onCompleted($event) {
        var_dump('evt.completed');
    }

    /**
     * Handle workflow entered event.
     */
    public function onAnnounce($event) {
        var_dump('evt.announce');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        var_dump('subscribe:evt');
        // can use any of the three formats:
        // workflow.guard
        // workflow.[workflow name].guard
        // workflow.[workflow name].guard.[transition name]
        $events->listen(
            'workflow.straight.guard',
            'App\Listeners\BlogPostWorkflowSubscriber@onGuard'
        );

        // workflow.leave
        // workflow.[workflow name].leave
        // workflow.[workflow name].leave.[place name]
        $events->listen(
            'workflow.straight.leave',
            'App\Listeners\BlogPostWorkflowSubscriber@onLeave'
        );

        // workflow.transition
        // workflow.[workflow name].transition
        // workflow.[workflow name].transition.[transition name]
        $events->listen(
            'workflow.straight.transition',
            'App\Listeners\BlogPostWorkflowSubscriber@onTransition'
        );

        // workflow.enter
        // workflow.[workflow name].enter
        // workflow.[workflow name].enter.[place name]
        $events->listen(
            'workflow.straight.enter',
            'App\Listeners\BlogPostWorkflowSubscriber@onEnter'
        );

        // workflow.entered
        // workflow.[workflow name].entered
        // workflow.[workflow name].entered.[place name]
        $events->listen(
            'workflow.straight.entered',
            'App\Listeners\BlogPostWorkflowSubscriber@onEntered'
        );

        // workflow.completed
        // workflow.[workflow name].completed
        // workflow.[workflow name].completed.[transition name]
        $events->listen(
            'workflow.straight.completed',
            'App\Listeners\BlogPostWorkflowSubscriber@onCompleted'
        );

        // workflow.announce
        // workflow.[workflow name].announce
        // workflow.[workflow name].announce.[transition name]
        $events->listen(
            'workflow.straight.announce',
            'App\Listeners\BlogPostWorkflowSubscriber@onAnnounce'
        );
    }
}
