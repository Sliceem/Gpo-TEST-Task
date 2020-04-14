<?php

require_once 'autoloader.php';

//Count time for All projects
EndTime::countEndTime();

//Update Current project time
$pointsUpdate = new Recount();
$pointsUpdate->updatePoints();

//Adding new JOB to DB
$newJob = new AddProject();
$newJob->addingNewJob();
