<?php

namespace Sthom\Admin;

class View
{

    public static function render(string $view, array $data = []): void
    {
        $data["view"] = $view;
        extract($data);
        ob_start();
        if ($view === "login") {
            require __DIR__ . "/../layout/views/login.php";
        } else {

            require __DIR__ . "/../layout/base.php";
        }
        $content = ob_get_clean();
        echo $content;
    }

}