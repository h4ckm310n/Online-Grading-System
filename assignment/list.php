<?php
session_start();
require_once "../include/check_auth.php";
if (check_auth() == 0)
    end_page();

//list assignments
require_once "../include/database/Assignment.php";
if (isset($_GET['cid']))
    //assignments of certain course
    $list_results = Assignment::select_by_course($_GET['cid'], $_SESSION['uid'], $_SESSION['urole']);
else if (check_auth() == 1)
    //assignments of all courses taught by current teacher account
    $list_results = Assignment::select_by_teacher($_SESSION['uid']);
else
    //assignments of all courses taken by current student account
    $list_results = Assignment::select_by_student($_SESSION['uid']);
?>
<html>
<head>
    <title>Assignment List</title>
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
        //if user is a teacher, show button to add assignment
        ?>
        <div style="padding-top: 3px; margin-bottom: 3px">
            <hr>
            <button class="btn btn-primary" onclick="addModal()">Add</button>
            <hr>
        </div>
        <?php
    }
    ?>
    <table class="table table-striped">
        <tr>
            <th>Assignment ID</th>
            <th>Course ID</th>
            <th>Title</th>
            <th>Deadline</th>
            <?php
                if (check_auth() == 2)
                {
                    //if user is a student, show whether the assignment is submitted
                    echo '<th>Submitted</th>';
                }
            ?>
            <th>Operation</th>
        </tr>
        <?php
        foreach ($list_results as $row) {
            ?>
            <tr id="tr_<?php echo $row['aid']; ?>">
                <td><?php echo $row['aid']; ?></td>
                <td><?php echo $row['cid']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['deadline']; ?></td>
                <?php
                if (check_auth() == 2)
                {
                    if ($row['submitted'] == 1)
                    {
                        //whether assignment is submitted
                        echo '<td><a style="color: cornflowerblue">YES</a></td>';
                        echo '<td>
                                <button type="button" class="btn btn-primary" 
                                onclick="viewAssignment(\''.$row['aid'].'\')">View
                                </button>
                              </td>';
                    }
                    else
                    {
                        echo '<td><a style="color: red">NO</a></td>';
                        echo '<td>
                                <button type="button" class="btn btn-primary" 
                                onclick="editAssignment(\''.$row['aid'].'\')">Edit</button>
                              </td>';
                    }

                }
                if (check_auth() == 1) {
                    //if user is a teacher, show button to list students who submitted assignment, and button to delete assignment
                    ?>
                    <td>
                        <button type="button" class="btn btn-primary"
                                onclick="lstStudent('<?php echo $row['aid'] ?>')">
                            Student
                        </button>
                        <button type="button" class="btn btn-danger"
                                onclick="delAssignment('<?php echo $row['aid'] ?>')">
                            Delete
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
    <div class="modal fade" id="add_assignment_modal" data-backdrop="static">
        <div class="modal-dialog" style="background-color: white">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Assignment</h4>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="add_title">Title: </label>
                            <div class="col-md-6">
                                <input id="add_title" name="add_title" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="add_deadline">Deadline: </label>
                            <div class="col-md-5">
                                <input id="add_deadline" name="add_deadline" type="date" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="add_cid">Course ID: </label>
                            <div class="col-md-5">
                                <input id="add_cid" name="add_cid" type="text" class="form-control" required="required"
                                    <?php
                                    //if exists the cid param
                                    if (isset($_GET['cid']))
                                        echo 'value="'.$_GET['cid'].'" readonly="readonly"'
                                    ?>>
                            </div>
                        </div>
                        <div id="add_a_q_container">
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <button type="button" id="add_q" class="btn btn-primary" onclick="addQuestion()">Add Question</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_post" class="btn btn-primary" onclick="addAssignment()">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="list_student" data-backdrop="static">
        <div class="modal-dialog modal-lg" style="background-color: white">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Students</h4>
                </div>
                <div class="modal-body" id="list_student_body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addQuestion() {
            //add more questions
            var new_q;
            var last_qid = $('.q_textarea:last').attr('name');
            if (last_qid == undefined)
                last_qid = 'q_content_0';
            var new_qid = 'q_content_' + String(Number(last_qid.replace('q_content_', '')) + 1);
            new_q = '<div class="form-group" id="' + new_qid.replace('q_content', 'div_q') +'">\
                        <label for="' + new_qid + '">Question Description: </label>\
                        <textarea class="form-control q_textarea" id="' + new_qid + '" \
                            name="' + new_qid + '" style="margin-bottom: 5px;" placeholder="Question Description" textarea>\
                        <div class="row container">\
                        <input class="form-control q_weight" type="number" name="' + new_qid.replace('q_content', 'weight') + '" \
                            style="margin-right: 5px; width: 100px" placeholder="Weight">\
                        <button type="button" class="btn btn-danger" onclick="delQuestion(\'' + new_qid + '\')">\
                          Delete\
                        </button>\
                        </div>\
                     </div>';
            $('#add_a_q_container').append(new_q);
        }

        function addModal() {
            //initialize the modal to add assignment
            $('#add_a_q_container').html('');
            for (var i=0; i<5; ++i)
                //5 questions by default
                addQuestion();
            $('#add_assignment_modal').modal('show');

        }

        function addAssignment() {
            //add assignment
            var contents = [];
            var weights = [];
            $('.q_textarea').each(
                function () {
                    contents.push($(this).val());
                }
            );
            $('.q_weight').each(
                function () {
                    weights.push($(this).val());
                }
            );
            $.post("add.php",
                {
                    cid: $('#add_cid').val(),
                    title: $('#add_title').val(),
                    deadline: $('#add_deadline').val(),
                    contents: contents,
                    weights: weights
                },
                function (data, status) {
                    alert(data);
                    location.reload();
                }
            );
        }

        function delAssignment(aid) {
            //delete assignment
            var flag = confirm("Confirm Delete");
            if (flag) {
                $.post("delete.php",
                    {
                        aid: aid
                    },
                    function (data, status) {
                        alert(data);
                        location.reload();
                    });
            }
        }

        function lstStudent(aid) {
            //list students who submitted assignment
            $.post("student.php",
                {
                    aid: aid,
                    mode: 1
                },
                function(data, status)
                {
                    $("#list_student_body").html(data);
                    $("#list_student").modal("show");
                }
            );
        }

        function delQuestion(qid) {
            //delete question
            var div_id = qid.replace('q_content', 'div_q');
            $('#' + div_id).remove();
        }

        function viewAssignment(aid, sid) {
            //view assignment of certain student
            var div = $('#div_view_t_a');
            div.slideUp();
            if (div.attr('data-sid') == sid && div.attr('data-aid') == aid) {
                div.attr('data-sid', '');
                div.attr('data-aid', '');
                return;
            }
            div.attr('data-sid', sid);
            div.attr('data-aid', aid);
            $.post('detail.php',
                {
                    mode: 3,
                    aid: aid,
                    sid: sid
                },
                function (data, status) {
                    div.html(data);
                    div.slideDown('slow');
                    calTotalMark();
                }
            );
        }

        function setStudentMark(aid, sid) {
            //set student's mark of certain assignment
            var qids = [];
            var qmarks = [];
            $('.q_mark_input').each(
                function () {
                    qids.push($(this).attr('name').replace('q_mark_', ''));
                    qmarks.push($(this).val());
                }
            );
            $.post("student.php",
                {
                    sid: sid,
                    mark: $('#total_mark_b').text(),
                    aid: aid,
                    qids: qids,
                    qmarks: qmarks,
                    comment: $('#assignment_comment_text').val(),
                    mode: 2
                },
                function(data, status)
                {
                    alert(data);
                    lstStudent(aid);
                }
            );
        }

        function hideAssignment() {
            //hide the student's assignment
            var div = $('#div_view_t_a');
            div.slideUp();
            div.html('');
        }

        function calTotalMark() {
            //calculate the mark of assignment by adding all marks of questions
            var total = 0;
            $('.q_mark_input').each(
                function () {
                    total += Number($(this).val());
                }
            );
            $('#total_mark_b').text(total);
        }

    </script>
    <?php
}

else if (check_auth() == 2)
{
    //student
    ?>
    <div class="modal fade" id="student_assignment_modal" data-backdrop="static">
        <div class="modal-dialog" style="background-color: white">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="s_a_h4"></h4>
                </div>
                <div class="modal-body" id="s_a_body"></div>
                <div class="modal-footer" id="s_a_footer">
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewAssignment(aid) {
            //if submitted, student can view the answer and mark of assignment
            $.post("detail.php",
                {
                    mode: 1,
                    aid: aid
                },
                function (data, status) {
                    $('#s_a_h4').text('View Assignment');
                    $('#s_a_body').html(data);
                    $('#s_a_footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
                    $('#student_assignment_modal').modal('show');
                }
            )
        }

        function editAssignment(aid) {
            //if not submitted, student can edit the answer of assignment
            $.post("detail.php",
                {
                    mode: 2,
                    aid: aid
                },
                function (data, status) {
                    var buttons = '<button type="button" class="btn btn-primary"\
                                        onclick="saveAssignment(\'' + aid + '\', false)">Save</button>\
                                   <button type="button" class="btn btn-primary"\
                                        onclick="saveAssignment(\'' + aid + '\', true)">Submit</button>\
                                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
                    $('#s_a_h4').text('Edit Assignment');
                    $('#s_a_body').html(data);
                    $('#s_a_footer').html(buttons);
                    $('#student_assignment_modal').modal('show');
                }
            )
        }

        function saveAssignment(aid, submit) {
            //save answers of assignment
            var date = new Date();
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var today = date.getFullYear()+"-"+(month)+"-"+(day) ;
            $('#submit_today').val(today);

            var qids = [];
            var answers = [];
            var flag;
            $('.ans_textarea').each( function () {
                    qids.push($(this).attr('id').replace('ans_txt_', ''));
                    answers.push($(this).val());
                }
            );
            if (submit == 1)
                //submit
                flag = confirm("Confirm to submit. You can not change your answers after submitted.");
            else
                flag = true;
            if (flag)
            {
                $.post("save.php",
                    {
                        aid: aid,
                        sid: '<?php echo $_SESSION['uid']; ?>',
                        qids: qids,
                        answers: answers,
                        date: $('#submit_today').val(),
                        mode: (submit ? 1 : 0)
                    },
                    function (data, status)
                    {
                        alert(data);
                        location.reload();
                    }
                );
            }
        }
    </script>
    <?php
}
?>
</body>
</html>
