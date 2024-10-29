<!doctype html>
<?php

use Model\Post;
use Model\Reply;

include "part/header.php";
?>
<body>
<div class="m-4">
    <div class="container mt-5">
        <h3 class="d-inline"><a href="/bbs">자유게시판</a></h3>/<h4 class="d-inline">글 읽기</h4>
        <p class="mt-1 mb-3">글의 상세 내용입니다.</p>
        <hr/>
        <?php
        $idx = $_GET['idx'];
        $post = new Post();
        $reply = new Reply();

        $postInfo = $post->getPost($idx);

        if ($postInfo) {
            // 게시글의 락 여부와 락 쿠키 체크
            $pass = false;
            if (isset($_COOKIE['post_key' . $postInfo['idx']])
                && password_verify($_COOKIE['post_key' . $postInfo['idx']], $postInfo['pw'])) {
                $pass = true;
            }
            if ($postInfo['lock'] == 1 && !$pass) {
                ?>
                <form action="/bbs/post/lockCheck" method="post">
                    <p>비밀글입니다, 보기 위해서는 비밀번호가 필요합니다.</p>
                    <div class="form-group">
                        <input type="hidden" name="idx" value="<?= $idx ?>">
                        <label for="pw">Password</label>
                        <input id="pw" type="password" class="form-control" name="pw" placeholder="비밀번호를 입력하세요">
                    </div>
                    <button type="submit" class="btn btn-primary">확인하기</button>
                    <a href="/bbs" class="btn btn-secondary">목록</a>
                </form>
                <?php
            } else {
                $viewsBonus = 0;
                if (!isset($_COOKIE['post_views' . $idx])) {
                    $post->increaseViews($idx);
                    $viewsBonus = 1;
                }
                ?>
                <div>
                    <h5 class="d-inline">제목) <?= $postInfo['title'] ?></h5>
                    <p class="float-right">글쓴이) <?= $postInfo['name'] ?></p>
                </div>
                <span class="mr-2">작성일: <?= $postInfo['created_at'] ?></span>
                <span class="mr-2">수정일: <?= $postInfo['updated_at'] ?></span>
                <span class="mr-2">조회수: <?= $postInfo['views'] + $viewsBonus ?></span>
                <span class="mr-2">추천수: <?= $postInfo['thumbs_up'] ?></span>
                <hr/>

                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">
                            <?= nl2br($postInfo['content']) ?>
                        </p>
                    </div>
                </div>

                <a href="./update?idx=<?= $postInfo['idx'] ?>" class="btn btn-primary">수정하기</a>
                <a href="/bbs" class="btn btn-secondary">목록</a>
                <a href="./delete?idx=<?= $postInfo['idx'] ?>" class="btn btn-dark">삭제하기</a>
                <button class="btn btn-success" id="thumbsUp">
                    추천 <?= $postInfo['thumbs_up'] != 0 ? "(" . $postInfo['thumbs_up'] . ")" : '' ?>
                    <span class="material-symbols-outlined" style="font-size:16px">thumb_up</span>
                </button>
                <!--추천에서 사용할 postIdx 값-->
                <input type="hidden" id="postIdx" value="<?= $idx ?>">

                <div class="mt-2">
                    <hr/>
                    <h5>댓글 작성</h5>
                    <form action="/bbs/reply/create" method="post">
                        <div class="form-group">
                            <input type="hidden" name="post_idx" value="<?= $idx ?>">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name을 입력해주세요.">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="pw"
                                           placeholder="Password를 입력해주세요.">
                                </div>
                            </div>

                            <label for="content">내용:</label>
                            <textarea name="content" class="form-control" id="content" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">댓글 작성</button>
                    </form>
                </div>
                <hr/>
                <h3>댓글</h3>
                <?php
                $replies = $reply->getReplies($idx);
                if ($replies) {
                    foreach ($replies as $replyInfo) {
                        ?>
                        <!-- 댓글 섹션 -->
                        <div class="mt-4 card">
                            <div class="card-body">
                                <input type="hidden" class="reply-idx" value="<?= $replyInfo['idx'] ?>"/>
                                <div class="media-body mb-3">
                                    <h5 class="mt-0"><?= $replyInfo['name'] ?></h5>
                                    <p class="mb-0">작성일: <?= $replyInfo['created_at'] ?></p>
                                    <?= nl2br($replyInfo['content']) ?>
                                </div>
                                <button class="btn btn-primary btn-reply-edit" data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                    수정
                                </button>
                                <button class="btn btn-primary btn-reply-delete" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                    삭제
                                </button>
                                <button class="btn btn-primary btnSubReply">
                                    대댓글
                                </button>
                            </div>
                        </div>
                        <?php
                        $subReplies = $reply->getSubReplies($replyInfo['idx']);
                        if ($subReplies) {
                            foreach ($subReplies as $subReplyInfo) {
                                ?>
                                <div class="mt-4 card ml-4">
                                    <div class="card-body">
                                        <input type="hidden" class="reply-idx" value="<?= $subReplyInfo['idx'] ?>"/>
                                        <div class="media-body mb-3">
                                            <h5 class="mt-0"><?= $subReplyInfo['name'] ?></h5>
                                            <p class="mb-0">작성일: <?= $subReplyInfo['created_at'] ?></p>
                                            <?= nl2br($subReplyInfo['content']) ?>
                                        </div>
                                        <button class="btn btn-primary btn-reply-edit" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                            수정
                                        </button>
                                        <button class="btn btn-primary btn-reply-delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                            삭제
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    include_once "part/editModal.php";
                    include_once "part/deleteModal.php";
                }
            }
        } else {
            echo "<script>alert('존재하지 않는 게시물입니다.');history.back();</script>";
        }
        ?>
    </div>
    <script src="/bbs/assets/script/read.js"></script>
</body>
</html>