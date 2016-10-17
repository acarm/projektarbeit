<?php

if(isset($_GET['list']))
{
    $list = $_GET['list'];
    if($list == "groups")
    {
        returnGroups();
    }
    else if($list == "users")
    {
        returnUsers();
    }
}
else
{
    if(isset($_GET['accname']) && isset($_GET['pwd']))
    {
        $acc = $_GET['accname'];
        $hash = sha1($_GET['pwd']);
        authUser($acc,$hash);
    }
}



function returnUsers()
{
    include "sql.php";
    $sql = "SELECT * FROM user";
    $result = $conn->query($sql);

    $user = array();

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $user[$row['id']] = $row['FirstName'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($user);
}


function returnGroups()
{

    include "sql.php";
    $sql = "SELECT * FROM groups";
    $result = $conn->query($sql);

    $groups = array();

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $groups[$row['id']] = $row['Name'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($groups);
}

function authUser($acc, $hash)
{
    include "sql.php";
    include "Models/User.php";

    $sql = "SELECT * FROM user WHERE SamAccountname = '$acc' AND PasswordHash = '$hash'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $user = User::FromDbRow($row);
        }
    }

    $user->grouplist = array();

    $sql = "SELECT id, Name, UserID FROM groups LEFT OUTER Join usergroups ON GroupID = groups.id AND UserID LIKE " . $user->ID . " ORDER by Name";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($row["UserID"] == $user->ID)
            {
                $user->grouplist[$row["id"]] = $row["Name"];
            }

        }
    }

    if($user->SamAccountName != "")
    {
        $sql = "INSERT INTO log (`SamAccountname`)
            VALUES ('$user->SamAccountName')";
        $conn->query($sql);
    }

    header('Content-Type: application/json');
    echo json_encode($user);
}


