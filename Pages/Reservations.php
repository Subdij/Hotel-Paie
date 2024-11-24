<?php
// ...existing code...
?>

<h2>Réserver une chambre</h2>
<form action="Reservations.php" method="post">
    <label for="name">Nom:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="prenom">Prénom:</label>
    <input type="text" id="prenom" name="prenom" required><br><br>
    <label for="num_tel">Numéro de téléphone:</label>
    <input type="tel" id="num_tel" name="num_tel" pattern="[0-9]{10}" required><br><br>
    <label for="adresse_mail">Adresse e-mail:</label>
    <input type="email" id="adresse_mail" name="adresse_mail" required><br><br>
    <label for="date_debut">Date de début:</label>
    <input type="date" id="date_debut" name="date_debut" required><br><br>
    <label for="date_fin">Date de fin:</label>
    <input type="date" id="date_fin" name="date_fin" required><br><br>
    <label for="options">Options:</label>
    <input type="text" id="options" name="options"><br><br>
    <input type="hidden" id="id_chambre" name="id_chambre" value="<?php echo $_GET['id_chambre']; ?>">
    <input type="submit" value="Réserver">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "HotelPaie";

    // Créer la connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insertion des données de réservation
    $name = $_POST['name'];
    $prenom = $_POST['prenom'];
    $num_tel = $_POST['num_tel'];
    $adresse_mail = $_POST['adresse_mail'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $options = $_POST['options'];
    $id_chambre = $_POST['id_chambre'];

    // Vérifier si la chambre existe
    $chambre_check_sql = "SELECT * FROM Chambre WHERE ID_chambre = $id_chambre";
    $chambre_check_result = $conn->query($chambre_check_sql);

    if ($chambre_check_result->num_rows > 0) {
        // Insertion des données du client
        $client_sql = "INSERT INTO Client (Nom, Prenom, num_tel, adresse_mail) VALUES ('$name', '$prenom', '$num_tel', '$adresse_mail')";
        if ($conn->query($client_sql) === TRUE) {
            $client_id = $conn->insert_id;

            // Insertion des données de réservation
            $reservation_sql = "INSERT INTO Reservation (nombre_place, date_reservation, date_debut_sejour, date_fin_sejour, options, prix_total, ID_client, ID_chambre) VALUES (1, NOW(), '$date_debut', '$date_fin', '$options', 0, $client_id, $id_chambre)";
            if ($conn->query($reservation_sql) === TRUE) {
                $reservation_id = $conn->insert_id;
                header("Location: Paiement.php?id_reservation=$reservation_id");
                exit();
            } else {
                echo "Erreur: " . $reservation_sql . "<br>" . $conn->error;
            }
        } else {
            echo "Erreur: " . $client_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Erreur: La chambre avec l'ID $id_chambre n'existe pas.";
    }

    // Fermeture de la connexion
    $conn->close();
}
?>
