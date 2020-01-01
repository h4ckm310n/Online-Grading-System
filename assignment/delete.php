<?php
require_once "../include/database/Assignment.php";
if (isset($_POST['aid']))
{
    if (Assignment::delete($_POST['aid']))
    {
        echo "Succeeded to delete.";
    }
    else
    {
        echo "Failed to delete.";
    }
}
?>