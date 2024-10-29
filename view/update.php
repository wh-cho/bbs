<!doctype html>
<?php
include "part/header.php";

use Model\Post;

?>
<body>
<?php

$post = new Post();
$postInfo = $post->getPost($_GET['idx']);
if ($postInfo) {
    ?>
    <div class="m-4">
        <div class="container mt-5">
            <h3 class="d-inline">
                <a href="/bbs">자유게시판</a>
            </h3>
            /<h4 class="d-inline">글 수정</h4>
            <p class="mt-1">글을 수정하는 공간입니다.</p>

            <form action="/bbs/post/update" method="post">
                <span class="mr-2">작성일: <?= $postInfo['created_at'] ?></span>
                <span class="mr-2">수정일: <?= $postInfo['updated_at'] ?></span>
                <span class="mr-2">조회수: <?= $postInfo['views'] ?></span>
                <span class="mr-2">추천수: <?= $postInfo['thumbs_up'] ?></span>

                <input type="hidden" name="idx" value="<?= $_GET['idx'] ?>">

                <div class="form-group mt-3">
                    <label for="title">제목</label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="제목을 입력하세요"
                           value="<?= $postInfo['title'] ?>">
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="lock"
                           name="lock" <?= $postInfo['lock'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="lock">
                        비밀 글 여부
                    </label>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <p> <?= $postInfo['name'] ?></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="pw"
                               placeholder="Password를 입력해주세요.">
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">내용</label>
                    <textarea class="form-control" name="content" rows="5" id="content"
                              placeholder="내용을 입력하세요"><?= $postInfo['content'] ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">저장하기</button>
                <a href="/bbs" class="btn btn-secondary">목록</a>
                <a href="./read?idx=<?= $postInfo['idx'] ?>" class="btn btn-secondary">뒤로가기</a>
            </form>
        </div>
    </div>
    <?php
} else {
    echo "<script>alert('존재하지 않는 게시물입니다.');history.back();</script>";
}
?>
</body>
</html>