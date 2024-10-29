<?php

use Model\Post;

$post = new Post();
?>
<!doctype html>
<?php
include "part/header.php";
?>
<body>
<div class="m-4">
    <h3><a href="/bbs">자유게시판</a></h3>

    <div id="write_btn" class="mb-4">
        <p class="d-inline">자유롭게 글을 쓸 수 있는 게시판입니다.</p>
        <a href="./create">
            <button class="btn btn-primary float-right">글쓰기</button>
        </a>
    </div>

    <!--검색-->
    <div class="container mt-4 mb-3">
        <form action="" method="get">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-inline full-width-form">
                        <div class="form-group mb-2 flex-fill">
                            <label for="searchInput" class="sr-only">검색</label>
                            <input name="search" type="text" class="form-control w-100" id="searchInput"
                                   placeholder="Search"
                                   value="<?= $_GET['search'] ?? '' ?>">
                        </div>
                        <button id="searchSubmit" type="submit" class="btn btn-primary mb-2">검색</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- 게시물 목록 테이블 -->
    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <th width="80">번호</th>
            <th width="300">제목</th>
            <th width="100">글쓴이</th>
            <th width="80">추천수</th>
            <th width="80">조회수</th>
            <th width="100">작성일</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // 현재 페이지 설정 값
        $currentPage = $_GET['page'] ?? 1;
        $searchWord = $_GET['search'] ?? '';

        // 페이지마다 보여줄 아이템 개수
        $perPage = 3;
        // 페이지의 시작 인덱스 값
        $startIndex = ($currentPage - 1) * $perPage;

        // 전체 게시글 수
        $total = $post->count($searchWord);

        // 전체 페이지 개수
        $totalPage = ceil($total / $perPage);

        // 현재 페이지에서 보여줄 마지막 페이지
        $endPage = $totalPage > $currentPage + 4 ? $currentPage + 4 : $totalPage;
        $endPage = $endPage < 10 && $totalPage > 10 ? 10 : $endPage;

        // 게시글 목록 가져오기
        $posts = $post->getPosts($searchWord, $startIndex, $perPage);

        if ($posts) {
            $bonusIdx = 0;
            foreach ($posts as $postInfo) {
                /// 30 글자 초과시 ... 저리
                $title = $postInfo["title"];
                if (strlen($title) > 30) {
                    // mb_substr: 한글이 깨지지 않도록 해줌
                    $title = str_replace($postInfo["title"], mb_substr($postInfo["title"], 0, 30, "utf-8") . "...", $postInfo["title"]);
                }
                ?>

                <tr>
                    <td><?= $total - ($startIndex + $bonusIdx++) ?></td>
                    <td>
                        <a href="./read?idx=<?= $postInfo['idx'] ?>">
                            <?= $title . " [" . $postInfo['reply_count'] . "]"; ?>
                            <?php if ($postInfo['is_new']) { ?>
                                <span class="badge badge-primary">new</span>
                            <?php } ?>
                        </a>
                    </td>
                    <td><?= $postInfo['name'] ?></td>
                    <td><?= $postInfo['thumbs_up'] ?></td>
                    <td><?= $postInfo['views'] ?></td>
                    <td><?= $postInfo['created_at'] ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>게시글이 없습니다.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- 페이지네이션 -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" data-page="1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            for ($page = max($currentPage - 4, 1); $page <= $endPage; $page++) {
                $isActive = $page == $currentPage ? 'active' : '';
                echo "<li class='page-item $isActive'><span class='page-link' data-page='$page'>$page</span></li>";
            }
            ?>
            <li class="page-item">
                <a class="page-link" data-page="<?= $totalPage ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
</body>
<script src="/bbs/assets/script/index.js"></script>
</html>