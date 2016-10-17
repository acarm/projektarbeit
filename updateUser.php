<?php
include "sql.php";

$id = $_GET['ID'];
$firstName = $_GET['firstName'];
$lastName = $_GET["lastName"];
$accName = $_GET['SamAccountName'];

if(strlen($_GET['passwordHash']) == 40)
    $hash = $_GET['passwordHash'];
else
    $hash = sha1($_GET['passwordHash']);
echo $hash;

if(!isset($id))
{
    $sql = "INSERT INTO user (`FirstName`, `LastName`, `SamAccountname`, `PasswordHash`)
            VALUES ('$firstName', '$lastName', '$accName', '$hash')";
    header("location: index.php?selectedValue=users");
}
else if ($submit == "Entfernen")
{
    $sql = "DELETE FROM user WHERE id=" . $id;
    header("location: index.php?selectedValue=users");
}
else
{
    $groupIds = array();

    $sql = "SELECT id FROM groups";
    $result = $conn->query($sql);

    $list = "";
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            if(isset($_GET["GID".$row['id']]))
                $groupIds[$row['id']] =  $row['id'];
        }
    }

    $sql = "DELETE FROM usergroups WHERE UserID=" . $id . "; \r";
    foreach($groupIds AS $gid)
        $sql .= "INSERT INTO usergroups (UserID, GroupID) VALUES ($id, $gid)"."; \r";
    $sql .= "UPDATE user SET firstName='$firstName', lastName='$lastName', SamAccountname='$accName', PasswordHash='$hash'  WHERE id=" . $id .";";

    header("location: index.php?selectedValue=users&selectedID=".$id);
}

if ($conn->multi_query($sql) != TRUE)
{
    echo "Error: " . $conn->error;
}


$conn->close();
exit;
