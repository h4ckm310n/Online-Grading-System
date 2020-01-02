<?php
session_start();
require_once "../include/check_auth.php";
if (check_auth() == 0)
    end_page();

require_once "../include/database/Student_Course.php";
require_once "../include/database/Student_Assignment.php";

function list_student($cid)
{
    //list all students taking certain course

    $grade_enum = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'F', 'U'];
    $list_results = Student_Course::select_by_course($cid);
    $list_avg = Student_Assignment::select_avg($cid);
    ?>
    <div style="padding-top: 3px; margin-bottom: 3px">
        <hr>
        <div class="row">
            <div class="col-md-8">
                <input type="text" id="add_sid" name="add_sid" style="margin-right: 10px;">
                <button class="btn btn-primary" onclick="addStudent('<?php echo $cid; ?>')">Add
                </button>
            </div>
            <div class="col-md-1"></div>
            <div>
                <button class="btn btn-primary" onclick="updateGrade('<?php echo $cid; ?>')">Update Grades</button>
            </div>
        </div>
        <hr>
    </div>
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Grade</th>
            <th>Average</th>
            <th>Delete</th>
        </tr>
        <?php
        for ($i=0;$i<count($list_results); ++$i) {
        //foreach ($list_results as $row) {
            $row = $list_results[$i];
            echo '<tr>';
            echo '<td>' . $row['uid'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td><select name="grade_'.$row['uid'].'">';
            foreach ($grade_enum as $g)
            {
                if ($g == $row['grade'])
                    //default option is the grade of student
                    $option_selected = " selected";
                else
                    $option_selected = "";
                echo '<option value="'.$g.'"'.$option_selected.'>'.$g.'</option>';
            }
            echo '</select></td>';
            echo '<td>'.$list_avg[$i]['avg'].'</td>';
            echo '<td>
                <button type="button" class="btn btn-danger"
                        onclick="delStudent(\'' . $row['uid'] . '\',\'' . $cid . '\')">Delete</button>
              </td>';
            echo '</tr>';
        }
        ?>
    </table>
    <?php
}

function add_student($sid, $cid, $by)
{
    //add student to course
    $s = $by == 1 ? "add student" : "enroll course";
    if (Student_Course::add($sid, $cid))
        echo "Succeeded to ".$s;
    else
        echo "Failed to ".$s;
}

function del_student($sid, $cid)
{
    //delete student from course
    if (Student_Course::delete($sid, $cid))
        echo "Succeeded to delete student";
    else
        echo "Failed to delete student";
}

function update_grades($sids, $cid, $grades)
{
    //update all grades of students who take certain course
    if (Student_Course::grades($sids, $cid, $grades))
        echo "Succeeded to set students' grades";
    else
        echo "Failed to set students' grades";
}

function view_grade($sid, $cid)
{
    //view self's grade of certain course, and mark of each assignment

    $sc = Student_Course::select_by_sid_cid($sid, $cid);
    $sa = Student_Assignment::select_by_sid_cid($sid, $cid);
    $avg = Student_Assignment::select_avg_by_sid($sid, $cid)['avg'];
    echo '<div>
            <h4>'.$cid.'     '.$sc['name'].'</h4>
            <br><a>Grade: '.$sc['grade'].'</a>
            <br><a>Average Mark: '.$avg.'</a>
         </div><hr>';
    ?>
    <table class="table table-striped">
        <tr>
            <th>Assignment</th>
            <th>Mark</th>
        </tr>
        <?php
        foreach ($sa as $row) {
            echo '<tr>
                    <td>'.$row['title'].'</td>
                    <td>'.$row['mark'].'</td>
                  </tr>';
        }
        ?>
    </table>
    <?php
}

if ($_POST['mode'] == 1)
    list_student($_POST['cid']);

else if ($_POST['mode'] == 2)
    add_student($_POST['sid'], $_POST['cid'], $_POST['by']);

else if ($_POST['mode'] == 3)
    del_student($_POST['sid'], $_POST['cid']);

else if ($_POST['mode'] == 4)
    update_grades($_POST['sids'], $_POST['cid'], $_POST['grades']);

else if ($_POST['mode'] == 5)
    view_grade($_POST['sid'], $_POST['cid']);