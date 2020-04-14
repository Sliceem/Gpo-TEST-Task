<?php 

require_once './classes/db.php';

function countProjectTime($project){
    $number = 0;
foreach($project as $result => $point){
    $number += $point['points'];
}
return $number / 60  / 8;
};

//-----------------------------------------
$column = 'points';
$table = 'jobs';
$uniq = DB::getInstance()->getUnicValue();
foreach($uniq as $un=>$iq){
    // echo $iq['project'] . BR;

    //GET uniq project NAME
    $columnValue = $iq['project'];
    $results = DB::getInstance()->getDataByParams($column, $table, $columnValue);
    //GET project DATA (human power, started date)
    $projectData = DB::getInstance()->getAllByProjectName($columnValue, 'projects');
    //Get workers for current project
    $workers = $projectData['humanpower'];
    //Get Project Start Time
    $projectStartDate = $projectData['start_date'];
    //Count days for project
    $daysForProject = ceil(number_format(countProjectTime($results), 2,'.','')/ $workers);
    $Total =  'PROJECT: '.$columnValue .' - '.'WORKERS: '. $workers . ' - Days for Project: ' .ceil(number_format(countProjectTime($results), 2,'.','')/ $workers)  .' - ' ;
    echo $Total;

    $days = strtotime($projectStartDate .'+'.$daysForProject.' weekday');
    $days = date( 'Y-m-d', $days );
    //Inserting to DB, Project END date
    $projectEndTime = DB::getInstance()->updateProjectEndTime($columnValue, 'projects', $days);
    echo 'Project Start Date: '.$projectStartDate.' Project End Date: '.$days;
    echo BR;
    echo '--------------------------------';
    echo BR;

}

echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\' . BR;

$jobs = DB::getInstance()->getAllFromTableByParams('jobs', '2020-03-01', 'SCHOOL1', 1);
$columnValue = 'SCHOOL1';
$projects = DB::getInstance()->getAllByProjectID($columnValue, 'jobsteps');

foreach($projects as $project => $currentProject){
    foreach($jobs as $job => $data){
        if ($currentProject['project_id'] == $data['project'] && $currentProject['job_type'] == $data['job_type']){
            $newPoints = number_format($currentProject['koef'] * $data['unit_val'], 2, '.', '');
            $id = $data['id'];
            DB::getInstance()->updateProjectPoints($id, $table, $newPoints);
        }
    }
}

$projectName = 'SCHOOL1';
$table = 'jobs';
$tableData = DB::getInstance()->getAllColumnsByProjectName($projectName, $table);
$insertNewJobType = ($projects[4]['job_type']);
$NewJobTypeKoef = ($projects[4]['koef']);
$count= 0;
for($x = 0; $x < count($tableData); $x+=4 ){
    // echo $tableData[$x]['id'] .' - '. $count ++. ' - '. count($tableData)/4 . BR;
    $tableData[$x]['points'] = $NewJobTypeKoef * $tableData[$x]['unit_val'];
    $tableData[$x]['job_type'] = $insertNewJobType;
    DB::getInstance()->insertNewJobType($table, $tableData[$x]);
}

// DB::getInstance()->delete();
