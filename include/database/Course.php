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
            $q = $conn->query("SELECT C.cid, C.name FROM Courses AS C
                                         JOIN Student_Course AS SC
                                         ON C.cid=SC.cid
                                         WHERE SC.sid='" . $_SESSION['uid'] . "'");
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

    public static function delete($cid)
    {
        try {
            $conn = connect();
            Student_Course::delete_by_cid($cid);
            Assignment::delete_by_cid($cid);
            $q = $conn->prepare("DELETE FROM Courses WHERE cid=?");
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
}
?>