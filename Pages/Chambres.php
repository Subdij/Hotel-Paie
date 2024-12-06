<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Styles/chambres.css">
    <link rel="stylesheet" href="../Styles/style.css">
</head>
<body>
  <?php

    include '../Pages/Header.php';

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel";

    // Créer la connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    ?>

    <section class="search-bar">
        <form method="GET" action="chambres.php">
            <label for="checkin">Date d'arrivée :</label>
            <input type="date" id="checkin" name="checkin" min="' . date('Y-m-d') . '" required>

            <label for="checkout">Date de départ :</label>
            <input type="date" id="checkout" name="checkout" required>

            <label for="guests">Nombre de voyageurs :</label>
            <input type="number" id="guests" name="guests" min="1" value="1" required>

            <button type="submit">Rechercher</button>
        </form>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const checkin = document.getElementById("checkin");
            const checkout = document.getElementById("checkout");

            // Mise à jour de la date minimale de départ en fonction de la date d'arrivée
            checkin.addEventListener("change", function () {
                const checkinDate = new Date(this.value);
                const minCheckoutDate = new Date(checkinDate);
                minCheckoutDate.setDate(minCheckoutDate.getDate() + 1); // Départ au moins 1 jour après
                checkout.min = minCheckoutDate.toISOString().split("T")[0];
                if (checkout.value && new Date(checkout.value) <= checkinDate) {
                    checkout.value = ""; // Réinitialise si la date est invalide
                }
            });

            // Définit la date d\'aujourd\'hui comme minimum par défaut pour l\'arrivée
            const today = new Date().toISOString().split("T")[0];
            checkin.min = today;
            checkout.min = today;
        });
    </script>
    </section>

    <section class="presentation_chambres">
        <?php

        // Récupérer les paramètres de recherche
        $checkin = $_GET['checkin'] ?? null;
        $checkout = $_GET['checkout'] ?? null;
        $guests = $_GET['guests'] ?? null;

        // Début de la requête pour récupérer les chambres disponibles
        $sql = "SELECT * FROM chambre WHERE 1";

        // Si des dates sont spécifiées, ajouter les conditions de disponibilité
        if ($checkin && $checkout) {
            $sql .= "
                    AND id_chambre NOT IN (
                    SELECT id_chambre
                    FROM réservation
                    WHERE (date_debut_sejour < '$checkout' AND date_fin_sejour > '$checkin')
                )
            ";
        }

        // Si un nombre de voyageurs est spécifié, ajouter la condition de capacité maximale
        if ($guests) {
            $sql .= " AND capacite_max >= $guests";
        }

        // Exécution de la requête
        $result = $conn->query($sql);

        // Afficher les chambres disponibles
        if ($result->num_rows > 0) {
            echo "<h2>Chambres disponibles</h2>";

            // Si plus de 4 voyageurs, afficher un message
            if ($guests > 4) {
                echo "<p class='alert-message'>Pour les groupes de plus de 4 personnes, veuillez réserver plusieurs chambres.</p>";
            }

            // Affichage des chambres disponibles
            while($row = $result->fetch_assoc()) {
                echo "<div class='room'>";
                echo "<img src='../Ressources/chambre.jpg' alt='Chambre'>";
                echo "<h2>Chambre #" . $row['id_chambre'] . "</h2>";
                echo "<p>Type : " . $row['type_chambre'] . "</p>";
                echo "<p>Prix : " . $row['prix_par_nuit'] . " € par nuit</p>";
                echo "<p>Capacité maximale : " . $row['capacite_max'] . " personnes</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucune chambre disponible pour les critères sélectionnés.</p>";
        }

        // Fermeture de la connexion
        $conn->close();
        ?>
    </section>

</body>
</html>

