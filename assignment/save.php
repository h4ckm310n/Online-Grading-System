<?php
session_start();
require_once "../include/database/Student_Assignment.php";

if (isset($_POST['mode']))
{
    $s = $_POST['mode'] == 1 ? 'submit' : 'save';
    if (Student_Assignment::update($_POST['aid'], $_POST['sid'], $_POST['qids'], $_POST['answers'], $_POST['date'], $_POST['mode']))
    {
        echo 'Succeeded to '.$s;
    }
    else
    {
        echo 'Failed to '.$s;
    }
}