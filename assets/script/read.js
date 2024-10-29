$(document).ready(function () {
    // 추천 기능
    $("#thumbsUp").click(function () {
        $.ajax({
            url: "/bbs/post/thumbsUp",
            type: "POST",
            data: {
                post_idx: $("#postIdx").val()
            },
            success: function (data) {
                alert(data.msg);
                if (data.result) {
                    location.reload();
                }
            },
            error: function (e) {
                alert("에러 발생");
            }
        });
    });

    // 댓글 수정 모달에 데이터 세팅 기능
    $(".btn-reply-edit").click(function () {
        let replyIdx = $(this).parent().parent().find(".reply-idx").val();
        console.log(replyIdx);
        $("#editModal .modal-reply-idx").val(replyIdx);
        $.ajax({
            url: "/bbs/reply/read",
            type: "GET",
            data: {
                reply_idx: replyIdx
            },
            success: function (data) {
                console.log(data);
                if (data.result) {
                    $("#editModalName").text(data.data.name);
                    $("#editModalPw").val("")
                    $("#editModalContent").val(data.data.content);
                } else {
                    alert(data.msg);
                }
            },
            error: function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })

    // 댓글 삭제 모달에 데이터 세팅 기능
    $(".btn-reply-delete").click(function () {
        let replyIdx = $(this).parent().parent().find(".reply-idx").val();
        $("#deleteModal .modal-reply-idx").val(replyIdx);
        $.ajax({
            url: "/bbs/reply/read",
            type: "GET",
            data: {
                reply_idx: replyIdx
            },
            success: function (data) {
                if (data.result) {
                    $("#deleteModalName").text(data.data.name);
                    $("#deleteModalPw").val("")
                    $("#deleteModalContent").text(data.data.content);
                } else {
                    alert(data.msg);
                }
            },
            error: function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })

    // 댓글 수정 ajax 기능
    $("#editModalSubmit").click(function (){
        $.ajax({
            url: "/bbs/reply/update",
            type: "POST",
            data: {
                reply_idx: $("#editModal .modal-reply-idx").val(),
                pw: $("#editModalPw").val(),
                content: $("#editModalContent").val()
            },
            success: function (data) {
                console.log(data);
                if (data.result) {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error : function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })

    // 댓글 삭제 ajax 기능
    $("#deleteModalSubmit").click(function (){
        $.ajax({
            url: "/bbs/reply/delete",
            type: "POST",
            data: {
                reply_idx: $("#deleteModal .modal-reply-idx").val(),
                pw: $("#deleteModalPw").val()
            },
            success: function (data) {
                console.log(data);
                if (data.result) {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error : function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })

    // 대댓글 작성 폼 생성 기능
    $(".btnSubReply").click(function (){
        let replyIdx = $(this).parent().find(".reply-idx").val();
        let postIdx = $("#postIdx").val();
        let subReplyFormExist = $("#subReplyForm").length > 0;
        if (subReplyFormExist) {
            $("#subReplyForm").remove();
        }

        $(this).parent().parent().after(
            "<div class=\"mt-4 card ml-4\" id=\"subReplyForm\">\n" +
            "            <div class=\"card-body\">\n" +
            "                <form action=\"/bbs/reply/create\" method=\"post\">\n" +
            "                    <h5>대댓글</h5>\n" +
            "                    <input name=\"post_idx\" type=\"hidden\" class=\"reply-idx\" value=\""+postIdx+"\"/>\n" +
            "                    <div class=\"media-body mb-3\">\n" +
            "                        <input name=\"parent_idx\" type=\"hidden\" name=\"post_idx\" value=\""+replyIdx+"\">\n" +
            "                        <div class=\"form-row\">\n" +
            "                            <div class=\"form-group col-md-6\">\n" +
            "                                <label for=\"name\">Name</label>\n" +
            "                                <input type=\"text\" class=\"form-control\" name=\"name\" placeholder=\"Name을 입력해주세요.\">\n" +
            "                            </div>\n" +
            "                            <div class=\"form-group col-md-6\">\n" +
            "                                <label for=\"password\">Password</label>\n" +
            "                                <input type=\"password\" class=\"form-control\" name=\"pw\"\n" +
            "                                       placeholder=\"Password를 입력해주세요.\">\n" +
            "                            </div>\n" +
            "                        </div>\n" +
            "                        <label for=\"content\">내용:</label>\n" +
            "                        <textarea name=\"content\" class=\"form-control\" id=\"content\" rows=\"3\"></textarea>\n" +
            "                    </div>\n" +
            "                    <button class=\"btn btn-primary\" type=\"submit\">댓글 작성</button>\n" +
            "                </form>\n" +
            "            </div>\n" +
            "        </div>")
    })
});