<?php
require_once "db_connect.php";
require_once "Student_Course.php";
require_once "Student_Question.php";

class Student_Assignment
{
    public static function add_student_record($conn, $aid)
    {
        try {
            if ($conn == null)
                $conn = connect();
            $q = $conn->prepare("INSERT INTO Student_Assignment (sid, aid)
                                           SELECT SC.sid, A.aid FROM Student_Course AS SC
                                           JOIN Assignments AS A ON SC.cid=A.cid
                                           WHERE A.aid=?");
            $q->bindParam(1, $aid);
            $r = $q->execute();
            return $r;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function select_by_assignment($aid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT U.uid, U.name, SA.mark, SA.submit_date
                                         FROM Users AS U 
                                         JOIN Student_Assignment AS SA ON U.uid=SA.sid
                                         WHERE SA.aid='$aid' AND SA.submit_date IS NOT NULL 
                                         ORDER BY U.uid");
            $rows = $q->fetchAll();
            $conn = null;
            return $rows;
        } catch (PDOException $e) {
            $conn = null;
            return false;
        }
    }

    public static function select_by_aid_sid($aid, $sid)
    {
        try {
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
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function select_by_sid_cid($sid, $cid)
    {
        try {
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
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function select_avg_by_sid($sid, $cid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT AVG(SA.mark) AS avg FROM Student_Assignment AS SA
                                         JOIN Assignments AS A ON SA.aid=A.aid
                                         WHERE A.cid='$cid' AND SA.sid='$sid'");
            $conn = null;
            return $q->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function select_avg($cid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT AVG(SA.mark) AS avg FROM Student_Assignment AS SA 
                                         JOIN Assignments AS A ON SA.aid=A.aid 
                                         WHERE A.cid='$cid' 
                                         GROUP BY(SA.sid)");
            $conn = null;
            return $q->fetchAll();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update($aid, $sid, $qids, $answers, $date, $submit)
    {
        try {
            //update answers
            $conn = connect();
            $r1 = Student_Question::update($conn, $aid, $sid, $qids, $answers);
            $r = true;
            if ($submit == 1) {
                //submit
                $q = $conn->prepare("UPDATE Student_Assignment SET submit_date=? WHERE aid=? AND sid=?");
                $q->bindParam(1, $date);
                $q->bindParam(2, $aid);
                $q->bindParam(3, $sid);
                $r = $q->execute();
            }
            $conn = null;
            return ($r1 && $r);
        } catch (PDOException $e) {
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
            $r = $q->execute();
            $r1 = Student_Question::marks($conn, $sid, $aid, $qids, $qmarks);
            $conn = null;
            return ($r && $r1);
        } catch (PDOException $e) {
            $conn = null;
            return false;
        }
    }

}