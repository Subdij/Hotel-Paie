
CREATE DATABASE HotelPaie;
USE HotelPaie;

CREATE TABLE Client (
    ID_client INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
    num_tel VARCHAR(15) NOT NULL,
    adresse_mail VARCHAR(100) NOT NULL
);

CREATE TABLE Reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    nombre_place INT NOT NULL,
    date_reservation DATE NOT NULL,
    date_debut_sejour DATE NOT NULL,
    date_fin_sejour DATE NOT NULL,
    options TEXT,
    prix_total DECIMAL(10, 2) NOT NULL,
    ID_client INT,
    ID_chambre INT,
    FOREIGN KEY (ID_client) REFERENCES Client(ID_client),
    FOREIGN KEY (ID_chambre) REFERENCES Chambre(ID_chambre)
);

CREATE TABLE Chambre (
    ID_chambre INT AUTO_INCREMENT PRIMARY KEY,
    Types_chambre VARCHAR(50) NOT NULL,
    prix_par_nuit DECIMAL(10, 2) NOT NULL
);

CREATE TABLE Disponibilite (
    ID_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
    ID_chambre INT,
    id_reservation INT,
    disponibilite BOOLEAN NOT NULL,
    FOREIGN KEY (ID_chambre) REFERENCES Chambre(ID_chambre),
    FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation)
);

CREATE TABLE Annulation (
    ID_annulation INT AUTO_INCREMENT PRIMARY KEY,
    id_reservation INT,
    annulation BOOLEAN NOT NULL,
    FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation)
);

CREATE TABLE Paiement (
    id_paiement INT AUTO_INCREMENT PRIMARY KEY,
    mode_paiement VARCHAR(50) NOT NULL,
    paiement_statut BOOLEAN NOT NULL,
    Payment_Date DATE NOT NULL,
    total_prix DECIMAL(10, 2) NOT NULL,
    id_reservation INT,
    FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation)
);