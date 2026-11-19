
<?php

class ForumModel
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=anatomia_forum;charset=utf8mb4",
                "root",
                ""
            );

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données.");
        }
    }

    // =====================================
    // PUBLICATIONS
    // =====================================

    // Récupérer toutes les publications actives
    public function getAllPosts()
    {
        $sql = "SELECT * FROM forum_posts
                WHERE status = 'active'
                ORDER BY created_at DESC";

        return $this->db->query($sql)->fetchAll();
    }

    // Récupérer une publication par son ID
    public function getPostById($postId)
    {
        $sql = "SELECT * FROM forum_posts
                WHERE id = :id
                AND status = 'active'";

        $query = $this->db->prepare($sql);
        $query->execute([':id' => $postId]);

        return $query->fetch();
    }

    // Ajouter une publication
    public function addPost($authorName, $title, $content)
    {
        $sql = "INSERT INTO forum_posts
                (author_name, title, content)
                VALUES (:author, :title, :content)";

        $query = $this->db->prepare($sql);

        return $query->execute([
            ':author' => $authorName,
            ':title' => $title,
            ':content' => $content
        ]);
    }

    // Modifier une publication
    public function updatePost($postId, $authorName, $title, $content)
    {
        $sql = "UPDATE forum_posts
                SET title = :title,
                    content = :content
                WHERE id = :id
                AND author_name = :author
                AND status = 'active'";

        $query = $this->db->prepare($sql);

        return $query->execute([
            ':title' => $title,
            ':content' => $content,
            ':id' => $postId,
            ':author' => $authorName
        ]);
    }

    // Supprimer une publication
    public function deletePost($postId, $authorName)
    {
        $sql = "DELETE FROM forum_posts
                WHERE id = :id
                AND author_name = :author";

        $query = $this->db->prepare($sql);

        return $query->execute([
            ':id' => $postId,
            ':author' => $authorName
        ]);
    }

    // =====================================
    // COMMENTAIRES
    // =====================================

    // Ajouter un commentaire
    public function addComment($postId, $authorName, $content)
    {
        $sql = "INSERT INTO forum_comments
                (post_id, author_name, content)
                VALUES (:post_id, :author, :content)";

        $query = $this->db->prepare($sql);

        return $query->execute([
            ':post_id' => $postId,
            ':author' => $authorName,
            ':content' => $content
        ]);
    }

    // Récupérer les commentaires
    public function getComments($postId)
    {
        $sql = "SELECT * FROM forum_comments
                WHERE post_id = :post_id
                ORDER BY created_at ASC";

        $query = $this->db->prepare($sql);
        $query->execute([':post_id' => $postId]);

        return $query->fetchAll();
    }

    // =====================================
    // LIKES
    // =====================================

    // Compter les likes
    public function getLikesCount($postId)
    {
        $sql = "SELECT COUNT(*) FROM forum_likes
                WHERE post_id = :post_id";

        $query = $this->db->prepare($sql);
        $query->execute([':post_id' => $postId]);

        return (int) $query->fetchColumn();
    }

    // Vérifier si un membre a déjà liké
    public function hasLiked($postId, $memberName)
    {
        $sql = "SELECT COUNT(*) FROM forum_likes
                WHERE post_id = :post_id
                AND member_name = :member";

        $query = $this->db->prepare($sql);

        $query->execute([
            ':post_id' => $postId,
            ':member' => $memberName
        ]);

        return (int) $query->fetchColumn() > 0;
    }

    // Ajouter ou retirer un like
    public function toggleLike($postId, $memberName)
    {
        if ($this->hasLiked($postId, $memberName)) {

            $sql = "DELETE FROM forum_likes
                    WHERE post_id = :post_id
                    AND member_name = :member";

        } else {

            $sql = "INSERT INTO forum_likes
                    (post_id, member_name)
                    VALUES (:post_id, :member)";
        }

        $query = $this->db->prepare($sql);

        return $query->execute([
            ':post_id' => $postId,
            ':member' => $memberName
        ]);
    }

    // =====================================
    // SIGNALEMENTS
    // =====================================

    // Enregistrer un signalement
    public function addReport($postId, $reporterName, $reason)
    {
        $sql = "INSERT INTO forum_reports
                (post_id, reporter_name, reason)
                VALUES (:post_id, :reporter, :reason)";

        $query = $this->db->prepare($sql);

        return $query->execute([
            ':post_id' => $postId,
            ':reporter' => $reporterName,
            ':reason' => $reason
        ]);
    }
}
