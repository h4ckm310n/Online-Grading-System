<?php
session_start();
require_once "../include/check_auth.php";
if (check_auth() != 1)
    end_page();

//list all students
require_once "../include/database/Student.php";
$list_results = Student::select_all();
?>
<html>
<head>
    <title>Student List</title>
    <script src="../script/jquery-3.4.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body style="background-color: #9fcdff; padding-top: 70px">
<?php
require_once "../include/header.php";
display_header();
?>
<div class="container" style="background-color: white">
    <div style="padding-top: 3px; margin-bottom: 3px">
        <hr>
        <button class="btn btn-primary" data-toggle="modal" data-target="#add_student_modal">Add</button>
        <hr>
    </div>
    <table class="table table-striped">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Operation</th>
        </tr>
        <?php
        foreach ($list_results as $row)
        {
            ?>
            <tr>
                <td><?php echo $row['sid']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="delStudent('<?php echo $row['sid']; ?>')">Delete</button>
                </td>
            </tr>
            <?php

        }
        ?>
    </table>
</div>

<div class="modal fade" id="add_student_modal" data-backdrop="static">
    <div class="modal-dialog" style="background-color: white">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Student</h4>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="add_name">Student Name: </label>
                        <div class="col-md-5">
                            <input id="add_name" name="add_name" type="text" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="add_password">Password: </label>
                        <div class="col-md-5">
                            <input id="add_password" name="add_password" type="password" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="add_phone">Phone Number: </label>
                        <div class="col-md-5">
                            <input id="add_phone" name="add_phone" type="number" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="add_email">Email: </label>
                        <div class="col-md-5">
                            <input id="add_email" name="add_email" type="email" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <button type="button" id="add_post" class="btn btn-primary" onclick="addStudent()">Add</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    function addStudent() {
        //create student account
        $.post("add.php",
            {
                name: $('#add_name').val(),
                password: $('#add_password').val(),
                phone: $('#add_phone').val(),
                email: $('#add_email').val(),
            },
            function(data, status)
            {
                alert(data);
                location.reload();
            }
        );
    }

    function delStudent(sid) {
        //delete student account
        var flag = confirm("Confirm Delete");
        if (flag)
        {
            $.post("delete.php",
                {
                    sid: sid
                },
                function(data, status)
                {
                    alert(data);
                    location.reload();
                });
        }
    }
</script>
</body>
</html>
