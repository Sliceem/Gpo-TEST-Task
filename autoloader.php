<?php

spl_autoload_register(function($classname){
    require_once ('classes/'.$classname . ".php");
});