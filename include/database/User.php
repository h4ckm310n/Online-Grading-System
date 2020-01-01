<?php
require_once "db_connect.php";

class User
{
    public static function login($role, $uid, $pwd)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("SELECT COUNT(*) as N, uid, name FROM Users WHERE uid=? AND password=? AND role=?");
            $q->bindParam(1, $uid);
            $q->bindParam(2, $pwd);
            $q->bindParam(3, $role);
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

    public static function update($uid, $name, $pwd, $phone, $email)
    {
        try {
            $conn = connect();
            //update
            $q = $conn->prepare("UPDATE Users SET name=?, password=?, phone=?, email=? WHERE uid=?");
            $q->bindParam(1, $name);
            $q->bindParam(2, $pwd);
            $q->bindParam(3, $phone);
            $q->bindParam(4, $email);
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

    public static function select_by_uid($uid)
    {
        try {
            $conn = connect();
            $q = $conn->prepare("SELECT * FROM Users WHERE uid=?");
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