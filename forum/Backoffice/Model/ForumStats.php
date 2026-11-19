
<?php

require_once __DIR__ . '/../config/database.php';

class ForumStats
{
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    // =====================================
    // STATISTIQUES DU DASHBOARD
    // =====================================

    public function getTotalPosts()
    {
        return $this->db->query(
            "SELECT COUNT(*) FROM forum_posts WHERE status != 'deleted'"
        )->fetchColumn();
    }

    public function getTotalComments()
    {
        return $this->db->query(
            "SELECT COUNT(*) FROM forum_comments"
        )->fetchColumn();
    }

    public function getTotalLikes()
    {
        return $this->db->query(
            "SELECT COUNT(*) FROM forum_likes"
        )->fetchColumn();
    }

    public function getTotalReports()
    {
        return $this->db->query(
            "SELECT COUNT(*) FROM forum_reports"
        )->fetchColumn();
    }

    // =====================================
    // GESTION DES PUBLICATIONS
    // =====================================

    public function getAllPosts()
    {
        $sql = "SELECT * FROM forum_posts
                ORDER BY created_at DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePost($id, $title, $content)
    {
        $sql = "UPDATE forum_posts
                SET title = ?, content = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$title, $content, $id]);
    }

    // Suppression logique : la publication reste dans la base
    // mais n'est plus visible sur le Front Office.

    public function deletePost($id)
    {
        $sql = "UPDATE forum_posts
                SET status = 'deleted'
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }

    // =====================================
    // GESTION DES COMMENTAIRES
    // =====================================

    public function getAllComments()
    {
        $sql = "SELECT c.*, p.title AS post_title
                FROM forum_comments c
                LEFT JOIN forum_posts p ON c.post_id = p.id
                ORDER BY c.created_at DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteComment($id)
    {
        $sql = "DELETE FROM forum_comments WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }

    // =====================================
    // GESTION DES SIGNALEMENTS
    // =====================================

    public function getAllReports()
    {
        $sql = "SELECT
                    r.id AS report_id,
                    r.post_id,
                    r.reporter_name,
                    r.reason,
                    r.created_at AS report_date,
                    r.status AS report_status,
                    p.title AS post_title,
                    p.author_name AS post_author,
                    p.content AS post_content
                FROM forum_reports r
                LEFT JOIN forum_posts p ON r.post_id = p.id
                ORDER BY r.created_at DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resolveReport($id)
    {
        $sql = "UPDATE forum_reports
                SET status = 'processed'
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }

    public function deleteReport($id)
    {
        $sql = "DELETE FROM forum_reports WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }
}
