<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/User.php';

class UserController
{
    private $db;

    public function __construct()
    {
        $this->db = config::getConnexion();
    }

    public function addUser(User $user, $allowAdmin = false)
    {
        $role = $user->getRole();
        $allowed = $allowAdmin ? ['utilisateur', 'medecin', 'admin'] : ['utilisateur', 'medecin'];
        if (!in_array($role, $allowed, true)) {
            $role = 'utilisateur';
        }

        if ($this->findByEmail($user->getEmail())) {
            return false;
        }

        $specialite = ($role === 'medecin') ? $this->nullable($user->getSpecialite()) : null;
        $query = $this->db->prepare(
            "INSERT INTO users (nom, prenom, email, password, role, date_naissance, genre, taille, poids, specialite)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        return $query->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            password_hash($user->getPassword(), PASSWORD_DEFAULT),
            $role,
            $this->nullable($user->getDateNaissance()),
            $this->nullable($user->getGenre()),
            $this->nullable($user->getTaille()),
            $this->nullable($user->getPoids()),
            $specialite,
        ]);
    }

    public function getAllUsers()
    {
        return $this->db->query(
            "SELECT id, nom, prenom, email, role, date_naissance, genre, taille, poids, specialite, created_at
             FROM users ORDER BY id DESC"
        )->fetchAll();
    }

    public function getUserById($id)
    {
        $query = $this->db->prepare(
            "SELECT id, nom, prenom, email, role, date_naissance, genre, taille, poids, specialite, created_at
             FROM users WHERE id = ?"
        );
        $query->execute([(int) $id]);
        $row = $query->fetch();
        return $row ?: null;
    }

    public function searchUsers($term)
    {
        $like = '%' . $term . '%';
        $query = $this->db->prepare(
            "SELECT id, nom, prenom, email, role, date_naissance, genre, taille, poids, specialite, created_at
             FROM users
             WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR role LIKE ? OR specialite LIKE ?
             ORDER BY id DESC"
        );
        $query->execute([$like, $like, $like, $like, $like]);
        return $query->fetchAll();
    }

    public function updateUser(User $user, $changePassword = false)
    {
        $role = $user->getRole();
        if (!in_array($role, ['utilisateur', 'medecin', 'admin'], true)) {
            return false;
        }

        $specialite = ($role === 'medecin') ? $this->nullable($user->getSpecialite()) : null;
        $params = [
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $role,
            $this->nullable($user->getDateNaissance()),
            $this->nullable($user->getGenre()),
            $this->nullable($user->getTaille()),
            $this->nullable($user->getPoids()),
            $specialite,
        ];

        if ($changePassword) {
            $sql = "UPDATE users
                    SET nom = ?, prenom = ?, email = ?, role = ?, date_naissance = ?, genre = ?, taille = ?, poids = ?, specialite = ?, password = ?
                    WHERE id = ?";
            $params[] = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        } else {
            $sql = "UPDATE users
                    SET nom = ?, prenom = ?, email = ?, role = ?, date_naissance = ?, genre = ?, taille = ?, poids = ?, specialite = ?
                    WHERE id = ?";
        }

        $params[] = (int) $user->getId();
        return $this->db->prepare($sql)->execute($params);
    }

    public function updateProfile(User $user, $changePassword = false)
    {
        $current = $this->getUserById($user->getId());
        if (!$current) {
            return false;
        }

        $specialite = ($current['role'] === 'medecin') ? $this->nullable($user->getSpecialite()) : null;
        $params = [
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $this->nullable($user->getDateNaissance()),
            $this->nullable($user->getGenre()),
            $this->nullable($user->getTaille()),
            $this->nullable($user->getPoids()),
            $specialite,
        ];

        if ($changePassword) {
            $sql = "UPDATE users
                    SET nom = ?, prenom = ?, email = ?, date_naissance = ?, genre = ?, taille = ?, poids = ?, specialite = ?, password = ?
                    WHERE id = ?";
            $params[] = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        } else {
            $sql = "UPDATE users
                    SET nom = ?, prenom = ?, email = ?, date_naissance = ?, genre = ?, taille = ?, poids = ?, specialite = ?
                    WHERE id = ?";
        }

        $params[] = (int) $user->getId();
        return $this->db->prepare($sql)->execute($params);
    }

    public function deleteUser($id)
    {
        $query = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $query->execute([(int) $id]);
        return $query->rowCount() > 0;
    }

    public function findByEmail($email)
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$email]);
        $row = $query->fetch();
        return $row ?: null;
    }

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        unset($user['password']);
        return $user;
    }

    public function countUsers()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function countByRole($role)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $query->execute([$role]);
        return (int) $query->fetchColumn();
    }

    public function emailTakenByOther($email, $id)
    {
        $query = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id <> ?");
        $query->execute([$email, (int) $id]);
        return (bool) $query->fetch();
    }

    private function nullable($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        return $value;
    }
}
