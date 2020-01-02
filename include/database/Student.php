<?php
require_once "db_connect.php";
require_once "Student_Course.php";

class Student
{
    public static function select_all()
    {
        try {
            $conn = connect();
            $q = $conn->query("SELECT * FROM Users WHERE role=2 ORDER BY uid");
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

    private static function sid_exist($conn, $sid)
    {
        $q = $conn->prepare("SELECT COUNT(*) AS N FROM Users WHERE uid=?");
        $q->bindParam(1, $sid);
        $q->execute();
        $row = $q->fetch();
        if ($row['N'] > 0)
            return true;
        return false;
    }

    public static function add($name, $pwd, $phone, $email)
    {
        try {
            $conn = connect();
            while (true) {
                $sid = 'S' . substr(str_shuffle('0123456789'), 0, 6);
                if (!self::sid_exist($conn, $sid))
                    break;
            }

            //insert
            $q = $conn->prepare("INSERT INTO Users(uid, password, name, phone, email, role) VALUES (?, ?, ?, ?, ?, 2)");
            $q->bindParam(1, $sid);
            $q->bindParam(2, $pwd);
            $q->bindParam(3, $name);
            $q->bindParam(4, $phone);
            $q->bindParam(5, $email);
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

    public static function delete($sid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("DELETE FROM Users WHERE uid=?");
            $q->bindParam(1, $sid);
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