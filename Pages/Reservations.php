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

    // Récupération de l'ID de la chambre sélectionnée via $_GET
    $id_chambre = $_GET['id_chambre'] ?? null;

    // Récupération des informations de la chambre
    $chambre = null;
    if ($id_chambre) {
        $sql = "SELECT * FROM chambre WHERE id_chambre = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_chambre);
        $stmt->execute();
        $result = $stmt->get_result();
        $chambre = $result->fetch_assoc();
        $stmt->close();
    }

    // Vérification si l'utilisateur est connecté
    $user = $_SESSION['user'] ?? null;
    ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="../Styles/reservation.css">
</head>
<body>
    <section class="reservation-page">
        <!-- Rappel de la chambre -->
        <div class="room-details">
            <?php if ($chambre): ?>
                <img src="../Ressources/chambre.jpg" alt="Chambre">
                <h2>Chambre #<?= htmlspecialchars($chambre['id_chambre']) ?></h2>
                <p>Type : <?= htmlspecialchars($chambre['type_chambre']) ?></p>
                <p>Description : <?= htmlspecialchars($chambre['description']) ?></p>
                <p>Capacité maximale : <?= htmlspecialchars($chambre['capacite_max']) ?> personnes</p>
                <p>Prix : <?= htmlspecialchars($chambre['prix_par_nuit']) ?> € par nuit</p>
            <?php else: ?>
                <p>Erreur : Aucune chambre sélectionnée.</p>
            <?php endif; ?>
        </div>

        <!-- Section formulaire et récapitulatif -->
        <div class="reservation-container">
            <!-- Colonne de gauche : Formulaire -->
            <div class="reservation-form">
                <h3>Formulaire de réservation</h3>
                <?php if (!$user): ?>
                    <p>Veuillez vous connecter pour continuer votre réservation.</p>
                    <a href="../Pages/Connexion.php" class="button">Se connecter</a>
                <?php else: ?>
                    <form action="confirmer_reservation.php" method="post">
                        <input type="hidden" name="id_chambre" value="<?= htmlspecialchars($id_chambre) ?>">
                        
                        <h4>Informations personnelles</h4>
                        <label>Prénom : <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required></label>
                        <label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" required></label>
                        <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required></label>
                        <label>Téléphone : <input type="text" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" required></label>

                        <h4>Détails du séjour</h4>
                        <label>Date d'arrivée : <input type="date" name="date_arrivee" value="<?= htmlspecialchars($_GET['checkin'] ?? '') ?>" required></label>
                        <label>Date de départ : <input type="date" name="date_depart" value="<?= htmlspecialchars($_GET['checkout'] ?? '') ?>" required></label>
                        <label>Nombre de voyageurs : <input type="number" name="voyageurs" min="1" value="<?= htmlspecialchars($_GET['guests'] ?? 1) ?>" required></label>

                        <h4>Options</h4>
                        <label><input type="checkbox" name="options[]" value="petit_dejeuner"> Petit-déjeuner (+10€)</label>
                        <label><input type="checkbox" name="options[]" value="parking"> Parking (+5€)</label>
                        <label><input type="checkbox" name="options[]" value="spa"> Spa (+20€)</label>

                        <button type="submit">Confirmer</button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Colonne de droite : Récapitulatif -->
            <div class="summary">
                <h3>Récapitulatif de la commande</h3>
                <?php if ($chambre): ?>
                    <p>Prix par nuit : <?= htmlspecialchars($chambre['prix_par_nuit']) ?> €</p>
                    <?php 
                        $nights = (isset($_GET['checkin'], $_GET['checkout'])) 
                            ? (strtotime($_GET['checkout']) - strtotime($_GET['checkin'])) / 86400 
                            : 0;
                        $total = $nights * $chambre['prix_par_nuit'];
                    ?>
                    <p>Nombre de nuits : <?= $nights ?></p>
                    <p>Total chambre : <?= $total ?> €</p>
                    <p>Frais de séjour : 5 €</p>
                    <p>Total final : <?= $total + 5 ?> € (dont TVA)</p>
                    <button type="submit" form="reservation-form">Confirmer</button>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>
