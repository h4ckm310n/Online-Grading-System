<?php
require_once "../include/database/Course.php";

if (isset($_POST['name']))
{
    if (Course::add($_POST['id'], $_POST['name'], $_POST['tid']))
    {
        echo "Succeeded to add course";
    }
    else
    {
        echo "Failed to add course";
    }
}
?>