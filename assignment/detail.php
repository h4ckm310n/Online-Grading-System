<?php
session_start();
require_once "../include/database/Student_Question.php";
require_once "../include/database/Student_Assignment.php";

function student_edit($aid)
{
    $sa = Student_Assignment::select_by_aid_sid($aid, $_SESSION['uid']);
    $sq = Student_Question::select_by_aid_sid($aid, $_SESSION['uid']);
    ?>
    <div>
        <h4><?php echo $sa['title']; ?></h4>
        <div class="row container">
            <a>Deadline: <?php echo $sa['deadline']; ?></a>
        </div>
        <hr>
    </div>
    <form method="post">
        <div>
            <?php
            foreach ($sq as $row)
            {
                echo '<div class="form-group">
                        <b>(Weight: '.$row['weight'].')</b><br>
                        <pre style="font-size: 17px">'.$row['content'].'</pre>
                        <textarea rows=4 id="ans_txt_'.$row['qid'].'" class="form-control ans_textarea">'.$row['answer'].'</textarea>
                      </div><br><br>';
            }
            ?>
        </div>
        <input type="date" hidden="hidden" id="submit_today">
    </form>

    <?php
}

function student_view($aid)
{
    $sa = Student_Assignment::select_by_aid_sid($aid, $_SESSION['uid']);
    $sq = Student_Question::select_by_aid_sid($aid, $_SESSION['uid']);
    ?>
    <div>
        <h4><?php echo $sa['title']; ?></h4>
        <div>
            <a class="col-md-6">Submit Date: <?php echo $sa['submit_date']; ?></a><br>
            <b class="col-md-6">Total Mark: <?php echo $sa['mark']; ?></b>
        </div>
        <hr>
        <div>
            <pre><?php echo $sa['comment']; ?></pre>
        </div>
    </div>
    <div>
        <?php
        foreach ($sq as $row)
        {
            echo '<div>
                    <pre style="font-size: 16px">Q:
'.$row['content'].'</pre>
                    <pre style="font-size: 15px; color: red">A:
'.$row['answer'].'</pre>
                    <a>Mark: '.$row['mark'].'</a>
                  </div><hr>';
        }
        ?>
    </div>
    <?php
}

function teacher_student($aid, $sid)
{
    $sa = Student_Assignment::select_by_aid_sid($aid, $sid);
    $sq = Student_Question::select_by_aid_sid($aid, $sid);
    ?>
    <div>
        <div>
            <h4><?php echo $sa['title']; ?></h4>
            <div style="padding: 3px">
                <a style="padding: 2px;">Student ID: <?php echo $sid; ?></a><br>
                <a style="padding: 2px;">Submit Date: <?php echo $sa['submit_date']; ?></a><br>
                <b>Total Mark: </b>
                <b id="total_mark_b"></b>
            </div>
            <button type="button" class="btn btn-secondary" onclick="hideAssignment()">Cancel</button>
            <hr>
        </div>
        <form>
            <?php
            foreach ($sq as $row)
            {
                echo '<div>
                    <pre style="font-size: 16px">Q:
'.$row['content'].'</pre>
                    <pre style="font-size: 15px; color: red">A:
'.$row['answer'].'</pre>
                    <label for="q_mark_input_'.$row['qid'].'">Mark:</label>
                    <input type="number" class="form-control q_mark_input" id="q_mark_input_'.$row['qid'].'" 
                      name="q_mark_'.$row['qid'].'" value="'.$row['mark'].'" onchange="calTotalMark()">
                  </div><hr>';
            }
            echo '<button type="button" class="btn btn-primary" onclick="setStudentMark(\''.$aid.'\', \''.$sid.'\')">Update Mark</button>'
            ?>
        </form>
    </div>
    <?php

}

if ($_POST['mode'] == 1)
    student_view($_POST['aid']);

else if ($_POST['mode'] == 2)
    student_edit($_POST['aid']);

else if ($_POST['mode'] == 3)
    teacher_student($_POST['aid'], $_POST['sid']);