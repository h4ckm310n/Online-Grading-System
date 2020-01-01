<?php
function check_auth()
{
    //get authority No.
    if (!isset($_SESSION['login']) && !$_SESSION['login'])
        return 0;
    if ($_SESSION['urole'] == 1)
        return 1;
    if ($_SESSION['urole'] == 2)
        return 2;
    return 0;
}

function end_page()
{
    //terminate
    echo "\nYou don't have the authority to visit this page.";
    die();
}
?>