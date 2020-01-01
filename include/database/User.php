<?php
require_once "db_connect.php";

class User
{
    public static function login($role, $uid, $pwd)
    {
        try {
            $conn = connect();
            switch ($role) {
                case 1:
                    $s = "SELECT COUNT(*) as N, tid, name FROM Teachers WHERE tid=? AND password=?";
                    break;
                case 2:
                    $s = "SELECT COUNT(*) as N, sid, name FROM Students WHERE sid=? AND password=?";
                    break;
                default:
                    $s = "";
                    break;
            }
            $q = $conn->prepare($s);
            $q->bindParam(1, $uid);
            $q->bindParam(2, $pwd);
            $q->execute();
            $row = $q->fetch();
            $conn = null;
            if ($row['N'] > 0) {
                $_SESSION['uid'] = $uid;
                $_SESSION['uname'] = $row['name'];
                $_SESSION['urole'] = $role;
                return true;
            }
            return false;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    private static function tid_exist($conn, $tid)
    {
        $q = $conn->prepare("SELECT COUNT(*) AS N FROM Teachers WHERE tid=?");
        $q->bindParam(1, $tid);
        $q->execute();
        $row = $q->fetch();
        if ($row['N'] > 0)
            return true;
        return false;
    }

    public static function register($uname, $pwd, $phone, $email, $office)
    {
        try {
            $conn = connect();
            while (true) {
                $tid = 'T' . substr(str_shuffle('0123456789'), 0, 6);
                if (!self::tid_exist($conn, $tid))
                    break;
            }

            //insert
            $q = $conn->prepare("INSERT INTO Teachers(tid, password, name, phone, email, office) VALUES (?, ?, ?, ?, ?, ?)");
            $q->bindParam(1, $tid);
            $q->bindParam(2, $pwd);
            $q->bindParam(3, $uname);
            $q->bindParam(4, $phone);
            $q->bindParam(5, $email);
            $q->bindParam(6, $office);
            $q->execute();
            $conn = null;
            $_SESSION['uid'] = $tid;
            $_SESSION['uname'] = $uname;
            $_SESSION['urole'] = 1;
            $_SESSION['login'] = true;
            return true;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function update($uid, $name, $pwd, $phone, $email, $office, $role)
    {
        try {
            $conn = connect();
            //update
            switch ($role) {
                case 1:
                    $s = "UPDATE Teachers SET name=?, password=?, phone=?, email=?, office=? WHERE tid=?";
                    break;
                case 2:
                    $s = "UPDATE Students SET name=?, password=?, phone=?, email=? WHERE sid=?";
                    break;
                default:
                    $s = "";
                    break;
            }
            $q = $conn->prepare($s);
            $q->bindParam(1, $name);
            $q->bindParam(2, $pwd);
            $q->bindParam(3, $phone);
            $q->bindParam(4, $email);
            if ($role == 1) {
                $q->bindParam(5, $office);
                $q->bindParam(6, $uid);
            }
            else
                $q->bindParam(5, $uid);
            $q->execute();
            $conn = null;
            $_SESSION['uname'] = $name;
            return true;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }

    public static function select_by_uid($uid, $role)
    {
        try {
            $conn = connect();
            switch ($role) {
                case 1:
                    $s = "SELECT * FROM Teachers WHERE tid=?";
                    break;
                case 2:
                    $s = "SELECT * FROM Students WHERE sid=?";
                    break;
                default:
                    $s = "";
                    break;
            }
            $q = $conn->prepare($s);
            $q->bindParam(1, $uid);
            $q->execute();
            $row = $q->fetch();
            $conn = null;
            return $row;
        }
        catch (PDOException $e)
        {
            $conn = null;
            return false;
        }
    }
}