<?php

require_once './autoloader.php';

class EndTime
{
    public static $column;
    public static $table;
    public static $uniq;


    public function countProjectTime($projectName)
    {
        $number = 0;
        foreach ($projectName as $result => $point) {
            $number += $point['points'];
        }
        return $number / 60  / 8;
    }

    public static function countEndTime()
    {
        self::$column = 'points';
        self::$table = 'jobs';
        self::$uniq = DB::getInstance()->getUnicValue();

        foreach (self::$uniq as $un => $iq) {
        }
        //GET uniq project NAME
        $columnValue = $iq['project'];
        $results = DB::getInstance()->getDataByParams(self::$column, self::$table, $columnValue);
        //GET project DATA (human power, started date)
        $projectData = DB::getInstance()->getAllByProjectName($columnValue, 'projects');
        //Get workers for current project
        $workers = $projectData['humanpower'];
        //Get Project Start Time
        $projectStartDate = $projectData['start_date'];
        //Count days for project
        $daysForProject = ceil(number_format(self::countProjectTime($results), 2, '.', '') / $workers);
        //Converts Days to EndDateTime dateFormat (including weekdays)
        $days = strtotime($projectStartDate . '+' . $daysForProject . ' weekday');
        $days = date('Y-m-d', $days);
        //Inserting to DB, Project END date
        DB::getInstance()->updateProjectEndTime($columnValue, 'projects', $days);
    }
}
