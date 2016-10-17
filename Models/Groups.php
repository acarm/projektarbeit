<?php
    class Groups
    {
        public $ID = "";
        public $Name;

        public static function FromDatabase($id)
        {
            include 'sql.php';
            $dbGroups = new Groups();
            $sql = "SELECT * FROM groups WHERE id = " . $id;
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $dbGroups = Groups::FromDbRow($row);

                    }
            }

            return $dbGroups;
        }

        static function FromDbRow($row)
        {
            $dbGroups = new Groups();
            $dbGroups->ID = $row['id'];
            $dbGroups->Name = $row['Name'];

            return $dbGroups;
        }

        public static function GroupsListView()
        {
            $view = file_get_contents("Views/Groups/RowContainer_View.html");
            $list = "";
            include 'sql.php';

            $sql = "SELECT * FROM groups";
            $result = $conn->query($sql);

            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $list .=  Groups::FromDbRow($row)->ToRowView();
                }
            }
            $view = str_replace("[listContent]", $list, $view);
            return $view;
        }

        function ToRowView()
        {
            $view = file_get_contents("Views/Groups/Row_View.html");
            $view = str_replace("[Id]", $this->ID, $view);
            $view = str_replace("[Name]", $this->Name, $view);

            return $view;
        }

        function ToView()
        {
            if($this->ID != 0)
                $view = file_get_contents("Views/Groups/Form_View.html");
            else
                $view = file_get_contents("Views/Groups/CreateForm_View.html");
            $view = str_replace("[id]", $this->ID, $view);
            $view = str_replace("[Name]", $this->Name, $view);

            include 'sql.php';
            if($this->ID != 0) {
                $sql = "SELECT id, FirstName, GroupID FROM user LEFT OUTER Join usergroups ON UserID = user.id AND GroupID LIKE " . $this->ID . " ORDER by FirstName";

                $result = $conn->query($sql);

                $list = "";
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $list .= user::FromDbRow($row)->ToCheckboxView($row["GroupID"] == $this->ID);
                    }
                }

                $view = str_replace("[listGroups]", $list, $view);
            }
            else
                $view = str_replace("[listGroups]", "", $view);
            return $view;
        }

        function ToCheckBoxRowView($isChecked)
        {
            $view = file_get_contents("Views/User/Checkbox_View.html");
            $view = str_replace("[GroupID]", $this->ID, $view);
            $view = str_replace("[GroupName]", $this->Name, $view);

            if($isChecked)
                $view = str_replace("[isChecked]", "checked", $view);
            else
                $view = str_replace("[isChecked]", "", $view);

            return $view;
        }

    }
?>