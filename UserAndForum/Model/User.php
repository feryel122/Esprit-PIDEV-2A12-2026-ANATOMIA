<?php

class User
{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role;
    private $dateNaissance;
    private $genre;
    private $taille;
    private $poids;
    private $specialite;
    private $createdAt;

    public function __construct(
        $id = null,
        $nom = '',
        $prenom = '',
        $email = '',
        $password = '',
        $role = 'utilisateur',
        $dateNaissance = null,
        $genre = null,
        $taille = null,
        $poids = null,
        $specialite = null,
        $createdAt = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->dateNaissance = $dateNaissance;
        $this->genre = $genre;
        $this->taille = $taille;
        $this->poids = $poids;
        $this->specialite = $specialite;
        $this->createdAt = $createdAt;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getDateNaissance() { return $this->dateNaissance; }
    public function getGenre() { return $this->genre; }
    public function getTaille() { return $this->taille; }
    public function getPoids() { return $this->poids; }
    public function getSpecialite() { return $this->specialite; }
    public function getCreatedAt() { return $this->createdAt; }

    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = $role; }
    public function setDateNaissance($dateNaissance) { $this->dateNaissance = $dateNaissance; }
    public function setGenre($genre) { $this->genre = $genre; }
    public function setTaille($taille) { $this->taille = $taille; }
    public function setPoids($poids) { $this->poids = $poids; }
    public function setSpecialite($specialite) { $this->specialite = $specialite; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
}
