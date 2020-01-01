<?php
session_start();
require_once "../include/check_auth.php";
if (check_auth() != 1)
    end_page();

//list students who submitted assignment
require_once "../include/database/Student_Assignment.php";

function list_student($aid)
{
    $list_results = Student_Assignment::select_by_assignment($aid);
    ?>
    <div id="div_view_t_a" style="display: none; padding: 5px; background-color: blanchedalmond" data-sid="" data-aid=""></div>
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Mark</th>
            <th>Submit Date</th>
            <th>Operation</th>
        </tr>
        <?php
        foreach ($list_results as $row) {
            if ($row['submit_date'] == 'NULL') {
                //if not submitted, do not show
                continue;
            }
            echo '<tr>';
            echo '<td>' . $row['uid'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>'.$row['mark'].'</td>';
            echo '<td>'.$row['submit_date'].'</td>';
            echo '<td><button class="btn btn-primary" onclick="viewAssignment(\''.$aid.'\', \''.$row['uid'].'\')">View</button></td>';
            echo '</tr><br>';
        }
        ?>
    </table>
    <?php
}

function update_mark($sid, $aid, $mark, $qids, $qmarks, $comment)
{
    //update student's mark
    if (Student_Assignment::mark($sid, $aid, $mark, $qids, $qmarks, $comment))
        echo "Succeeded to set student's mark";
    else
        echo "Failed to set student's mark";
}

if ($_POST['mode'] == 1)
    list_student($_POST['aid']);

else if ($_POST['mode'] == 2)
    update_mark($_POST['sid'], $_POST['aid'], $_POST['mark'], $_POST['qids'], $_POST['qmarks'], $_POST['comment']);
