<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="Styles/style.css" />

</head>
<body>
<?php

include "Models/MainController.php";
include "Models/Menu.php";
include "Models/user.php";
include "Models/groups.php";


$mainView = file_get_contents("Views/Main/MainView.html");

$controller = MainController::GetMainController($_GET["selectedValue"], $_GET["selectedID"]);

echo $controller->selectedID . " - " . $controller->selectedValue;

if($controller->selectedValue == "groups")
{
    $mainView = str_replace("[menu]", Menu::GetMenuView("groups"), $mainView);
    $mainView = str_replace("[Lists]", Groups::GroupsListView(), $mainView);

    if($controller->selectedID != 0)
    {
        $mainView = str_replace("[Forms]", Groups::FromDatabase($controller->selectedID)->ToView() ,$mainView);
    }
    else
    {
        $newGroups = new Groups();
        $mainView = str_replace("[Forms]", $newGroups->ToView(), $mainView);
    }

}

if($controller->selectedValue == "users")
{
    $mainView = str_replace("[menu]", Menu::GetMenuView("users"), $mainView);
    $mainView = str_replace("[Lists]", User::UserListView(), $mainView);

    if($controller->selectedID != 0)
    {
        $mainView = str_replace("[Forms]", User::FromDatabase($controller->selectedID)->ToView() ,$mainView);
    }
    else
    {
        $newUser = new User();
        $mainView = str_replace("[Forms]", $newUser->ToView(), $mainView);
    }

}


echo $mainView;

?>




</body>
</html> 