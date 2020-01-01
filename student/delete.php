<?php
require_once "../include/database/Student.php";
if (isset($_POST['sid']))
{
    //delete student account
    if (Student::delete($_POST['sid']))
    {
        echo "Succeeded to delete.";
    }
    else
    {
        echo "Failed to delete.";
    }
}
?>