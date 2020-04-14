<?php

require_once './autoloader.php';

class Recount
{
    public $jobs;
    public $columnValue;
    public $projects;
    public $table;

    public function updatePoints()
    {
        $this->table = 'jobs';
        $this->columnValue = 'SCHOOL1';
        //Getting all from table by Conditions
        $this->jobs = DB::getInstance()->getAllFromTableByParams($this->table, '2020-03-01', 'SCHOOL1', 1);
        //Getting all jobs for Project
        $this->projects = DB::getInstance()->getAllByProjectID($this->columnValue, 'jobsteps');


        foreach ($this->projects as $project => $currentProject) {
            foreach ($this->jobs as $job => $data) {
                //Matching project and jobs
                if ($currentProject['project_id'] == $data['project'] && $currentProject['job_type'] == $data['job_type']) {
                    //new Points added
                    $newPoints = number_format($currentProject['koef'] * $data['unit_val'], 2, '.', '');
                    $id = $data['id'];
                    //Inserting new Points by ID
                    DB::getInstance()->updateProjectPoints($id, $this->table, $newPoints);
                }
            }
        }
    }
}
