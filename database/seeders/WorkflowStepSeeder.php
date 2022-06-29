<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Entity\WorkflowStep;

class WorkflowStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // protected $fillable = [ 'code', 'doc_type', 'rank', 'flag' ];
        $amdal = $this->getAmdal();
        $uklpul = $this->getUKLUPL();

        foreach($amdal as $step)
        {
            WorkflowStep::create($step);
        }
        foreach($uklpul as $step)
        {
            WorkflowStep::create($step);
        }
    }

    protected function getAmdal(){
        return [
            [ 'code' => 'PRO-1', 'doc_type' => 'AMDAL', 'rank' => 1, 'is_conditional' => false ],
            [ 'code' => 'PRO-1.5', 'doc_type' => 'AMDAL', 'rank' => 2, 'is_conditional' => false ],
            [ 'code' => 'PRO-2', 'doc_type' => 'AMDAL', 'rank' => 3, 'is_conditional' => false ],
            [ 'code' => 'PRO-3', 'doc_type' => 'AMDAL', 'rank' => 4, 'is_conditional' => false ],
            [ 'code' => 'PRO-4', 'doc_type' => 'AMDAL', 'rank' => 5, 'is_conditional' => false ],
            [ 'code' => 'PRO-5', 'doc_type' => 'AMDAL', 'rank' => 6, 'is_conditional' => false ],
            [ 'code' => 'PRO-6', 'doc_type' => 'AMDAL', 'rank' => 7, 'is_conditional' => false ],
            [ 'code' => 'AMD-2', 'doc_type' => 'AMDAL', 'rank' => 8, 'is_conditional' => false ],
            [ 'code' => 'AMD-3', 'doc_type' => 'AMDAL', 'rank' => 9, 'is_conditional' => false ],
            [ 'code' => 'AMD-4', 'doc_type' => 'AMDAL', 'rank' => 10, 'is_conditional' => false ],
            [ 'code' => 'AMD-5', 'doc_type' => 'AMDAL', 'rank' => 11, 'is_conditional' => true ],
            [ 'code' => 'AMD-6', 'doc_type' => 'AMDAL', 'rank' => 12, 'is_conditional' => false ],
            [ 'code' => 'AMD-6.5', 'doc_type' => 'AMDAL', 'rank' => 13, 'is_conditional' => false ],
            [ 'code' => 'AMD-7', 'doc_type' => 'AMDAL', 'rank' => 14, 'is_conditional' => false ],
            [ 'code' => 'AMD-8', 'doc_type' => 'AMDAL', 'rank' => 15, 'is_conditional' => false ],
            [ 'code' => 'AMD-9', 'doc_type' => 'AMDAL', 'rank' => 16, 'is_conditional' => false ],
            [ 'code' => 'AMD-10', 'doc_type' => 'AMDAL', 'rank' => 17, 'is_conditional' => true ],
            [ 'code' => 'AMD-11', 'doc_type' => 'AMDAL', 'rank' => 18, 'is_conditional' => false ],
            [ 'code' => 'AMD-11.5', 'doc_type' => 'AMDAL', 'rank' => 19, 'is_conditional' => false ],
            [ 'code' => 'AMD-12', 'doc_type' => 'AMDAL', 'rank' => 20, 'is_conditional' => false ],
            [ 'code' => 'AMD-13', 'doc_type' => 'AMDAL', 'rank' => 21, 'is_conditional' => false ],
            [ 'code' => 'AMD-14', 'doc_type' => 'AMDAL', 'rank' => 22, 'is_conditional' => false ],
            [ 'code' => 'AMD-15', 'doc_type' => 'AMDAL', 'rank' => 23, 'is_conditional' => false ],
            [ 'code' => 'AMD-16', 'doc_type' => 'AMDAL', 'rank' => 24, 'is_conditional' => false ],
            [ 'code' => 'AMD-17', 'doc_type' => 'AMDAL', 'rank' => 25, 'is_conditional' => false ],
            [ 'code' => 'AMD-18', 'doc_type' => 'AMDAL', 'rank' => 26, 'is_conditional' => false ],
            [ 'code' => 'AMD-18.1', 'doc_type' => 'AMDAL', 'rank' => 27, 'is_conditional' => false ],
            [ 'code' => 'AMD-18.2', 'doc_type' => 'AMDAL', 'rank' => 28, 'is_conditional' => false ],
            [ 'code' => 'AMD-19', 'doc_type' => 'AMDAL', 'rank' => 29, 'is_conditional' => false ],
            [ 'code' => 'AMD-20', 'doc_type' => 'AMDAL', 'rank' => 30, 'is_conditional' => false ],
            [ 'code' => 'AMD-21', 'doc_type' => 'AMDAL', 'rank' => 31, 'is_conditional' => false ],
            [ 'code' => 'AMD-21.5', 'doc_type' => 'AMDAL', 'rank' => 32, 'is_conditional' => true ],
            [ 'code' => 'AMD-21.6', 'doc_type' => 'AMDAL', 'rank' => 33, 'is_conditional' => false ],
            [ 'code' => 'AMD-22', 'doc_type' => 'AMDAL', 'rank' => 34, 'is_conditional' => false ],
            [ 'code' => 'AMD-22.5', 'doc_type' => 'AMDAL', 'rank' => 35, 'is_conditional' => false ],
            [ 'code' => 'AMD-23', 'doc_type' => 'AMDAL', 'rank' => 36, 'is_conditional' => false ],
            [ 'code' => 'AMD-24', 'doc_type' => 'AMDAL', 'rank' => 37, 'is_conditional' => false ],
        ];
    }

    protected function getUKLUPL(){
        return [
            [ 'code' => 'PRO-1', 'doc_type' => 'UKL-UPL', 'rank' => 1, 'is_conditional' => false ],
            [ 'code' => 'PRO-1.5', 'doc_type' => 'UKL-UPL', 'rank' => 2, 'is_conditional' => false ],
            [ 'code' => 'PRO-2', 'doc_type' => 'UKL-UPL', 'rank' => 3, 'is_conditional' => false ],
            [ 'code' => 'PRO-3', 'doc_type' => 'UKL-UPL', 'rank' => 4, 'is_conditional' => false ],
            [ 'code' => 'PRO-4', 'doc_type' => 'UKL-UPL', 'rank' => 5, 'is_conditional' => false ],
            [ 'code' => 'PRO-5', 'doc_type' => 'UKL-UPL', 'rank' => 6, 'is_conditional' => false ],
            [ 'code' => 'PRO-6', 'doc_type' => 'UKL-UPL', 'rank' => 7, 'is_conditional' => false ],
            [ 'code' => 'UKL-1', 'doc_type' => 'UKL-UPL', 'rank' => 8, 'is_conditional' => false ],
            [ 'code' => 'UKL-2', 'doc_type' => 'UKL-UPL', 'rank' => 9, 'is_conditional' => false ],
            [ 'code' => 'UKL-2.5', 'doc_type' => 'UKL-UPL', 'rank' => 10, 'is_conditional' => false ],
            [ 'code' => 'UKL-3', 'doc_type' => 'UKL-UPL', 'rank' => 11, 'is_conditional' => false ],
            [ 'code' => 'UKL-4', 'doc_type' => 'UKL-UPL', 'rank' => 12, 'is_conditional' => false ],
            [ 'code' => 'UKL-5', 'doc_type' => 'UKL-UPL', 'rank' => 13, 'is_conditional' => true ],
            [ 'code' => 'UKL-6', 'doc_type' => 'UKL-UPL', 'rank' => 14, 'is_conditional' => false ],
            [ 'code' => 'UKL-7', 'doc_type' => 'UKL-UPL', 'rank' => 15, 'is_conditional' => false ],
            [ 'code' => 'UKL-8', 'doc_type' => 'UKL-UPL', 'rank' => 16, 'is_conditional' => false ],
            [ 'code' => 'UKL-9', 'doc_type' => 'UKL-UPL', 'rank' => 17, 'is_conditional' => false ],
            [ 'code' => 'UKL-10', 'doc_type' => 'UKL-UPL', 'rank' => 18, 'is_conditional' => true ],
            [ 'code' => 'UKL-11', 'doc_type' => 'UKL-UPL', 'rank' => 19, 'is_conditional' => false ],
            [ 'code' => 'UKL-12', 'doc_type' => 'UKL-UPL', 'rank' => 20, 'is_conditional' => false ],
            [ 'code' => 'UKL-12.1', 'doc_type' => 'UKL-UPL', 'rank' => 21, 'is_conditional' => false ],
            [ 'code' => 'UKL-12.5', 'doc_type' => 'UKL-UPL', 'rank' => 22, 'is_conditional' => false ],
            [ 'code' => 'UKL-13', 'doc_type' => 'UKL-UPL', 'rank' => 23, 'is_conditional' => false ],
        ];
    }
}
