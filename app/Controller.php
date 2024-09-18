<?php

namespace App;

class Controller
{
    protected function render($resource, $view, $data = [])
    {
        session_status() === PHP_SESSION_NONE ? session_start() : "";

        extract($data);

        include "Views/main/header.php";
        include "Views/$resource/$view.php";
        include "Views/main/footer.php";
    }

    protected function paginate($array)
    {
        $postCount = count($array); //# of posts total
        $totalPages = ceil($postCount / 10); // number of pages, 10 per page

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        if ($currentPage < 1) {
            $currentPage = 1;
        } else if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * 10;
        $itemNum = min(10, $postCount - $offset);
        return array_slice($array, $offset, $itemNum);
    }

    protected function errorHandles($error)
    {
        //error is true or exists as an array (even with no posts)
        // if (is_array($error)) {
        //     return false;
        // }

        // if ($error === true) {
        //     return false;
        // }

        switch ($error) {
            case "sql-error":
                header('location: /error?e=db');
                break;
            case "page-error":
                header('location: /error?e=404');
                break;
            case "name-error-login":
                header('location: /users/login?e=name');
                break;
            case "password-error-login":
                header('location: /users/login?e=password');
                break;
            case "name-error-signup":
                header('location: /users/signup?e=name');
                break;
            case "password-error-signup":
                header('location: /users/signup?e=password');
                break;
            default:
                return false;
        }
    }
}