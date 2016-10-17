<?php

class MainController
{
    public $selectedValue = "users";
    public $selectedID = 0;

    public static function GetMainController($value, $id)
    {
        if(!isset($value))
            $value = "users";
        $controller = new MainController();
        $controller->selectedValue = $value;
        $controller->selectedID = $id;

        return $controller;
    }
}