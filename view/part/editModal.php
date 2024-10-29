<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editModalForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">댓글 수정</h5>
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
                        <p id="editModalName"></p>
                    </div>
                    <div class="mb-3">
                        <label for="editModalPw" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="editModalPw">
                    </div>
                    <div class="mb-3">
                        <label for="editModalContent" class="form-label">내용:</label>
                        <textarea name="content" class="form-control" id="editModalContent"
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="editModalSubmit" class="btn btn-primary" data-bs-dismiss="modal">수정</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                </div>
            </form>
        </div>
    </div>
</div>