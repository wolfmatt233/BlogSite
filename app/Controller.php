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
        if (!isset($error['type'])) {
            return false;
        }

        switch ($error['type']) {
            case "error-page":
                header('location: /error?e=' . $error['message']);
                break;
            case "login-error":
                header('location: /users/login?e=' . $error['message']);
                break;
            case "signup-error":
                header('location: /users/signup?e=' . $error['message']);
                break;
            case "post-create-error":
                header('location: /posts/create?e=' . $error['message']);
                break;
            case "posts-update-error":
                header('location: /posts/' . $error['id'] . '/edit?e=' . $error['message']);
                break;
            default:
                return false;
        }
    }
}