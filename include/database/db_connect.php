<?php
session_start();

function connect()
{
    $dsn = "mysql:host=localhost;dbname=cs108";
    return new PDO($dsn, "cs108", "cs108_2019");
}
