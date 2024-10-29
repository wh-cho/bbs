<?php
namespace DB;
require_once __DIR__."/../bootstrap.php";
use Migration\Post;
use Migration\Reply;

new Migration();

// cmd, bbs 폴더에서 다음 명령어 실횅  php .\DB\Migration.php
class Migration
{
    public function __construct()
    {
        $this->post = new post();
        $this->reply = new reply();

        echo "[Migration Start]\n";
        $this->post->migrate();
        $this->reply->migrate();
        echo "[Migration End]\n";
    }
}