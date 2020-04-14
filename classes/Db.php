<?php

require_once './config.php';

class DB
{
    private static $instance;
    private $pdo;

    private function __construct()
    {
        $dsn = 'mysql:host=' . HOST . ';dbname=' . DBNAME;
        try {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $this->pdo = new PDO($dsn, DBLOGIN, DBPASS, $options);
            // $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Error accured: ' . $e->getMessage() . BR;
            die();
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DB;
        }
        return self::$instance;
    }

    public function getDataByParams($column, $table, $columnValue)
    {
        $stmt = $this->pdo->prepare("SELECT $column FROM $table WHERE project = '$columnValue'");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    // public function hello(){
    //     return 'hello';
    // }

    public function getUnicValue()
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT project FROM jobs");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getAllByProjectName($projectName, $table)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE project = '$projectName'");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

    public function updateProjectEndTime($projectName, $table, $endTime)
    {
        $stmt = $this->pdo->prepare("UPDATE $table SET end_date='$endTime' WHERE project = '$projectName'");
        $stmt->execute();
    }

    public function updateProjectPoints($id, $table, $points)
    {
        $stmt = $this->pdo->prepare("UPDATE $table SET points='$points' WHERE id = '$id'");
        $stmt->execute();
    }

    public function insertNewJobType( $table, $data){
        $stmt = $this->pdo->prepare("INSERT INTO $table (project, job_type, hus_id, floor, room, no, unit_val, status, points, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['project'], $data['job_type'], $data['hus_id'], $data['floor'], $data['room'], $data['no'], $data['unit_val'], $data['status'], $data['points'], $data['date']]);
    }

    public function getAllFromTableByParams($table, $date, $project, $status)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE date > '$date' AND project = '$project' AND status = '$status'");
        $stmt->execute();
        $result = $stmt->fetchall();
        return $result;
    }

    public function getAllByProjectID($projectName, $table)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE project_id = '$projectName'");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    public function getAllColumnsByProjectName($projectName, $table)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE project = '$projectName'");
        $stmt->execute();
        $result = $stmt->fetchall();
        return $result;
    }

    public function delete (){
        $stmt = $this->pdo->prepare("DELETE from jobs where id > 42323");
        $stmt->execute();
    }
}
