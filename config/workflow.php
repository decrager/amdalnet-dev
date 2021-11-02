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
    'amdal' => [
        'type' => 'workflow',
        'marking_store' => [
            'type' => 'multiple_state',
            'property' => 'marking',
        ],
        'supports' => ['App\Entity\Project'],
        'events_to_dispatch' => [
            'workflow.enter',
            'workflow.leave',
            'workflow.transition',
            'workflow.entered',
            'workflow.completed',
            'workflow.announce',
            'workflow.guard',
        ],
        'places' => ['initial', 'amdal', 'rkl-rpl', 'ka',  'andal', 'ukl-upl', 'sppl', 'published', 'sk-pkplh', 'sk-skkl'],
        'initial_places' => ['initial'],
        'transitions' => [
            'to_amdal' => [
                'from' => 'initial',
                'to' => 'amdal',
            ],
            'to_ka' => [
                'from' => 'amdal',
                'to' => 'ka',
            ],
            'to_andal' => [
                'from' => 'ka',
                'to' => 'andal',
            ],
            'to_rklrpl' => [
                'from' => 'ka',
                'to' => 'rkl-rpl',
            ],
            'gen_skkl' => [
                'from' => ['andal', 'rkl-rpl'],
                'to' => 'sk-skkl',
            ],
            'gen_pkplh' => [
                'from' => 'ukl-upl',
                'to' => 'sk-pkplh',
            ],
            'to_sppl' => [
                'from' => 'initial',
                'to' => 'sppl',
            ],
            'to_uklupl' => [
                'from' => 'initial',
                'to' => 'ukl-upl',
            ],
            'publish' => [
                'from' => 'initial',
                'to' => 'published',
            ],
            // 'finish' => [
            //     'from' => ['sk-pkplh', 'sppl', 'sk-skkl'],
            //     'to' => ['done'],
            // ]
        ]
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
        'places' => ['init', 'in-screening', 'sppl-completed', 'amdal.announcement', 'amdal.announcement-completed', 'amdal.pubcons', 'amdal.pubcons-completed', 
            'uklupl.mr-activities', 'uklupl.mr-ukl', 'uklupl.mr-upl', 'uklupl.mr-completed', 
            'uklupl.mt-activities', 'uklupl.mt-environment-set', 'uklupl.mt-impact', 'uklupl.mt-ukl', 'uklupl.mt-upl', 'uklupl.mt-submitted',
            'amdal.ka-draft', 'amdal.ka-draft-submitted', 'amdal.ka-draft-reviewed', 'amdal.ka-draft-approved', 'amdal.ka-resubmitted', 
            'amdal.andal-draft', 'amdal.rklrpl.activities', 'amdal.rklrpl.environment-set', 'amdal.rklrpl.impact-matrix', 'amdal.rklrpl.dp', 'amdal.rklrpl.dph', 'amdal.rklrpl.rkl', 'amdal.rklrpl.rpl', 
            'amdal.submitted', 'amdal.andal-submitted', 'uklupl.mr', 'uklupl.mt', 'amdal',
            'uklupl.mt-reviewed', 'uklupl.mt-completed', 'amdal.reviewed', 'amdal.completed'],
        'initial_places' => ['init'], // defaults to the first place if omitted
        'transitions' => [
            'screening' => [
                'from' => 'init',
                'to' => 'in-screening',
            ],
            'to_sppl' => [
                'from' => 'in-screening',
                'to' => 'sppl-completed',
            ],
            'to_uklupl_mr' => [
                'from' => 'in-screening',
                'to' => ['uklupl.mr', 'amdal.announcement'],
            ],
            'to_uklupl_mt' => [
                'from' => 'in-screening',
                'to' => ['uklupl.mt', 'amdal.announcement'],
            ],
            'to_amdal' => [
                'from' => 'in-screening',
                'to' => ['amdal', 'amdal.announcement'],
            ],
            'to_uklupl_mr_activities' => [
                'from' => ['uklupl.mr', 'amdal.pubcons-completed'],
                'to' => 'uklupl.mr-activities',
            ],
            'to_uklupl_mt_activities' => [
                'from' => ['uklupl.mt', 'amdal.pubcons-completed'],
                'to' => 'uklupl.mt-activities',
            ],
            'plan_ukl_mr' => [
                'from' => 'uklupl.mr-activities',
                'to' => 'uklupl.mr-ukl',
            ],
            'plan_upl_mr' => [
                'from' => 'uklupl.mr-ukl',
                'to' => 'uklupl.mr-upl',
            ],
            'finish_uklupl_mr' => [
                'from' => 'uklupl.mr-upl',
                'to' => 'uklupl.mr-completed',
            ],
            'set_environment_mt' => [
                'from' => 'uklupl.mt-activities',
                'to' => 'uklupl.mt-environment-set',
            ],
            'set_impact_mt' => [
                'from' => 'uklupl.mt-environment-set',
                'to' => 'uklupl.mt-impact',
            ],
            'plan_ukl_mt' => [
                'from' => 'uklupl.mt-impact',
                'to' => 'uklupl.mt-ukl',
            ],
            'plan_upl_mt' => [
                'from' => 'uklupl.mt-ukl',
                'to' => 'uklupl.mt-upl',
            ],
            'submit_uklupl_mt' => [
                'from' => 'uklupl.mt-upl',
                'to' => 'uklupl.mt-submitted',
            ],
            'review_uklupl_mt' => [
                'from' => 'uklupl.mt-submitted',
                'to' => 'uklupl.mt-reviewed',
            ],
            'finish_uklupl_mt' => [
                'from' => 'uklupl.mt-reviewed',
                'to' => 'uklupl.mt-completed',
            ],
            'draft_amdal_ka' => [
                'from' => ['amdal', 'amdal.pubcons-completed'],
                'to' => 'amdal.ka-draft',
            ],
            'submit_amdal_ka' => [
                'from' => 'amdal.ka-draft',
                'to' => 'amdal.ka-draft-submitted',
            ],
            'review_amdal_ka' => [
                'from' => 'amdal.ka-draft-submitted',
                'to' => 'amdal.ka-draft-reviewed',
            ],
            'approve_amdal_ka' => [
                'from' => 'amdal.ka-draft-reviewed',
                'to' => 'amdal.ka-draft-approved',
            ],
            'resubmit_amdal_ka' => [
                'from' => 'amdal.ka-draft-approved',
                'to' => 'amdal.ka-resubmitted',
            ],
            'to_andal_rklrpl' => [
                'from' => 'amdal.ka-resubmitted',
                'to' => ['amdal.andal-draft', 'amdal.rklrpl.activities'],
            ],
            'submit_andal' => [
                'from' => 'amdal.andal-draft',
                'to' => 'amdal.andal-submitted',
            ],
            // 'approve_andal' => [
            //     'from' => 'amdal.andal-submitted',
            //     'to' => 'amdal.andal-approved',
            // ],
            'set_environment_rklrpl' => [
                'from' => 'amdal.rklrpl.activities',
                'to' => 'amdal.rklrpl.environment-set',
            ],
            'to_impact_matrix_rklrpl' => [
                'from' => 'amdal.rklrpl.environment-set',
                'to' => 'amdal.rklrpl.impact-matrix',
            ],
            'to_rklrpl_dp' => [
                'from' => 'amdal.rklrpl.impact-matrix',
                'to' => 'amdal.rklrpl.dp',
            ],
            'to_rklrpl_dph' => [
                'from' => 'amdal.rklrpl.dp',
                'to' => 'amdal.rklrpl.dph',
            ],
            'plan_rklrpl_rkl' => [
                'from' => 'amdal.rklrpl.dph',
                'to' => 'amdal.rklrpl.rkl',
            ],
            'plan_rklrpl_rpl' => [
                'from' => 'amdal.rklrpl.rkl',
                'to' => 'amdal.rklrpl.rpl',
            ],
            'submit_amdal' => [
                'from' => ['amdal.rklrpl.rpl', 'amdal.andal-submitted'],
                'to' => 'amdal.submitted',
            ],
            'review_amdal' => [
                'from' => 'amdal.submitted',
                'to' => 'amdal.reviewed',
            ],
            'finish_amdal' => [
                'from' => ['amdal.reviewed', 'sppl-completed', 'uklupl.mt-completed', 'uklupl.mr-completed'],
                'to' => 'amdal.completed',
            ],
            // 'finish_amdal_sppl' => [
            //     'from' => 'sppl-completed',
            //     'to' => 'amdal.completed',
            // ],
            // 'finish_amdal_' => [
            //     'from' => 'sppl-completed',
            //     'to' => 'amdal.completed',
            // ],
            'announced' => [
                'from' => 'amdal.announcement',
                'to' => 'amdal.announcement-completed',
            ],
            'pubcons' => [
                'from' => 'amdal.announcement-completed',
                'to' => 'amdal.pubcons',
            ],
            'finish_pubcons' => [
                'from' => 'amdal.pubcons',
                'to' => 'amdal.pubcons-completed',
            ],
        ],
    ],
];
