<?php

namespace Route;

use Controller\ReplyController;

class ReplyRoute extends BaseRoute
{
    function routing($url): bool
    {
        $replyController = new ReplyController();

        if ($this->routeCheck($url, "reply/create", "POST")) {
            $replyController->create();
            return true;
        } else if ($this->routeCheck($url, "reply/read", "GET")) {
            $replyController->read();
            return true;
        } else if ($this->routeCheck($url, "reply/update", "POST")) {
            $replyController->update();
            return true;
        } else if ($this->routeCheck($url, "reply/delete", "POST")) {
            $replyController->delete();
            return true;
        } else {
            return false;
        }
    }
}