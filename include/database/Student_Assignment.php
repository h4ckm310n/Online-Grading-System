<?php
require_once "db_connect.php";
require_once "Student_Course.php";
require_once "Student_Question.php";

class Student_Assignment
{
    public static function add_student_record($conn, $aid, $cid)
    {
        try {
            if ($conn == null)
                $conn = connect();
            $students = Student_Course::select_by_course($cid);
            $q = $conn->prepare("INSERT INTO Student_Assignment (sid, aid) VALUES (?, ?)");
            $q->bindParam(1, $sid);
            $q->bindParam(2, $aid);
            foreach ($students as $s_row)
            {
                $sid = $s_row['sid'];
                $q->execute();
            }
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function delete_by_sid($conn, $sid)
    {
        try {
            if ($conn == null)
                $conn = connect();
            Student_Question::delete_by_sid($conn, $sid);
            $q = $conn->prepare("DELETE FROM Student_Assignment WHERE sid=?");
            $q->bindParam(1, $sid);
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

    public static function delete_by_aid($conn, $aid)
    {
        try {
            if ($conn == null)
                $conn = connect();
            $q = $conn->prepare("DELETE FROM Student_Assignment WHERE aid=?");
            $q->bindParam(1, $aid);
            $q->execute();
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function select_by_assignment($aid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT S.sid, S.name, SA.mark, IFNULL(SA.submit_date, 'NULL') AS submit_date 
                                         FROM Students AS S 
                                         JOIN Student_Assignment AS SA ON S.sid=SA.sid
                                         WHERE SA.aid='$aid' ORDER BY S.sid");
            $rows = $q->fetchAll();
            $conn = null;
            return $rows;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function select_by_aid_sid($aid, $sid)
    {
        try
        {
            $conn = connect();
            $q = $conn->prepare("SELECT A.aid, A.title, A.deadline,
                                           SA.comment, SA.mark, SA.submit_date 
                                           FROM Assignments AS A 
                                           JOIN Student_Assignment AS SA 
                                           ON A.aid=SA.aid
                                           WHERE SA.aid=? AND SA.sid=?");
            $q->bindParam(1, $aid);
            $q->bindParam(2, $sid);
            $q->execute();
            return $q->fetch();
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function select_by_sid_cid($sid, $cid)
    {
        try
        {
            $conn = connect();
            $q = $conn->prepare("SELECT SA.aid, SA.mark, A.title
                                           FROM Student_Assignment AS SA 
                                           JOIN Assignments AS A 
                                           ON SA.aid=A.aid
                                           WHERE A.cid=? AND SA.sid=?");
            $q->bindParam(1, $cid);
            $q->bindParam(2, $sid);
            $q->execute();
            return $q->fetchAll();
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function update($aid, $sid, $qids, $answers, $date, $submit)
    {
        try
        {
            //update answers
            $conn = connect();
            Student_Question::update($conn, $aid, $sid, $qids, $answers);
            if ($submit == 1)
            {
                //submit
                $q = $conn->prepare("UPDATE Student_Assignment SET submit_date=? WHERE aid=? AND sid=?");
                $q->bindParam(1, $date);
                $q->bindParam(2, $aid);
                $q->bindParam(3, $sid);
                $q->execute();
            }
            $conn = null;
            return true;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function mark($sid, $aid, $mark, $qids, $qmarks, $comment)
    {
        //update student's mark
        try {
            $conn = connect();
            $q = $conn->prepare("UPDATE Student_Assignment SET mark=?, comment=? WHERE sid=? AND aid=?");
            $q->bindParam(1, $mark);
            $q->bindParam(2, $comment);
            $q->bindParam(3, $sid);
            $q->bindParam(4, $aid);
            $q->execute();
            Student_Question::marks($conn, $sid, $aid, $qids, $qmarks);
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