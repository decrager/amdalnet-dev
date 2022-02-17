<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ZeroDaHero\LaravelWorkflow\Events\GuardEvent;
use App\Entity\WorkflowLog;

class ProjectWorkflowSubscriber
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
        
    }

    /**
     * Handle workflow leave event.
     */
    public function onLeave($event)
    {
        
    }

    /**
     * Handle workflow transition event.
     */
    public function onTransition($event) {
        // var_dump('evt.transition');
    }

    /**
     * Handle workflow enter event.
     */
    public function onEnter($event) {
        // var_dump('evt.enter');
    }

    /**
     * Handle workflow entered event.
     */
    public function onEntered($event) {
        // var_dump('evt.entered');
    }

    /**
     * Handle workflow completed event.
     */
    public function onCompleted($event) {
        // var_dump('evt.completed');
        // var_dump(get_class($event));
        // var_dump($event->serialize());

        $oriEvent = $event->getOriginalEvent();
        $transition = $oriEvent->getTransition();
        $project = $oriEvent->getSubject();
        
        // transition data
        $tname = $transition->getName();
        $tfroms = $transition->getFroms();
        $ttos = $transition->getTos();
        
        $duration = 0;
        $duration_total = 0;
        $now = now();
        // calculate duration
        if ($tfroms[0] == 'init') {
            $duration = $now->floatDiffInSeconds($project->created_at);
            $duration_total = $duration;
        } else {
            $lastlog = WorkflowLog::where([['id_project', $project->id], ['to_place', $tfroms[0]]])
                ->orderBy('created_at', 'DESC')
                ->first();
            $duration = $now->floatDiffInSeconds($lastlog->created_at);
            $duration_total = $now->floatDiffInSeconds($project->created_at);
        }
        
        // var_dump($oriEvent->getMarking());
        // var_dump($transition->getTransition());
        // var_dump($oriEvent->getWorkflowName());

        $log = WorkflowLog::create([
            'id_project' => $project->id,
            'transition' => $tname,
            'from_place' => $tfroms[0],
            'to_place' => $ttos[0],
            'duration' => $duration,
            'duration_total' => $duration_total,
        ]);
    }

    /**
     * Handle workflow annouce event.
     */
    public function onAnnounce($event) {
        // var_dump('evt.announce');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        // can use any of the three formats:
        // workflow.guard
        // workflow.[workflow name].guard
        // workflow.[workflow name].guard.[transition name]
        $events->listen(
            'workflow.amdalnet.guard',
            'App\Listeners\ProjectWorkflowSubscriber@onGuard'
        );

        // workflow.leave
        // workflow.[workflow name].leave
        // workflow.[workflow name].leave.[place name]
        $events->listen(
            'workflow.amdalnet.leave',
            'App\Listeners\ProjectWorkflowSubscriber@onLeave'
        );

        // workflow.transition
        // workflow.[workflow name].transition
        // workflow.[workflow name].transition.[transition name]
        $events->listen(
            'workflow.amdalnet.transition',
            'App\Listeners\ProjectWorkflowSubscriber@onTransition'
        );

        // workflow.enter
        // workflow.[workflow name].enter
        // workflow.[workflow name].enter.[place name]
        $events->listen(
            'workflow.amdalnet.enter',
            'App\Listeners\ProjectWorkflowSubscriber@onEnter'
        );

        // workflow.entered
        // workflow.[workflow name].entered
        // workflow.[workflow name].entered.[place name]
        $events->listen(
            'workflow.amdalnet.entered',
            'App\Listeners\ProjectWorkflowSubscriber@onEntered'
        );

        // workflow.completed
        // workflow.[workflow name].completed
        // workflow.[workflow name].completed.[transition name]
        $events->listen(
            'workflow.amdalnet.completed',
            'App\Listeners\ProjectWorkflowSubscriber@onCompleted'
        );

        // workflow.announce
        // workflow.[workflow name].announce
        // workflow.[workflow name].announce.[transition name]
        $events->listen(
            'workflow.amdalnet.announce',
            'App\Listeners\ProjectWorkflowSubscriber@onAnnounce'
        );
    }
}
