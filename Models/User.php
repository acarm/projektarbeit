<?php

class User
{
    public $ID = "";
    public $firstName;
    public $lastName;
    public $SamAccountName;
    public $passwordHash;
    public $grouplist;
 //test

    public static function FromDatabase($id)
    {
        include 'sql.php';

        $sql = "SELECT * FROM user WHERE id = ".$id;
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc())
            {
                $dbUser = User::FromDbRow($row);
            }
        }

        return $dbUser;
    }
	
    static function FromDbRow($row)
    {
        $dbUser = new User();
        $dbUser->ID = $row['id'];
        $dbUser->firstName = $row['FirstName'];
        $dbUser->lastName = $row['LastName'];
        if(isset($row['SamAccountname']))
        {
            $dbUser->SamAccountName = $row['SamAccountname'];
        }
        if(isset($row['PasswordHash']))
        {
            $dbUser->passwordHash = $row['PasswordHash'];
        }
        return $dbUser;
    }

    public static function UserListView()
    {
        $view = file_get_contents("Views/User/RowContainer_View.html");
        $list = "";
        include 'sql.php';

        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $list .=  User::FromDbRow($row)->ToRowView();
            }
        }
        $view = str_replace("[listContent]", $list, $view);
        return $view;
    }

    function ToRowView()
    {
        $view = file_get_contents("Views/User/Row_View.html");
        $view = str_replace("[Id]", $this->ID, $view);
        $view = str_replace("[lastName]", $this->lastName, $view);
        $view = str_replace("[Name]", $this->firstName, $view);

        return $view;
    }

    public static function ListGroupsViews()
    {
        $view = file_get_contents("Views/User/Checkbox_View.html");
        $list = "";
        include 'sql.php';

        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $list .=  User::FromDbRow($row)->ToCheckboxView();
            }
        }
        $view = str_replace("[listGroups]", $list, $view);
        return $view;
    }

    function ToCheckboxView($isChecked)
    {
        $view = file_get_contents("Views/Groups/Checkbox_View.html");
        $view = str_replace("[UserID]", $this->ID, $view);
        $view = str_replace("[UserName]", $this->firstName, $view);

        if($isChecked)
            $view = str_replace("[isChecked]", "checked", $view);
        else
            $view = str_replace("[isChecked]", "", $view);

        return $view;
    }

    function ToView()
    {
        if($this->ID != 0)
            $view = file_get_contents("Views/User/Form_View.html");
        else
            $view = file_get_contents("Views/User/Create_Form_View.html");
        $view = str_replace("[firstName]", $this->firstName, $view);
        $view = str_replace("[lastName]", $this->lastName, $view);
        $view = str_replace("[accName]", $this->SamAccountName, $view);
        $view = str_replace("[id]", $this->ID, $view);
        $view = str_replace("[hash]", $this->passwordHash, $view);


        include 'sql.php';
        if($this->ID != 0) {
            $sql = "SELECT id, Name, UserID FROM groups LEFT OUTER Join usergroups ON GroupID = groups.id AND UserID LIKE " . $this->ID . " ORDER by Name";

            $result = $conn->query($sql);

            $list = "";
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $list .= Groups::FromDbRow($row)->ToCheckBoxRowView($row["UserID"] == $this->ID);
                }
            }

            $view = str_replace("[listGroups]", $list, $view);
        }
        else
            $view = str_replace("[listGroups]", "", $view);
        return $view;
    }

}
