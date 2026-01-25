<?php

function getDatesExist(){
    $filesscan = scandir('reports/');
    for($i=0;$i<count($filesscan);$i++){
        if(!validateDate($filesscan[$i])){
            unset($filesscan[$i]);
        }
    }
    $filesscan = array_values($filesscan);
    return $filesscan;
}

function validateDate($date)
{
    $d = DateTime::createFromFormat('d.m.Y', $date);
    return $d && $d->format('d.m.Y') === $date;
}

function validateDateWithFormat($date)
{
    $d = DateTime::createFromFormat('d-M-Y', $date);
    return $d && $d->format('d-M-Y') === $date;
}

function getStartingDate(){
    $dates_array = getDatesExist();
    if(!empty($dates_array)){
        $startDate = date('d-M-Y',min(array_map('strtotime', $dates_array)));
        if($startDate!='01-Jan-1970'){
            return $startDate; 
        }else{
            return "";
        }
    }else{
        return "";
    }
}

function getEndingDate(){
    $dates_array = getDatesExist();
    if(!empty($dates_array)){
    $endDate = date('d-M-Y',max(array_map('strtotime', $dates_array)));
        if($endDate!='01-Jan-1970'){
            return $endDate; 
        }else{
            return "";
        }   
    }else{
        return "";
    }
    
}

?>
