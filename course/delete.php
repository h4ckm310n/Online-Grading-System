<?php
require_once "../include/database/Course.php";
if (isset($_POST['cid']))
{
    //delete course
    if (Course::delete($_POST['cid']))
    {
        echo $_POST['cid'];
        echo "Succeeded to delete.";
    }
    else
    {
        echo "Failed to delete.";
    }
}
?>