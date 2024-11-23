<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paie</title>
    <link rel="stylesheet" href="./Styles/style.css">
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
        echo "<h2 class='text-2xl font-bold mb-4'>Chambres disponibles</h2>";
        echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='room-card bg-white border border-gray-200 rounded-lg shadow-md p-6'>";
            echo "<h3 class='text-xl font-semibold mb-2'>Chambre: " . $row["ID_chambre"]. "</h3>";
            echo "<p class='mb-2'>Type: " . $row["Types_chambre"]. "</p>";
            echo "<p class='mb-4'>Description: " . $row["description"]. "</p>";
            echo "<form action='Pages/Reservations.php' method='get'>";
            echo "<input type='hidden' name='id_chambre' value='" . $row["ID_chambre"] . "'>";
            echo "<input type='submit' value='Réserver' class='bg-blue-500 text-white px-4 py-2 rounded'>";
            echo "</form>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>Aucune chambre disponible.</p>";
    }
    ?>
</body>
</html>