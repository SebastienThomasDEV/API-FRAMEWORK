<?php

require __DIR__ . "/../vendor/autoload.php";

use Sthom\Admin\Router;
use Sthom\Admin\View;
use Sthom\Admin\Authenticator;
use Sthom\Back\Utils\ControllerReader;

session_start();
$router = new Router($_SERVER["REQUEST_URI"]);



$router->get("/", function () {
    if (Authenticator::check()) {
        $routes = ControllerReader::readRoutesBackOffice("app/controller");

        View::render("routes", ["routes" => $routes]);
    } else {
        header("Location: /login");
    }
});

$router->post("/login", function () {
    if (Authenticator::authenticate($_POST["username"], $_POST["password"])) {
        header("Location: /");
    } else {
        View::render("login", ["error" => "Invalid credentials"]);
    }
});

$router->get("/login", function () {
    View::render("login");
});

$router->get("/logout", function () {
    session_destroy();
    header("Location: /login");
});

$router->get("/authentication", function () {
    View::render("authentication");
});

$router->get("/log", function () {
    $log = file_get_contents(__DIR__ . "/../logs/error.txt");
    $lines = explode("\n", $log);
    $lines = array_reverse($lines);

    View::render("log", ["lines" => $lines]);
});

$router->get("/env", function () {
    $env = file_get_contents(__DIR__ . "/../.env");
    $lines = explode("\n", $env);
    $env = [];
    foreach ($lines as $line) {
        $data = explode("='", $line);
        if (count($data) === 2) {
            $env[$data[0]] = str_replace("\r", "",str_replace("'", "", $data[1]));
        }
    }
    View::render("env", ["env" => $env]);
});

$router->post("/env", function () {
    $env = "";
    foreach ($_POST as $key => $value) {
        $env .= "$key='$value'\n";
    }
    file_put_contents(__DIR__ . "/../.env", $env);
    header("Location: /env");
});
try {
    $router->run();
} catch (\Sthom\Admin\exceptions\RouterException $e) {
    echo $e->getMessage();
}