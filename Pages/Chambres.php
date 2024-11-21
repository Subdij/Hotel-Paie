<?php
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

// Récupération des chambres disponibles
$sql = "SELECT * FROM Chambre";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Chambres disponibles</h2>";
    while($row = $result->fetch_assoc()) {
        echo "Chambre: " . $row["ID_chambre"]. " - Type: " . $row["Types_chambre"]. "<br>";
    }
} else {
    echo "Aucune chambre disponible.";
}

// Fermeture de la connexion
$conn->close();
?>
