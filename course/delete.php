<?php
require_once "../include/database/Course.php";
if (isset($_POST['cid']))
{
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