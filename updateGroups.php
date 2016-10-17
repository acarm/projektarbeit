<?php
include "sql.php";

$id = $_GET['id'];
$newName = $_GET['name'];
$submit = $_GET['submit'];

if(!isset($id))
{
    $sql = "INSERT INTO groups (`Name`) VALUE ('$newName')";
    header("location: index.php?selectedValue=groups");
}
else if ($submit == "Entfernen")
{
    $sql = "DELETE  FROM groups WHERE id=" . $id;
    header("location: index.php?selectedValue=groups");
}

else
{
    $userIds = array();

    $sql = "SELECT id FROM user";
    $result = $conn->query($sql);

    $list = "";
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            if(isset($_GET["UID".$row['id']]))
                $userIds[$row['id']] =  $row['id'];
        }
    }

    $sql = "DELETE FROM usergroups WHERE GroupID=" . $id . "; \r";
    foreach($userIds AS $uid)
        $sql .= "INSERT INTO usergroups (UserID, GroupID) VALUES ($uid, $id)"."; \r";
    $sql .= "UPDATE groups SET name='$newName'  WHERE id=" . $id .";";

    header("location: index.php?selectedValue=groups&selectedID=".$id);
}


if ($conn->multi_query($sql) != TRUE)
{
    echo "Error: " . $conn->error;
}

$conn->close();
exit;


