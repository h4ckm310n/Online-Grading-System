<?php
require_once "db_connect.php";
require_once "Student_Course.php";
require_once "Assignment.php";

class Course
{
    public static function select_by_teacher()
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT cid, name FROM Courses WHERE tid='" . $_SESSION['uid'] . "'");
            $conn = null;
            return $q->fetchAll();
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function select_by_student()
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT C.cid, C.name, U.name AS teacher, IF(SC.sid IS NULL, 0, 1) AS taken 
                                         FROM Courses AS C
                                         JOIN Users AS U ON C.tid=U.uid
                                         LEFT JOIN Student_Course AS SC ON C.cid=SC.cid
                                         AND SC.sid='" . $_SESSION['uid'] . "'");
            $conn = null;
            return $q->fetchAll();
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function add($cid, $name, $tid) {
        try {
            $conn = connect();
            //insert
            $q = $conn->prepare("INSERT INTO Courses(cid, name, tid) VALUES (?, ?, ?)");
            $q->bindParam(1, $cid);
            $q->bindParam(2, $name);
            $q->bindParam(3, $tid);
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

    public static function delete($cid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("DELETE FROM Courses WHERE cid=?");
            $q->bindParam(1, $cid);
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
?>