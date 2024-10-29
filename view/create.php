<!doctype html>
<?php
include "part/header.php";
?>
<body>
<div class="m-4">
    <div class="container mt-5">
        <h3 class="d-inline"><a href="/bbs">자유게시판</a></h3>/<h4 class="d-inline">글 작성</h4>
        <p class="mt-1">글을 작성하는 공간입니다.</p>

        <form action="/bbs/post/create" method="post">
            <div class="form-group">
                <label for="title">제목</label>
                <input type="text" class="form-control" name="title" placeholder="제목을 입력하세요">
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Name을 입력해주세요.">
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="pw" placeholder="Password를 입력해주세요.">
                </div>
            </div>

            <div class="form-group">
                <label for="content">내용</label>
                <textarea class="form-control" name="content" rows="5" placeholder="내용을 입력하세요"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">글쓰기</button>
        </form>
    </div>

</div>
</body>
</html>