<?php
session_start();
if (isset($_POST['username']))
{
    require_once "../include/database/User.php";
    if (User::register($_POST['username'], $_POST['password'], $_POST['phone'], $_POST['email'], $_POST['office']))
    {
        echo "Your User ID is ".$_SESSION['uid'];
    }
    else
    {
        echo "Failed to register, please try again.";
    }
}
else {
    ?>
    <html>
    <head>
        <title>Teacher Registration</title>
        <script src="../script/jquery-3.4.1.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    </head>
    <body style="background-color: #9fcdff">
    <div class="container"
         style="background-color: white; margin-top: 20px; padding-top: 10px; padding-bottom: 10px; border-radius: 20px">
        <h2>Register</h2>
        <hr>
        <form method="post">
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="username">User Name: </label>
                <div class="col-md-3">
                    <input id="username" name="username" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="password">Password: </label>
                <div class="col-md-3">
                    <input id="password" name="password" type="password" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="phone">Phone Number: </label>
                <div class="col-md-3">
                    <input id="phone" name="phone" type="number" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="email">Email: </label>
                <div class="col-md-3">
                    <input id="email" name="email" type="email" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="office">Office: </label>
                <div class="col-md-3">
                    <input id="office" name="office" type="office" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <div class="col-md-1">
                    <button type="button" id="reg_btn" class="btn btn-primary">Confirm</button>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <a href="login.php">
                        <button type="button" class="btn btn-primary">Login</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('#reg_btn').click(function() {
            $.post("register.php",
                {
                    username: $('#username').val(),
                    password: $('#password').val(),
                    phone: $('#phone').val(),
                    email: $('#email').val(),
                    office: $('#office').val()
                },
                function(data, status)
                {
                    alert(data);
                    window.location.href = "../index.php";
                }
            )
        });
    </script>
    </body>
    </html>
    <?php
}
?>