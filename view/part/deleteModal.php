<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteModalForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">댓글 삭제</h5>
                    <button style="height: 35px;" type="button"
                            class="btn-close btn btn-primary p-1"
                            data-bs-dismiss="modal" aria-label="Close">
                                        <span style="width: 23px; height: 23px"
                                              class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <input type="hidden" name="reply_idx" class="modal-reply-idx">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name:</label>
                        <p id="deleteModalName"></p>
                    </div>
                    <div class="mb-3">
                        <label for="deleteModalPw" class="form-label">Password:</label>
                        <input name="pw" type="password" class="form-control" id="deleteModalPw">
                    </div>
                    <div class="mb-3">
                        <label for="editModalContent" class="form-label">내용:</label>
                        <div id="deleteModalContent"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="deleteModalSubmit" class="btn btn-primary">삭제</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                </div>
            </form>
        </div>
    </div>
</div>