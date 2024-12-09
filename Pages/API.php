<?php

function connect_db() {
    $conn = new mysqli("localhost", "root", "", "hotel");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function delete_client($id_client) {
    $conn = connect_db();
    $sql = "DELETE FROM client WHERE id_client='$id_client'";
    if ($conn->query($sql) === TRUE) {
        echo "Client deleted successfully";
    } else {
        echo "Error deleting client: " . $conn->error;
    }
    $conn->close();
}

function edit_client($id_client) {
    $conn = connect_db();
    $sql = "SELECT * FROM client WHERE id_client='$id_client'";
    $result = $conn->query($sql);
    $editClient = null;
    if ($result->num_rows > 0) {
        $editClient = $result->fetch_assoc();
    }
    $conn->close();
    return $editClient;
}

function update_client($id_client, $nom, $prenom, $num_tel, $adresse_mail) {
    $conn = connect_db();
    $sql = "UPDATE client SET nom='$nom', prenom='$prenom', num_tel='$num_tel', adresse_mail='$adresse_mail' WHERE id_client='$id_client'";
    if ($conn->query($sql) === TRUE) {
        echo "Client updated successfully";
    } else {
        echo "Error updating client: " . $conn->error;
    }
    $conn->close();
}

function add_client($nom, $prenom, $num_tel, $adresse_mail, $mot_de_passe) {
    $conn = connect_db();
    $mot_de_passe = password_hash($mot_de_passe, PASSWORD_BCRYPT);
    $sql = "INSERT INTO client (nom, prenom, num_tel, adresse_mail, mot_de_passe) VALUES ('$nom', '$prenom', '$num_tel', '$adresse_mail', '$mot_de_passe')";
    if ($conn->query($sql) === TRUE) {
        echo "Client added successfully";
    } else {
        echo "Error adding client: " . $conn->error;
    }
    $conn->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

function cancel_reservation($id_reservation) {
    $conn = connect_db();
    $sql = "DELETE FROM reservation WHERE id_reservation='$id_reservation'";
    if ($conn->query($sql) === TRUE) {
        echo "Reservation cancelled successfully";
    } else {
        echo "Error cancelling reservation: " . $conn->error;
    }
    $conn->close();
}

function edit_reservation($id_reservation) {
    $conn = connect_db();
    $sql = "SELECT * FROM reservation WHERE id_reservation='$id_reservation'";
    $result = $conn->query($sql);
    $editReservation = null;
    if ($result->num_rows > 0) {
        $editReservation = $result->fetch_assoc();
    }
    $conn->close();
    return $editReservation;
}

function update_reservation($id_reservation, $nombre_voyageurs, $date_reservation, $date_debut_sejour, $date_fin_sejour, $options, $prix_total) {
    $conn = connect_db();
    $sql = "UPDATE reservation SET nombre_voyageurs='$nombre_voyageurs', date_reservation='$date_reservation', date_debut_sejour='$date_debut_sejour', date_fin_sejour='$date_fin_sejour', options='$options', prix_total='$prix_total' WHERE id_reservation='$id_reservation'";
    if ($conn->query($sql) === TRUE) {
        echo "Reservation updated successfully";
    } else {
        echo "Error updating reservation: " . $conn->error;
    }
    $conn->close();
}

$editClient = null;
$editReservation = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        delete_client($_POST['id_client']);
        
    } elseif (isset($_POST['edit'])) {
        $editClient = edit_client($_POST['id_client']);

    } elseif (isset($_POST['update'])) {
        update_client($_POST['id_client'], $_POST['nom'], $_POST['prenom'], $_POST['num_tel'], $_POST['adresse_mail']);

    } elseif (isset($_POST['add'])) {
        add_client($_POST['nom'], $_POST['prenom'], $_POST['num_tel'], $_POST['adresse_mail'], $_POST['mot_de_passe']);

    } elseif (isset($_POST['cancel'])) {
        cancel_reservation($_POST['id_reservation']);

    } elseif (isset($_POST['edit_reservation'])) {
        $editReservation = edit_reservation($_POST['id_reservation']);

    } elseif (isset($_POST['update_reservation'])) {
        update_reservation($_POST['id_reservation'], $_POST['nombre_voyageurs'], $_POST['date_reservation'], $_POST['date_debut_sejour'], $_POST['date_fin_sejour'], $_POST['options'], $_POST['prix_total']);
    }
}
?>