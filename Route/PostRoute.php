<?php

namespace Route;

use Controller\PostController;

class PostRoute extends BaseRoute
{
    function routing($url): bool
    {
        $PostController = new PostController();

        if ($this->routeCheck($url, "post/list", "GET")) {
            return $this->requireView('index');
        } else if ($this->routeCheck($url, "post/read", "GET")) {
            return $this->requireView('read');
        } else if ($this->routeCheck($url, "post/create", "GET")) {
            return $this->requireView('create');
        } else if ($this->routeCheck($url, "post/update", "GET")) {
            return $this->requireView('update');
        } else if ($this->routeCheck($url, "post/delete", "GET")) {
            return $this->requireView('delete');
        } else if ($this->routeCheck($url, "post/create", "POST")) {
            $PostController->create();
            return true;
        } else if ($this->routeCheck($url, "post/update", "POST")) {
            $PostController->update();
            return true;
        } else if ($this->routeCheck($url, "post/delete", "POST")) {
            $PostController->delete();
            return true;
        } else if ($this->routeCheck($url, "post/lockCheck", "POST")) {
            $PostController->lockCheck();
            return true;
        } else if ($this->routeCheck($url, "post/thumbsUp", "POST")) {
            $PostController->thumbsUp();
            return true;
        } else {
            return false;
        }
    }
}