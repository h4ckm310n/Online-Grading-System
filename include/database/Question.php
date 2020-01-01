<?php
require_once "db_connect.php";
require_once "Student_Question.php";

class Question
{
    public static function add_questions($conn, $aid, $contents, $weights)
    {
        try
        {
            if ($conn == null)
                $conn = connect();
            $q = $conn->prepare("INSERT INTO Questions VALUES(?, ?, ?, ?)");
            $q->bindParam(1, $qid);
            $q->bindParam(2, $aid);
            $q->bindParam(3, $content);
            $q->bindParam(4, $weight);
            for ($i=0; $qid<count($contents); $i++)
            {
                $qid = $i + 1;
                $content = $contents[$i];
                $weight = $weights[$i];
                $q->execute();
                Student_Question::add_student_record($conn, $aid, $qid);
            }

        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public static function delete_by_aid($conn, $aid)
    {
        try
        {
            if ($conn == null)
                $conn = connect();
            Student_Question::delete_by_aid($conn, $aid);
            $q = $conn->prepare("DELETE FROM Questions WHERE aid=?");
            $q->bindParam(1, $aid);
            $q->execute();
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }
}