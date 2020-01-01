<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'])
{
    //already login
    header("Location: ../index.php");
    die();
}
else if (isset($_POST['uid']) && isset($_POST['pwd']))
{
    //login post
    require_once "../include/database/User.php";
    if (User::login($_POST['role'], $_POST['uid'], $_POST['pwd']))
    {
        $_SESSION['login'] = true;
        header("Location: ../index.php");
        die();
    }
    else
    {
        ?>
        <html>
        <head></head>
        <body>
        <script>
            alert('Wrong user ID or password! ');
            location.reload();
        </script>
        </body>
        </html>
        <?php
    }

}
else {
    //login page
    ?>

    <html>
    <head>
        <title>Login</title>
        <script src="../script/jquery-3.4.1.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    </head>
    <body style="background-color: #9fcdff">
    <div class="container" style="background-color: white; margin-top: 20px; padding-top: 10px; padding-bottom: 10px; border-radius: 20px">
        <h2>Login</h2>
        <hr>
        <form method="post">
            <div class="form-group row">
                <label class="col-md-1 col-form-label" for="uid">User ID: </label>
                <div class="col-md-3">
                    <input id="uid" name="uid" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1 col-form-label" for="pwd">Password: </label>
                <div class="col-md-3">
                    <input id="pwd" name="pwd" type="password" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-1"></div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role1" value="1" required="required">
                    <label class="form-check-label" for="role1">Teacher</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role2" value="2">
                    <label class="form-check-label" for="role2">Student</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <a href="register.php">
                        <button type="button" class="btn btn-primary">Register</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
    </body>
    </html>
    <?php
}
?>