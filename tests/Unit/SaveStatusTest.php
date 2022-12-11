<?php

namespace Tests\Unit;

use Tests\TestCase;

class SaveStatusTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_return_true()
    {
        $projectId = 1777;
        $sections = [
            'matriks.ukl',
            'matriks.upl'
        ];

        $response = $this->get("/api/save-status?project_id=$projectId&sections[]=$sections[0]&sections[]=$sections[1]");

        $response->assertSeeText(['status' => true]);
        $response->assertStatus(200);
    }
}