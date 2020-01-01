<?php
require_once "../include/database/Student.php";

if (isset($_POST['name']))
{
    //create student account
    if (Student::add($_POST['name'], $_POST['password'], $_POST['phone'], $_POST['email']))
    {
        echo "Succeeded to add student";
    }
    else
    {
        echo "Failed to add student";
    }
}
?>
