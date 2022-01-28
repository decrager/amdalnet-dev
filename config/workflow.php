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
        'supports' => ['App\Entity\ProjectX'],
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
        'type' => 'state_machine',
        'marking_store' => [
            'type' => 'single_state', // multiple_state | single_state, can be omitted to default to workflow type's default
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
        'places' => ['init', 'info-filling', 'in-screening', 'screening-completed','formulator-assignment',
        'announcement-drafting','announcement', 'announcement-completed', 'uklupl-mr.pkplh-published',
        'uklupl-mt.form', 'uklupl-mt.matrix-ukl', 'uklupl-mt.matrix-upl','uklupl-mt.submitted',
        'uklupl-mt.adm-review', 'uklupl-mt.adm-returned', 'uklupl-mt.examination-invitation-drafting',
        'uklupl-mt.examination-invitation-sent',
        'uklupl-mt.examination', 'uklupl-mt.examination-meeting', 'uklupl-mt.returned', 'uklupl-mt.ba-drafting',
        'uklupl-mt.ba-signed', 'uklupl-mt.recommendation-drafting', 'uklupl-mt.recommendation-signed', 'uklupl-mt.pkplh-published',
        'amdal.form-ka-drafting', 'amdal.form-ka-submitted', 'amdal.form-ka-adm-review','amdal.form-ka-adm-returned',
        'amdal.form-ka-adm-approved', 'amdal.form-ka-examination-invitation-drafting', 'amdal.form-ka-examination-invitation-sent', 
        'amdal.form-ka-examination','amdal.form-ka-examination-meeting', 'amdal.form-ka-returned', 'amdal.form-ka-approved',
        'amdal.form-ka-ba-drafting', 'amdal.form-ka-ba-signed', 'amdal.andal-drafting','amdal.rklrpl-drafting',
        'amdal.submitted', 'amdal.adm-review', 'amdal.adm-returned', 'amdal.adm-approved', 'amdal.examination',
        'amdal.feasibility-invitation-drafting', 'amdal.feasibility-invitation-sent', 'amdal.feasibility-review',
        'amdal.feasibility-meeting', 'amdal.feasibility-returned', 'amdal.feasibility-ba-drafting', 'amdal.feasibility-ba-signed',
        'amdal.recommendation-drafting', 'amdal.recommendation-signed', 'amdal.skkl-published'],
        'initial_places' => ['init'], // defaults to the first place if omitted
        'transitions' => [
            'fill-info' => [
                'from' => 'init',
                'to' => 'info-filling',
            ],
            'screening' => [
                'from' => 'info-filling',
                'to' => 'in-screening',
            ],
            'complete-screening' => [
                'from' => 'in-screening',
                'to' => 'screening-completed',
            ],
            'assign-formulator' => [
                'from' => 'screening-completed',
                'to' => 'formulator-assignment',
            ],
            'draft-announcement' => [
                'from' => 'formulator-assignment',
                'to' => 'announcement-drafting',
            ],
            'announce' => [
                'from' => 'announcement-drafting',
                'to' => 'announcement',
            ],
            'complete-announcement' => [ 
                'from' => 'announcement',
                'to' => 'announcement-completed',
            ],
              'publish-uklupl-mr-pkplh' => [   // 1. UKL-UPL MR
                  'from' => 'announcement-completed',
                  'to' => 'uklupl-mr.pkplh-published',
              ],
              'fill-uklupl-form' => [          // 2. UKL-UPL MT
                  'from' => 'announcement-completed',
                  'to' => 'uklupl-mt.form',
              ],
                'fill-uklupl-matrix-ukl' => [ 
                    'from' => 'uklupl-mt.form',
                    'to' => 'uklupl-mt.matrix-ukl',
                ],
                'fill-uklupl-matrix-upl' => [ 
                    'from' => 'uklupl-mt.matrix-ukl',
                    'to' => 'uklupl-mt.matrix-upl',
                ],
                'submit-uklupl' => [ 
                    'from' => 'uklupl-mt.matrix-upl',
                    'to' => 'uklupl-mt.submitted',
                ],
                'review-uklupl-adm' => [ 
                    'from' => 'uklupl-mt.submitted',
                    'to' => 'uklupl-mt.adm-review',
                ],
                  'return-uklupl-adm' => [                      // Adm returned
                      'from' => 'uklupl-mt.adm-review',
                      'to' => 'uklupl-mt.adm-returned',
                  ],
                  'draft-uklupl-examination-invitation' => [    // Continue
                      'from' => 'uklupl-mt.adm-review',
                      'to' => 'uklupl-mt.examination-invitation-drafting',
                  ],
                    'send-uklupl-examination-invitation' => [    
                        'from' => 'uklupl-mt.examination-invitation-drafting',
                        'to' => 'uklupl-mt.examination-invitation-sent',
                    ],
                    'examine-uklupl' => [  
                        'from' => 'uklupl-mt.examination-invitation-sent',
                        'to' => 'uklupl-mt.examination',
                    ],
                    'held-uklupl-examination-meeting' => [  
                        'from' => 'uklupl-mt.examination',
                        'to' => 'uklupl-mt.examination-meeting',
                    ],
                      'return-uklupl-examination' => [    // UKL-UPL returned
                          'from' => 'uklupl-mt.examination-meeting',
                          'to' => 'uklupl-mt.returned',
                      ],
                      'draft-uklupl-ba' => [              // continue
                          'from' => 'uklupl-mt.examination-meeting',
                          'to' => 'uklupl-mt.ba-drafting',
                      ],
                        'sign-uklupl-ba' => [  
                            'from' => 'uklupl-mt.ba-drafting',
                            'to' => 'uklupl-mt.ba-signed',
                        ],
                        'draft-uklupl-recommendation' => [  
                            'from' => 'uklupl-mt.ba-signed',
                            'to' => 'uklupl-mt.recommendation-drafting',
                        ],
                        'sign-uklupl-recommendation' => [  
                            'from' => 'uklupl-mt.recommendation-drafting',
                            'to' => 'uklupl-mt.recommendation-signed',
                        ],
                        'publish-uklupl-pkplh' => [  
                            'from' => 'uklupl-mt.recommendation-signed',
                            'to' => 'uklupl-mt.pkplh-published',
                        ],
              'draft-amdal-form-ka' => [          // 3. AMDAL
                  'from' => 'announcement-completed',
                  'to' => 'amdal.form-ka-drafting',
              ],
                'submit-amdal-form-ka' => [
                    'from' => 'amdal.form-ka-drafting',
                    'to' => 'amdal.form-ka-submitted',
                ],
                'review-amdal-form-ka' => [
                    'from' => 'amdal.form-ka-submitted',
                    'to' => 'amdal.form-ka-adm-review',
                ],
                  'return-amdal-form-ka-review' => [
                      'from' => 'amdal.form-ka-adm-review',
                      'to' => 'amdal.form-ka-adm-returned',
                  ],
                  'approve-amdal-form-ka' => [
                      'from' => 'amdal.form-ka-adm-review',
                      'to' => 'amdal.form-ka-adm-approved',
                  ],
                    'draft-amdal-form-ka-examination-invitation' => [
                        'from' => 'amdal.form-ka-adm-approved',
                        'to' => 'amdal.form-ka-examination-invitation-drafting',
                    ],
                    'send-amdal-form-ka-examination-invitation' => [
                        'from' => 'amdal.form-ka-examination-invitation-drafting',
                        'to' => 'amdal.form-ka-examination-invitation-sent',
                    ],
                    'examine-amdal-form-ka' => [
                        'from' => 'amdal.form-ka-examination-invitation-sent',
                        'to' => 'amdal.form-ka-examination',
                    ],
                    'held-amdal-form-ka-meeting' => [
                        'from' => 'amdal.form-ka-examination',
                        'to' => 'amdal.form-ka-examination-meeting',
                    ],
                      'return-amdal-form-ka' => [
                          'from' => 'amdal.form-ka-examination-meeting',
                          'to' => 'amdal.form-ka-returned',
                      ],
                      'aprrove-amdal-form-ka' => [
                          'from' => 'amdal.form-ka-examination-meeting',
                          'to' => 'amdal.form-ka-approved',
                      ],
                        'draft-amdal-form-ka-ba' => [
                            'from' => 'amdal.form-ka-approved',
                            'to' => 'amdal.form-ka-ba-drafting',
                        ],
                        'sign-amdal-form-ka-ba' => [
                            'from' => 'amdal.form-ka-ba-drafting',
                            'to' => 'amdal.form-ka-ba-signed',
                        ],
                        'draft-amdal-andal' => [
                            'from' => 'amdal.form-ka-ba-signed',
                            'to' => 'amdal.andal-drafting',
                        ],
                        'draft-amdal-rklrpl' => [
                            'from' => 'amdal.andal-drafting',
                            'to' => 'amdal.rklrpl-drafting',
                        ],
                        'submit-amdal' => [
                            'from' => 'amdal.rklrpl-drafting',
                            'to' => 'amdal.submitted',
                        ],
                        'review-amdal-adm' => [
                            'from' => 'amdal.submitted',
                            'to' => 'amdal.adm-review',
                        ],
                          'return-amdal-adm' => [
                              'from' => 'amdal.adm-review',
                              'to' => 'amdal.adm-returned',
                          ],
                          'approve-amdal-adm' => [
                              'from' => 'amdal.adm-review',
                              'to' => 'amdal.adm-approved',
                          ],
                            'examine-amdal' => [
                                'from' => 'amdal.adm-approved',
                                'to' => 'amdal.examination',
                            ],
                            'draft-amdal-feasibility-invitation' => [
                                'from' => 'amdal.examination',
                                'to' => 'amdal.feasibility-invitation-drafting',
                            ],
                            'send-amdal-feasibility-invitation' => [
                                'from' => 'amdal.feasibility-invitation-drafting',
                                'to' => 'amdal.feasibility-invitation-sent',
                            ],
                            'review-amdal-feasibility' => [
                                'from' => 'amdal.feasibility-invitation-sent',
                                'to' => 'amdal.feasibility-review',
                            ],
                            'held-amdal-feasibility-meeting' => [
                                'from' => 'amdal.feasibility-review',
                                'to' => 'amdal.feasibility-meeting',
                            ],
                              'return-amdal-feasibility' => [
                                  'from' => 'amdal.feasibility-meeting',
                                  'to' => 'amdal.feasibility-returned',
                              ],
                              'draft-amdal-feasibility-ba' => [
                                  'from' => 'amdal.feasibility-meeting',
                                  'to' => 'amdal.feasibility-ba-drafting',
                              ],
                                'sign-amdal-feasibility-ba' => [
                                    'from' => 'amdal.feasibility-ba-drafting',
                                    'to' => 'amdal.feasibility-ba-signed',
                                ],
                                'draft-amdal-recommendation' => [
                                    'from' => 'amdal.feasibility-ba-signed',
                                    'to' => 'amdal.recommendation-drafting',
                                ],
                                'sign-amdal-recommendation' => [
                                    'from' => 'amdal.recommendation-drafting',
                                    'to' => 'amdal.recommendation-signed',
                                ],
                                'publish-amdal-skkl' => [
                                    'from' => 'amdal.recommendation-signed',
                                    'to' => 'amdal.skkl-published',
                                ],
        ],
    ],
];
