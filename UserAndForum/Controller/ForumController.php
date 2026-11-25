<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Forum.php';

class ForumController
{
    private $db;

    public function __construct()
    {
        $this->db = config::getConnexion();
    }

    public function publicationErrors($title, $content)
    {
        $errors = [];
        $title = trim((string) $title);
        $content = trim((string) $content);

        if ($title === '') {
            $errors[] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($title) < 3) {
            $errors[] = 'Le titre doit contenir au moins 3 caractères.';
        } elseif (mb_strlen($title) > 255) {
            $errors[] = 'Le titre ne peut pas dépasser 255 caractères.';
        }

        if ($content === '') {
            $errors[] = 'Le contenu est obligatoire.';
        } elseif (mb_strlen($content) < 10) {
            $errors[] = 'Le contenu doit contenir au moins 10 caractères.';
        } elseif (mb_strlen($content) > 5000) {
            $errors[] = 'Le contenu ne peut pas dépasser 5000 caractères.';
        }

        return $errors;
    }

    public function commentErrors($content)
    {
        $content = trim((string) $content);
        if ($content === '') {
            return ['Le commentaire est obligatoire.'];
        }
        if (mb_strlen($content) < 2) {
            return ['Le commentaire doit contenir au moins 2 caractères.'];
        }
        if (mb_strlen($content) > 1000) {
            return ['Le commentaire ne peut pas dépasser 1000 caractères.'];
        }
        return [];
    }

    public function reportErrors($reason)
    {
        $reason = trim((string) $reason);
        if ($reason === '') {
            return ['La raison du signalement est obligatoire.'];
        }
        if (mb_strlen($reason) < 10) {
            return ['La raison doit contenir au moins 10 caractères.'];
        }
        if (mb_strlen($reason) > 500) {
            return ['La raison ne peut pas dépasser 500 caractères.'];
        }
        return [];
    }

    public function searchErrors($term)
    {
        $term = trim((string) $term);
        if (mb_strlen($term) > 100) {
            return ['La recherche ne peut pas dépasser 100 caractères.'];
        }
        return [];
    }

    public function addForum(Forum $forum)
    {
        if ($this->publicationErrors($forum->getTitle(), $forum->getContent())) {
            return false;
        }
        $query = $this->db->prepare(
            "INSERT INTO forum_posts (user_id, author_name, title, content, status)
             VALUES (?, ?, ?, ?, 'active')"
        );
        $ok = $query->execute([
            $forum->getUserId(),
            $forum->getAuthorName(),
            $forum->getTitle(),
            $forum->getContent(),
        ]);
        return $ok ? (int) $this->db->lastInsertId() : false;
    }

    public function getAllForums($search = '')
    {
        $sql = "SELECT p.*, u.role AS author_role,
                       (SELECT COUNT(*) FROM forum_comments c WHERE c.post_id = p.id) AS comments_count,
                       (SELECT COUNT(*) FROM forum_likes l WHERE l.post_id = p.id) AS likes_count
                FROM forum_posts p
                LEFT JOIN users u ON u.id = p.user_id
                WHERE p.status = 'active'";
        $params = [];
        if (trim($search) !== '') {
            if ($this->searchErrors($search)) {
                return [];
            }
            $sql .= " AND (p.title LIKE ? OR p.content LIKE ? OR p.author_name LIKE ?)";
            $like = '%' . trim($search) . '%';
            $params = [$like, $like, $like];
        }
        $sql .= " ORDER BY p.created_at DESC";
        $query = $this->db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll();
    }

    public function getAllForumsAdmin($search = '')
    {
        $sql = "SELECT p.*, u.role AS author_role
                FROM forum_posts p
                LEFT JOIN users u ON u.id = p.user_id";
        $params = [];
        if (trim($search) !== '') {
            if ($this->searchErrors($search)) {
                return [];
            }
            $sql .= " WHERE p.title LIKE ? OR p.content LIKE ? OR p.author_name LIKE ?";
            $like = '%' . trim($search) . '%';
            $params = [$like, $like, $like];
        }
        $sql .= " ORDER BY p.created_at DESC";
        $query = $this->db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll();
    }

    public function getForumById($id)
    {
        $query = $this->db->prepare(
            "SELECT p.*, u.role AS author_role,
                    (SELECT COUNT(*) FROM forum_likes l WHERE l.post_id = p.id) AS likes_count
             FROM forum_posts p
             LEFT JOIN users u ON u.id = p.user_id
             WHERE p.id = ?"
        );
        $query->execute([(int) $id]);
        $row = $query->fetch();
        return $row ?: null;
    }

    public function updateForum(Forum $forum, $actorId, $isAdmin = false)
    {
        $post = $this->getForumById($forum->getId());
        if (!$post || $post['status'] === 'deleted') {
            return false;
        }
        if (!$isAdmin && (int) $post['user_id'] !== (int) $actorId) {
            return false;
        }
        if ($this->publicationErrors($forum->getTitle(), $forum->getContent())) {
            return false;
        }

        $query = $this->db->prepare(
            "UPDATE forum_posts SET title = ?, content = ? WHERE id = ?"
        );
        return $query->execute([
            $forum->getTitle(),
            $forum->getContent(),
            (int) $forum->getId(),
        ]);
    }

    public function deleteForum($id, $actorId, $isAdmin = false)
    {
        $post = $this->getForumById($id);
        if (!$post) {
            return false;
        }
        if (!$isAdmin && (int) $post['user_id'] !== (int) $actorId) {
            return false;
        }

        $query = $this->db->prepare("UPDATE forum_posts SET status = 'deleted' WHERE id = ?");
        return $query->execute([(int) $id]);
    }

    public function searchForums($term)
    {
        return $this->getAllForums($term);
    }

    public function getForumsByUser($userId)
    {
        $query = $this->db->prepare(
            "SELECT * FROM forum_posts WHERE user_id = ? AND status = 'active' ORDER BY created_at DESC"
        );
        $query->execute([(int) $userId]);
        return $query->fetchAll();
    }

    public function addComment($postId, $userId, $authorName, $content)
    {
        $post = $this->getForumById($postId);
        if (!$post || $post['status'] !== 'active') {
            return false;
        }
        if ($this->commentErrors($content)) {
            return false;
        }
        $query = $this->db->prepare(
            "INSERT INTO forum_comments (post_id, user_id, author_name, content) VALUES (?, ?, ?, ?)"
        );
        return $query->execute([(int) $postId, (int) $userId, $authorName, $content]);
    }

    public function getCommentsByPost($postId)
    {
        $query = $this->db->prepare(
            "SELECT c.*, u.role AS author_role
             FROM forum_comments c
             LEFT JOIN users u ON u.id = c.user_id
             WHERE c.post_id = ?
             ORDER BY c.created_at ASC"
        );
        $query->execute([(int) $postId]);
        return $query->fetchAll();
    }

    public function getAllComments()
    {
        return $this->db->query(
            "SELECT c.*, p.title AS post_title
             FROM forum_comments c
             LEFT JOIN forum_posts p ON p.id = c.post_id
             ORDER BY c.created_at DESC"
        )->fetchAll();
    }

    public function deleteComment($id, $actorId, $isAdmin = false)
    {
        if ($isAdmin) {
            $query = $this->db->prepare("DELETE FROM forum_comments WHERE id = ?");
            $query->execute([(int) $id]);
            return $query->rowCount() > 0;
        }
        $query = $this->db->prepare("DELETE FROM forum_comments WHERE id = ? AND user_id = ?");
        $query->execute([(int) $id, (int) $actorId]);
        return $query->rowCount() > 0;
    }

    public function countLikes($postId)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM forum_likes WHERE post_id = ?");
        $query->execute([(int) $postId]);
        return (int) $query->fetchColumn();
    }

    public function hasLiked($postId, $userId)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM forum_likes WHERE post_id = ? AND user_id = ?");
        $query->execute([(int) $postId, (int) $userId]);
        return (int) $query->fetchColumn() > 0;
    }

    public function toggleLike($postId, $userId, $memberName)
    {
        $post = $this->getForumById($postId);
        if (!$post || $post['status'] !== 'active') {
            return false;
        }
        if ($this->hasLiked($postId, $userId)) {
            $query = $this->db->prepare("DELETE FROM forum_likes WHERE post_id = ? AND user_id = ?");
            return $query->execute([(int) $postId, (int) $userId]);
        }
        $query = $this->db->prepare(
            "INSERT INTO forum_likes (post_id, user_id, member_name) VALUES (?, ?, ?)"
        );
        return $query->execute([(int) $postId, (int) $userId, $memberName]);
    }

    public function addReport($postId, $userId, $reporterName, $reason)
    {
        $post = $this->getForumById($postId);
        if (!$post || $post['status'] !== 'active') {
            return false;
        }
        if ((int) $post['user_id'] === (int) $userId) {
            return false;
        }
        if ($this->reportErrors($reason)) {
            return false;
        }
        try {
            $query = $this->db->prepare(
                "INSERT INTO forum_reports (post_id, user_id, reporter_name, reason) VALUES (?, ?, ?, ?)"
            );
            return $query->execute([(int) $postId, (int) $userId, $reporterName, $reason]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllReports()
    {
        return $this->db->query(
            "SELECT r.id AS report_id, r.post_id, r.reporter_name, r.reason,
                    r.created_at AS report_date, r.status AS report_status,
                    p.title AS post_title, p.author_name AS post_author, p.content AS post_content
             FROM forum_reports r
             LEFT JOIN forum_posts p ON p.id = r.post_id
             ORDER BY r.created_at DESC"
        )->fetchAll();
    }

    public function resolveReport($id)
    {
        $query = $this->db->prepare("UPDATE forum_reports SET status = 'processed' WHERE id = ?");
        return $query->execute([(int) $id]);
    }

    public function deleteReport($id)
    {
        $query = $this->db->prepare("DELETE FROM forum_reports WHERE id = ?");
        $query->execute([(int) $id]);
        return $query->rowCount() > 0;
    }

    public function getStats()
    {
        return [
            'posts' => (int) $this->db->query("SELECT COUNT(*) FROM forum_posts WHERE status <> 'deleted'")->fetchColumn(),
            'comments' => (int) $this->db->query("SELECT COUNT(*) FROM forum_comments")->fetchColumn(),
            'likes' => (int) $this->db->query("SELECT COUNT(*) FROM forum_likes")->fetchColumn(),
            'reports' => (int) $this->db->query("SELECT COUNT(*) FROM forum_reports WHERE status = 'pending'")->fetchColumn(),
        ];
    }
}
