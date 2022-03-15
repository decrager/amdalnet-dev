<?php

namespace Tests\Feature;

use App\Entity\Project;
use App\Services\OssService;
use Tests\TestCase;

class OssTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');
        $project = Project::findOrFail(196);
        $result = OssService::receiveLicense($project);
        // $result2 = OssService::receiveLicenseStatus();
        if ($result) {
            $response->assertStatus(200);
        } else {
            $response->assertStatus(500);
        }
    }
}
