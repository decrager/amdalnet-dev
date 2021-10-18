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
    'amdalnet' => [
        'type' => 'workflow',  
        'marking_store' => [
            'type' => 'multiple_state', // multiple_state | single_state, can be omitted to default to workflow type's default
            'property' => 'marking', // this is the property on the model, defaults to 'marking'
        ],
        // optional top-level metadata
        'metadata' => [
            // any data 
        ],
        'supports' => ['App\Entity\Project'], // objects this workflow supports
        'events_to_dispatch' => [
            'workflow.enter',
            'workflow.leave',
            'workflow.transition',
            'workflow.entered',
            'workflow.completed',
            'workflow.announce',
            'workflow.guard',
        ],
        'places' => ['initial', 'penapisan', 'amdal', 'rkl-rpl', 'ka-draft', 'ka-review', 'ka-finish', 'andal-draft', 'andal-review', 'andal-finish', 'ukl-upl-draft', 'ukl-upl-review', 'ukl-upl-finish', 'sppl', 'finish'],
        'initial_places' => ['initial'], // defaults to the first place if omitted
        'transitions' => [
            'to_filter' => [
                'from' => 'initial',
                'to' => 'penapisan',
                // optional transition-level metadata
                'metadata' => [
                    // any data
                ]
            ],
            'to_amdal' => [
                'from' => 'penapisan',
                'to' => 'amdal',
                'metadata' => []
            ],
            'to_sppl' => [
                'from' => 'penapisan',
                'to' => 'sppl'
            ],
            'to_ka_draft' => [
                'from' => 'amdal',
                'to' => ['ka-draft']
            ],
            'to_ka_review' => [
                'from' => 'ka-draft',
                'to' => ['ka-review']
            ],
            'to_ka_finish' => [
                'from' => 'ka-review',
                'to' => ['ka-finish']
            ],
            'to_andal_draft' => [
                'from' => 'ka-finish',
                'to' => ['andal-draft']
            ],
            'to_andal_review' => [
                'from' => 'andal-draft',
                'to' => ['andal-review']
            ],
            'to_andal_finish' => [
                'from' => 'andal-review',
                'to' => ['andal-finish']
            ],
            'to_rkl_rpl' => [
                'from' => 'ka-finish',
                'to' => ['rkl-rpl']
            ],
            'to_ukl_upl_draft' => [
                'from' => 'penapisan',
                'to' => ['ukl-upl-draft']
            ],
            'to_ukl_upl_review' => [
                'from' => 'ukl-upl-draft',
                'to' => ['ukl-upl-review']
            ],
            'to_ukl_upl_finish' => [
                'from' => 'ukl-upl-review',
                'to' => ['ukl-upl-finish']
            ],
            'to_finish' => [
                'from' => ['ukl-upl-finish', 'sppl', 'andal-finish', 'rkl-rpl'],
                'to' => ['finish'],
                'metadata' => [
                    'not_amdal' => true,
                    'not_ukl_upl'=> true,
                ]
            ]
        ],
    ],
];
