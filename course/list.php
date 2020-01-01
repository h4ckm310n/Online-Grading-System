<?php
session_start();
require_once "../include/check_auth.php";
if (check_auth() == 0)
    end_page();

//list courses
require_once "../include/database/Course.php";

if (check_auth() == 1)
    //courses taught by current teacher account
    $list_results = Course::select_by_teacher();
else
    //courses taken by current student account
    $list_results = Course::select_by_student();
?>
<html>
<head>
    <title>Course List</title>
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
    <?php
    if (check_auth() == 1) {
        //if user is a teacher, show a button to add new course
        ?>
        <div style="padding-top: 3px; margin-bottom: 3px">
            <hr>
            <button class="btn btn-primary" data-toggle="modal" data-target="#add_course_modal">Add</button>
            <hr>
        </div>
        <?php
    }
    ?>
    <table class="table table-striped">
        <tr>
            <th>Course ID</th>
            <th>Name</th>
            <th>Operation</th>
        </tr>
        <?php
        foreach ($list_results as $row) {
            ?>
            <tr id="tr_<?php echo $row['cid']; ?>">
                <td><?php echo $row['cid']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <?php
                if (check_auth() == 1) {
                    //teacher
                    ?>
                    <td>
                        <a href="../assignment/list.php?cid=<?php echo $row['cid'] ?>" target="_blank">
                            <button type="button" class="btn btn-primary">Assignment</button>
                        </a>
                        <button type="button" class="btn btn-primary"
                                onclick="lstStudent('<?php echo $row['cid'] ?>')">
                            Student
                        </button>
                        <button type="button" class="btn btn-danger"
                                onclick="delCourse('<?php echo $row['cid'] ?>')">
                            Delete
                        </button>
                    </td>
                    <?php
                } else {
                    //student
                    ?>
                    <td>
                        <a href="../assignment/list.php?cid=<?php echo $row['cid'] ?>" target="_blank">
                            <button type="button" class="btn btn-primary">Assignment</button>
                        </a>
                        <button type="button" class="btn btn-primary"
                                onclick="viewGrade('<?php echo $_SESSION['uid']; ?>', '<?php echo $row['cid']; ?>')">
                            Grade
                        </button>
                    </td>

                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
    </table>
</div>




<?php
if (check_auth() == 1) {
    //teacher
    ?>
    <div class="modal fade" id="add_course_modal" data-backdrop="static">
        <div class="modal-dialog" style="background-color: white">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Course</h4>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="add_id">Course ID: </label>
                            <div class="col-md-5">
                                <input id="add_id" name="add_id" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="add_name">Course Name: </label>
                            <div class="col-md-5">
                                <input id="add_name" name="add_name" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <button type="button" id="add_post" class="btn btn-primary" onclick="addCourse()">Add</button>
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

    <div class="modal fade" id="list_student">
        <div class="modal-dialog modal-lg" style="background-color: white">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Students</h4>
                </div>
                <div class="modal-body" id="list_student_body">
                </div>
            </div>
        </div>
    </div>

    <script>
        function addCourse() {
            //add new course
            $.post("add.php",
                {
                    id: $('#add_id').val(),
                    name: $('#add_name').val(),
                    tid: '<?php echo $_SESSION['uid']; ?>'
                },
                function (data, status) {
                    alert(data);
                    location.reload();
                }
            );
        }

        function delCourse(cid) {
            //delete course
            var flag = confirm("Confirm Delete");
            if (flag) {
                $.post("delete.php",
                    {
                        cid: cid
                    },
                    function (data, status) {
                        alert(data);
                        location.reload();
                    });
            }
        }

        function lstStudent(cid) {
            //list students who take certain course
            $.post("student.php",
                {
                    cid: cid,
                    mode: 1
                },
                function(data, status)
                {
                    $("#list_student_body").html(data);
                    $("#list_student").modal("show");
                }
            );
        }

        function addStudent(cid) {
            //add student to certain course
            $.post("student.php",
                {
                    cid: cid,
                    sid: $("#add_sid").val(),
                    mode: 2
                },
                function(data, status)
                {
                    alert(data);
                    lstStudent(cid);
                }
            );
        }

        function delStudent(sid, cid) {
            //delete student from certain course
            var flag = confirm("Confirm Delete");
            if (flag) {
                $.post("student.php",
                    {
                        cid: cid,
                        sid: sid,
                        mode: 3
                    },
                    function (data, status) {
                        alert(data);
                        lstStudent(cid);
                });
            }
        }

        function updateGrade(cid) {
            //update grades of all students having certain course
            var all_sid = [];
            var all_grade = [];
            $("select[name^='grade_']").each(
                function () {
                    all_sid.push($(this).attr('name').replace('grade_', ''));
                    all_grade.push($(this).val());
                }
            );
            $.post("student.php",
                    {
                        sids: all_sid,
                        grades: all_grade,
                        cid: cid,
                        mode: 4
                    },
                    function(data, status)
                    {
                        alert(data);
                    }
            );
        }
    </script>
    <?php
}

else
{
    //student
    ?>
    <div class="modal fade" id="grade_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Grade</h4>
                </div>
                <div class="modal-body" id="grade_modal_body"></div>
            </div>
        </div>
    </div>

    <script>
        function viewGrade(sid, cid) {
            //view grade of certain course and mark of each assignment
            $.post("student.php",
                {
                    mode: 5,
                    sid: sid,
                    cid: cid
                },
                function (data, status) {
                    $('#grade_modal_body').html(data);
                    $('#grade_modal').modal('show');
                }
            );
        }
    </script>
    <?php
}
?>
</body>
</html>
