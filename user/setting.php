<?php
session_start();
require_once "../include/database/User.php";
//update user information
if (isset($_POST['name']))
{
    if (User::update($_SESSION['uid'], $_POST['name'], $_POST['pwd'], $_POST['phone'], $_POST['email']))
    {
        echo "Succeeded to update user information";
    }
    else
    {
        echo "Failed to update user information";
    }
}