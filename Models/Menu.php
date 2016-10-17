<?php
    class Menu
    {
        public static function GetMenuView($value)
        {
            $view = file_get_contents("Views/Menu/MenuView.html");
            if($value == "groups")
            {
                $view = str_replace("[g]", "<b>", $view);
                $view = str_replace("[/g]", "</b>", $view);
                $view = str_replace("[b]", "", $view);
                $view = str_replace("[/b]", "", $view);
            }
            else
            {
                $view = str_replace("[b]", "<b>", $view);
                $view = str_replace("[/b]", "</b>", $view);
                $view = str_replace("[g]", "", $view);
                $view = str_replace("[/g]", "", $view);
            }

            return $view;
        }
    }


?>