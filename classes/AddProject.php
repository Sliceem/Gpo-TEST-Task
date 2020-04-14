<?php

class AddProject
{
    public $projectName;
    public $table;
    public $tableData;
    public $projects;
    public $columnValue;

    public function addingNewJob()
    {
        $this->projectName = 'SCHOOL1';
        $this->table = 'jobs';
        $this->columnValue = 'SCHOOL1';
        //Get all from Table where Projectname = 'SCHOOL1'
        $this->tableData = DB::getInstance()->getAllColumnsByProjectName($this->projectName, $this->table);
        //Get all jobs by Project
        $this->projects = DB::getInstance()->getAllByProjectID($this->columnValue, 'jobsteps');
        //NEW JOB config
        $insertNewJobType = ($this->projects[4]['job_type']);
        $newJobTypeKoef = ($this->projects[4]['koef']);

        for ($x = 0; $x < count($this->tableData); $x += 4) {
            //Rewriting new JOB, before inserting
            //Creating points for New JOB
            $this->tableData[$x]['points'] = $newJobTypeKoef * $this->tableData[$x]['unit_val'];
            $this->tableData[$x]['job_type'] = $insertNewJobType;
            //Inserting JOB
            DB::getInstance()->insertNewJobType($this->table, $this->tableData[$x]);
        }
    }
}
