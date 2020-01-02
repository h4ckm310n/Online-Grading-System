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
            $r = true;
            for ($i=0; $qid<count($contents); $i++)
            {
                $qid = $i + 1;
                $content = $contents[$i];
                $weight = $weights[$i];
                $r = $q->execute();
            }
            $r2 = Student_Question::add_student_record($conn, $aid);
            return ($r && $r2);
        }
        catch (PDOException $e)
        {
            return false;
        }
    }
}
