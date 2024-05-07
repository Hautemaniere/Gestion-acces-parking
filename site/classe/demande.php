<?php

class DemandeVehicule {
    private $nom;
    private $prenom;
    private $email;
    private $immatriculation;
    private $image;
    private $statut;
    private $id_user;
    private $date;

    // Constructeur pour initialiser les propriétés de la demande de véhicule
    public function __construct($nom, $prenom, $email, $immatriculation, $image, $statut, $id_user, $date) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->immatriculation = $immatriculation;
        $this->image = $image;
        $this->statut = $statut;
        $this->id_user = $id_user;
        $this->date = $date;
    }

    // Méthodes pour obtenir les propriétés de la demande de véhicule
    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getImmatriculation() {
        return $this->immatriculation;
    }

    public function getImage() {
        return $this->image;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function getDate() {
        return $this->date;
    }
}
?>
