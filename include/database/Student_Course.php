<?php
require_once "db_connect.php";
require_once "Student_Assignment.php";
require_once "Assignment.php";

class Student_Course
{
    public static function select_by_course($cid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT U.uid, U.name, SC.grade FROM Users AS U 
                                         JOIN Student_Course AS SC ON U.uid=SC.sid
                                         WHERE SC.cid='$cid' ORDER BY U.uid");
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

    public static function select_by_sid_cid($sid, $cid)
    {
        try
        {
            $conn = connect();
            $q = $conn->query("SELECT SC.sid, SC.cid, SC.grade, C.name
                                         FROM Student_Course AS SC 
                                         JOIN Courses AS C 
                                         ON SC.cid=C.cid
                                         WHERE SC.cid='$cid' AND SC.sid='$sid'");
            $conn = null;
            return $q->fetch();
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function add($sid, $cid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("INSERT INTO Student_Course (sid, cid) VALUES (?, ?)");
            $q->bindParam(1, $sid);
            $q->bindParam(2, $cid);
            $r = $q->execute();
            $conn = null;
            return $r;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function grades($sids, $cid, $grades)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("UPDATE Student_Course SET grade=? WHERE sid=? AND cid=?");
            $q->bindParam(1, $grade);
            $q->bindParam(2, $sid);
            $q->bindParam(3, $cid);
            $r = true;
            for ($i=0; $i<count($sids); ++$i)
            {
                $sid = $sids[$i];
                $grade = $grades[$i];
                $r = $q->execute();
            }
            $conn = null;
            return $r;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function delete($sid, $cid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("DELETE SC, SA, SQ FROM Student_Course AS SC
                                           JOIN Assignments AS A ON SC.cid=A.cid
                                           JOIN Student_Assignment AS SA ON A.aid=SA.aid AND SC.sid=SA.sid
                                           JOIN Student_Question AS SQ ON SQ.aid=SA.aid AND SC.sid=SQ.sid 
                                           WHERE SC.sid=? AND SC.cid=?");
            $q->bindParam(1, $sid);
            $q->bindParam(2, $cid);
            $r = $q->execute();
            $conn = null;
            return $r;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

}