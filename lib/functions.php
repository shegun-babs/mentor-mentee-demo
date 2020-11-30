<?php

define('REG_MENTOR', 'mentor');
define('REG_MENTEE', 'mentee');

function getSpecializations()
{
    return [
        'specialists',
        'propriety knowledge',
        'generalists',
        'seniority',
        'management',
        'leadership',
        'executive management',
        'governance',
        'creative',
        'research',
        'relationships',
        'entrepreneur'
    ];
}


function insertToTable($table, array $data)
{
    require_once __DIR__ . '/db_config.php';
    try {
        $db = new DBManager();
        $return = $db->insert($table, $data) ? $db->insert_id : false;
        $db->close();
        return $return;
    } catch (Exception $err) {
        exit($err->getMessage());
    }
}


function mentorRules(){
    return [
        'fullname' => ['required'],
        'email' => ['required', 'email'],
        'specializations' => ['required'],
        'about' => ['required']
    ];
}


function menteeRules(){
    return [
        'fullname' => ['required'],
        'email' => ['required', 'email'],
        'mentors' => ['required'],
        'about' => ['required']
    ];
}


function mentors($fields='*', $table = 'mentors')
{
    require_once __DIR__ . '/db_config.php';
    $db = new DBManager();
    return $db->select($fields, $table);
}

function mentees($fields='*', $table='mentees'){
    require_once __DIR__ . '/db_config.php';
    $db = new DBManager();
    return $db->select($fields, $table);
}

function mentor_mentees($mentee_id){
    require_once __DIR__ . '/db_config.php';
    $db = new DBManager();
    $id = $db->escape($mentee_id);
    $query = "SELECT a.id as 'mentor_id', a.fullname as 'mentor_fullname', a.email as 'mentor_email', a.specializations as 'mentor_specializations', a.about as 'mentor_about', c.* 
                from mentors a 
                INNER JOIN mentees_mentor b ON a.id = b.mentor_id 
                INNER JOIN mentees c ON b.mentee_id = c.id WHERE c.id={$id}";
    $result = $db->query($query);
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

function show_error($validator, $field)
{
    if (!is_null($validator) && !empty($validator->hasError($field))) {
        return true;
    }
    return false;
}


function plural($var)
{
    return $var . "s";
}


function old($var)
{
    echo !empty($_POST[$var]) ? $_POST[$var] : '';
}