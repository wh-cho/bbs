<?php

namespace Model;

use PDO;
use PDOException;

class Post extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Post 목록을 가져옵니다.
     * @param $search string 검색어
     * @param $start int 시작할 데이터의 인덱스
     * @param $perPage int 페이지마다 보여줄 데이터 수
     * @return array|false
     */
    public function getPosts(string $search, int $start, int $perPage)
    {
        try {
            $query = "select p.*,
        (SELECT COUNT(*) FROM replies r WHERE r.post_idx = p.idx) AS reply_count,
        CASE WHEN TIMESTAMPDIFF(MINUTE, p.created_at, NOW()) <= 1440 THEN 1
        ELSE 0 END AS is_new
        from posts p
        where p.title like :search
        order by idx desc limit :start, :perPage";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue('search', '%' . ($search ?? '') . '%');
            $stmt->bindParam('start', $start, PDO::PARAM_INT);
            $stmt->bindParam('perPage', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Post 추가
     * @param $name
     * @param $pw
     * @param $title
     * @param $content
     * @return bool
     */
    public function create($name, $pw, $title, $content): bool
    {
        try {
            $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);
            $query = "INSERT INTO posts (name, pw, title,content) VALUES (:name, :pw, :title, :content)";
            return $this->conn->prepare($query)->execute([
                'name' => $name,
                'pw' => $hashed_pw,
                'title' => $title,
                'content' => $content
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Post 수정
     * @param $idx
     * @param $pw
     * @param $title
     * @param $content
     * @param $lock
     * @return bool
     */
    public function update($idx, $pw, $title, $content, $lock): bool
    {
        try {
            $query = "SELECT pw FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return false;
            }
            // 업데이트
            $query = "UPDATE posts SET title = :title, content = :content, `lock` = :lock WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'title' => $title,
                'content' => $content,
                'lock' => $lock == 'on' ? 1 : 0,
                'idx' => $idx,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Post 삭제
     * @param $idx
     * @param $pw
     * @return bool
     */
    public function delete($idx, $pw): bool
    {
        try {
            $query = "SELECT pw FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return false;
            }
            // 삭제
            $query = "DELETE FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'idx' => $idx,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Post 목록의 개수
     * @param $search string 검색어
     * @return int|mixed
     */
    public function count(string $search)
    {
        try {
            $query = "SELECT count(idx) FROM posts WHERE title like :search";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue('search', '%' . ($search ?? '') . '%');
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    /**
     * Post 데이터 가져오기
     * @param $idx int Post의 idx
     * @return array|mixed
     */
    public function getPost(int $idx)
    {
        try {
            $query = "SELECT * FROM posts WHERE idx = :idx LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx
            ]);
            return $stmt->fetch();
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Post 조회 수 증가
     * @param $idx int Post의 idx
     * @return bool|void
     */
    public function increaseViews($idx)
    {
        try {
            // post_views 쿠키가 없으면 조회수 증가
            if (!isset($_COOKIE['post_views' . $idx])) {
                $stmt = $this->conn->prepare("update posts set views = views + 1 where idx = :idx");
                $stmt->bindParam('idx', $idx);
                $stmt->execute();
                // 조회수 증가 후 하루짜리 쿠키 생성
                setcookie('post_views' . $idx, true, time() + 60 * 60 * 24, '/');
                return true;
            }
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Post 잠김 확인 후 해제
     * @param $idx
     * @param $pw
     * @return bool
     */
    public function lockCheck($idx, $pw): bool
    {
        try {
            echo $idx;
            $query = "SELECT pw FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return false;
            }
            // 1시간짜리 쿠키 생성
            setcookie("post_key" . $idx, $pw, time() + 3600, "/");
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Post 추천 기능
     * @param $idx int Post의 idx
     * @return array
     */
    public function thumbsUp(int $idx): array
    {
        try {
            // 쿠키 체크
            if (isset($_COOKIE["post_thumbs_up" . $idx])) {
                return [
                    'result' => false,
                    'msg' => '이미 추천하셨습니다.'
                ];
            }

            // 추천 수 증가
            $query = "UPDATE posts SET thumbs_up = thumbs_up + 1 WHERE idx = :idx";
            $result = $this->conn->prepare($query)->execute([
                'idx' => $idx
            ]);

            // 결과 값 리턴 + 쿠키 생성
            if ($result) {
                setcookie("post_thumbs_up" . $idx, true,  time() + 60 * 60 * 24, "/");
                return [
                    'result' => true,
                    'msg' => '추천되었습니다.'
                ];
            }
            return [
                'result' => false,
                'msg' => '추천에 실패했습니다.'
            ];
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return [
                'result' => false,
                'msg' => '알 수 없는 에러가 발생했습니다, 관리자에게 문의주세요.'
            ];
        }
    }

}