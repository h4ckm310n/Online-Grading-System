<?php
require_once "db_connect.php";
require_once "Student_Assignment.php";

class Student_Question
{
    public static function add_student_record($conn, $aid, $qid)
    {
        try
        {
            if ($conn == null)
                $conn = connect();
            $s_rows = Student_Assignment::select_by_assignment($aid);
            $q = $conn->prepare("INSERT INTO Student_Question(sid, qid, aid) VALUES(?, ?, ?)");
            $q->bindParam(1, $sid);
            $q->bindParam(2, $qid);
            $q->bindParam(3, $aid);
            foreach ($s_rows as $row)
            {
                $sid = $row['uid'];
                $q->execute();
            }
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function delete_by_aid($conn, $aid)
    {
        try {
            if ($conn == null)
                $conn = connect();
            $q = $conn->prepare("DELETE FROM Student_Question WHERE aid=?");
            $q->bindParam(1, $aid);
            $q->execute();
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
            $q = $conn->prepare("DELETE FROM Student_Question WHERE sid=?");
            $q->bindParam(1, $sid);
            $q->execute();
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function select_by_aid_sid($aid, $sid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("SELECT Q.qid, Q.content, Q.weight, SQ.answer, SQ.mark FROM Questions AS Q
                                           JOIN Student_Question AS SQ
                                           ON Q.qid=SQ.qid AND Q.aid=SQ.aid
                                           WHERE Q.aid=? AND SQ.sid=?");
            $q->bindParam(1, $aid);
            $q->bindParam(2, $sid);
            $q->execute();
            $conn = null;
            return $q->fetchAll();
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function update($conn, $aid, $sid, $qids, $answers)
    {
        try
        {
            if ($conn == null)
                $conn = connect();
            $q = $conn->prepare("UPDATE Student_Question SET answer=? WHERE qid=? AND aid=? AND sid=?");
            $q->bindParam(1, $ans);
            $q->bindParam(2, $qid);
            $q->bindParam(3, $aid);
            $q->bindParam(4, $sid);
            for ($i=0; $i<count($qids); ++$i)
            {
                $qid = $qids[$i];
                $ans = $answers[$i];
                $q->execute();
            }
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function marks($conn, $sid, $aid, $qids, $qmarks)
    {
        try
        {
            if ($conn == null)
                $conn = connect();
            $q = $conn->prepare("UPDATE Student_Question SET mark=? WHERE sid=? AND aid=? AND qid=?");
            $q->bindParam(1, $mark);
            $q->bindParam(2, $sid);
            $q->bindParam(3, $aid);
            $q->bindParam(4, $qid);
            for ($i=0; $i<count($qids); ++$i)
            {
                $qid = $qids[$i];
                $mark = $qmarks[$i];
                $q->execute();
            }
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }
}