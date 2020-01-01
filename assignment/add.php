<?php
require_once "../include/database/Assignment.php";

if (isset($_POST['title']))
{
    //add new assignment
    if (Assignment::add($_POST['cid'], $_POST['title'], $_POST['deadline'], $_POST['contents'], $_POST['weights']))
    {
        echo "Succeeded to add assignment";
    }
    else
    {
        echo "Failed to add assignment";
    }
}
?>