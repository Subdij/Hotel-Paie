<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paie</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
    <?php
    include 'Pages/Header.php';

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
        echo "<div class='room-cards'>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='room-card'>";
            echo "<h3>Chambre: " . $row["ID_chambre"]. "</h3>";
            echo "<p>Type: " . $row["Types_chambre"]. "</p>";
            echo "<p>Description: " . $row["description"]. "</p>";
            echo "<form action='Pages/Reservations.php' method='get'>";
            echo "<input type='hidden' name='id_chambre' value='" . $row["ID_chambre"] . "'>";
            echo "<input type='submit' value='Réserver'>";
            echo "</form>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "Aucune chambre disponible.";
    }

    // Fermeture de la connexion
    $conn->close();
    ?>
</body>
</html>

