<?php
require_once "db_connect.php";
require_once "Question.php";
require_once "Student_Course.php";
require_once "Student_Assignment.php";

class Assignment
{
    public static function select_by_teacher($tid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT A.aid, A.title, C.cid, A.deadline FROM Assignments AS A 
                                         JOIN Courses AS C
                                         ON C.cid=A.cid
                                         JOIN Users AS U
                                         ON C.tid=U.uid
                                         WHERE U.uid='$tid'");
            $conn = null;
            return $q->fetchAll();
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function select_by_student($sid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT A.aid, A.title, A.cid, A.deadline, 
                                         IF(SA.submit_date IS NULL, '0', '1') AS submitted
                                         FROM Assignments AS A 
                                         JOIN Student_Assignment AS SA
                                         ON SA.aid=A.aid
                                         WHERE SA.sid='$sid'");
            $conn = null;
            return $q->fetchAll();
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function select_by_course($cid, $uid, $role)
    {
        try {
            $conn = connect();
            switch($role)
            {
                case 1:
                    $s = "SELECT A.aid, A.title, C.cid, A.deadline FROM Assignments AS A 
                          JOIN Courses AS C
                          ON C.cid=A.cid
                          WHERE C.cid='$cid'";
                    break;
                case 2:
                    $s = "SELECT A.aid, A.title, A.cid, A.deadline, 
                          IF(SA.submit_date IS NULL, '0', '1') AS submitted
                          FROM Assignments AS A 
                          JOIN Student_Assignment AS SA
                          ON SA.aid=A.aid
                          WHERE SA.sid='$uid' AND A.cid='$cid'";
            }
            $q = $conn->query($s);
            $conn = null;
            return $q->fetchAll();
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function aid_exist($conn, $aid)
    {
        $q = $conn->prepare("SELECT COUNT(*) AS N FROM Assignments WHERE aid=?");
        $q->bindParam(1, $aid);
        $q->execute();
        $row = $q->fetch();
        if ($row['N'] > 0)
            return true;
        return false;
    }

    public static function add($cid, $title, $deadline, $contents, $weights)
    {
        try {
            $conn = connect();
            while (true) {
                $aid = 'A' . substr(str_shuffle('0123456789'), 0, 6);
                if (!self::aid_exist($conn, $aid))
                    break;
            }

            //insert
            $q = $conn->prepare("INSERT INTO Assignments(aid, cid, title, deadline) VALUES (?, ?, ?, ?)");
            $q->bindParam(1, $aid);
            $q->bindParam(2, $cid);
            $q->bindParam(3, $title);
            $q->bindParam(4, $deadline);
            $q->execute();
            Student_Assignment::add_student_record($conn, $aid, $cid);
            Question::add_questions($conn, $aid, $contents, $weights);
            $conn = null;
            return true;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function delete($aid)
    {
        try {
            $conn = connect();
            Student_Assignment::delete_by_aid($conn, $aid);
            Question::delete_by_aid($conn, $aid);
            $q = $conn->prepare("DELETE FROM Assignments WHERE aid=?");
            $q->bindParam(1, $aid);
            $q->execute();
            $conn = null;
            return true;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function delete_by_cid($cid)
    {
        try {
            $conn = connect();
            $aids = self::select_by_course($cid);
            foreach ($aids as $a_rows)
            {
                Student_Assignment::delete_by_aid($conn, $a_rows['aid']);
                Question::delete_by_aid($conn, $a_rows['aid']);
            }
            $q = $conn->prepare("DELETE FROM Assignments WHERE cid=?");
            $q->bindParam(1, $cid);
            $q->execute();
            $conn = null;
            return true;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function update($aid, $title, $deadline)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("UPDATE Assignments SET title=?, deadline=? WHERE aid=?");
            $q->bindParam(1, $title);
            $q->bindParam(2, $deadline);
            $q->bindParam(3, $aid);
            $q->execute();
            $conn = null;
            return true;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }
}