<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entity\BlogPost;
use Workflow;

class WorkflowTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Workflow Test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $post = factory(BlogPost::class)->create();
        $post = BlogPost::factory()->create();
        $workflow = Workflow::get($post);
        $workflow = $post->workflow_get();
        
        // draft
        var_dump($workflow->can($post, 'publish')); // False
        var_dump($workflow->can($post, 'reject')); // False
        var_dump($workflow->can($post, 'to_review')); // True
        $transitions = $workflow->getEnabledTransitions($post);
        var_dump($transitions);

        // Apply a transition
        $workflow->apply($post, 'to_review');
        $post->save(); // Don't forget to persist the state

        // Get the workflow directly
        // Using the WorkflowTrait
        var_dump($post->workflow_can('publish')); // True
        var_dump($post->workflow_can('reject')); // True
        var_dump($post->workflow_can('to_review')); // False
        // var_dump($post->workflow_get());

        // Get the post transitions
        echo "Get Transition:\n";
        foreach ($post->workflow_transitions() as $transition) {
            echo $transition->getName()."\n";
            $transitionMetadata = $workflow->getMetadataStore()->getTransitionMetadata($transition); 
            var_dump($transitionMetadata);
        }

        // Apply a transition
        $post->workflow_apply('publish');
        $post->save();

        // Get the current places
        $places = $workflow->getMarking($post)->getPlaces();
        var_dump($places);

        // Get the definition
        $definition = $workflow->getDefinition();
        var_dump($definition);

        // Get the metadata
        $metadata = $workflow->getMetadataStore();
        var_dump($metadata);
        // or get a specific piece of metadata
        $workflowMetadata = $workflow->getMetadataStore()->getWorkflowMetadata();
        var_dump($workflowMetadata);
        $placeMetadata = $workflow->getMetadataStore()->getPlaceMetadata('published'); // string place name
        var_dump($placeMetadata);
        // var_dump($workflow);
        // $otherPlaceMetadata = $workflow->getMetadataStore()->getMetadata('max_num_of_words', 'draft');
    }
}
