<?php

return [
    'straight' => [
        'type' => 'workflow',  // state_machine | workflow
        // The marking store can be omitted, and will default to 'multiple_state'
        // for workflow and 'single_state' for state_machine if the type is omitted
        'marking_store' => [
            'type' => 'multiple_state', // multiple_state | single_state, can be omitted to default to workflow type's default
            'property' => 'marking', // this is the property on the model, defaults to 'marking'
            // 'class' => EloquentMethodMarkingStore::class, // optional, uses EloquentMethodMarkingStore by default (for Eloquent models)
        ],
        // optional top-level metadata
        'metadata' => [
            // any data
        ],
        'supports' => ['App\Entity\BlogPost'], // objects this workflow supports
        // Specifies events to dispatch (only in 'workflow', not 'state_machine')
        // - set `null` to dispatch all events (default, if omitted)
        // - set to empty array (`[]`) to dispatch no events
        // - set to array of events to dispatch only specific events
        // Note that announce will dispatch a guard event on the next transition
        // (if announce isn't dispatched the next transition won't guard until checked/applied)
        'events_to_dispatch' => [
            'workflow.enter',
            'workflow.leave',
            'workflow.transition',
            'workflow.entered',
            'workflow.completed',
            'workflow.announce',
            'workflow.guard',
            // Symfony\Component\Workflow\WorkflowEvents::ENTER,
            // Symfony\Component\Workflow\WorkflowEvents::LEAVE,
            // Symfony\Component\Workflow\WorkflowEvents::TRANSITION,
            // Symfony\Component\Workflow\WorkflowEvents::ENTERED,
            // Symfony\Component\Workflow\WorkflowEvents::COMPLETED,
            // Symfony\Component\Workflow\WorkflowEvents::ANNOUNCE,
        ],
        'places' => ['draft', 'review', 'rejected', 'published'],
        'initial_places' => ['draft'], // defaults to the first place if omitted
        'transitions' => [
            'to_review' => [
                'from' => 'draft',
                'to' => 'review',
                // optional transition-level metadata
                'metadata' => [
                    // any data
                ]
            ],
            'publish' => [
                'from' => 'review',
                'to' => 'published',
                'metadata' => [
                    'status' => '200',
                    'completed' => True
                ]
            ],
            'reject' => [
                'from' => 'review',
                'to' => 'rejected'
            ]
        ],
    ],
];
