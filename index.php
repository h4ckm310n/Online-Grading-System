<?php
session_start();
if (!isset($_SESSION['login']) || !$_SESSION['login'])
{
    // redirect to login page
    header("Location: user/login.php");
    die();
}
require_once "include/check_auth.php";
?>
<html>
<head>
    <title>Online Grading System</title>
    <script src="../script/jquery-3.4.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body style="background-color: #9fcdff; padding-top: 70px">
<?php
require_once "include/header.php";
display_header();
?>
<div class="container" style="background-color: white">
    <div class="card">
        <div class="card-body">
            <h4>CS108 Project of Pan Wenxi</h4>
            <hr>
            <br>
            <pre style="font-size: 20px;">
Hello, <?php echo (check_auth() == 1 ? "Teacher" : "Student")." <b>".$_SESSION['uname']."</b>"; ?>.
Your User ID is <b><?php echo $_SESSION['uid'] ?></b>.
This is the index page.
Click the links of the navigation bar on the top to
do more operation.
            </pre>
        </div>
    </div>
</div>
</body>
</html>