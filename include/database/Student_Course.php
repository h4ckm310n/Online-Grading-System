<?php
require_once "db_connect.php";


class Student_Course
{
    public static function select_by_course($cid)
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT S.sid, S.name, SC.grade FROM Students AS S 
                                         JOIN Student_Course AS SC ON S.sid=SC.sid
                                         WHERE SC.cid='$cid' ORDER BY S.sid");
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

    public static function grades($sids, $cid, $grades)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("UPDATE Student_Course SET grade=? WHERE sid=? AND cid=?");
            $q->bindParam(1, $grade);
            $q->bindParam(2, $sid);
            $q->bindParam(3, $cid);
            for ($i=0; $i<count($sids); ++$i)
            {
                $sid = $sids[$i];
                $grade = $grades[$i];
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

    public static function delete($sid, $cid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("DELETE FROM Student_Course WHERE sid=? AND cid=?");
            $q->bindParam(1, $sid);
            $q->bindParam(2, $cid);
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
            $q = $conn->prepare("DELETE FROM Student_Course WHERE cid=?");
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

    public static function delete_by_sid($sid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("DELETE FROM Student_Course WHERE sid=?");
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

}