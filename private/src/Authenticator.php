<?php

namespace Sthom\Admin;



abstract class Authenticator
{
    public static function authenticate(string $username, string $password): bool
    {
        $config = json_decode(file_get_contents(__DIR__ . "/../../api.config.json"), true);
        $credentials = $config["admin"]["credentials"];
        if (trim($credentials["username"]) === $username && trim($credentials["password"]) === $password) {
            $_SESSION["authenticated"] = true;
            return true;
        }
        return false;
    }

    public static function check(): bool
    {
        return isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true;
    }
}