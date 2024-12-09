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
    

    // Gestion de la réservation
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Traitement des données envoyées depuis le fichier js
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['options'], $input['prix_total'])) {
            $options = json_encode($input['options']);
            $prix_total = (float)$input['prix_total'];
        }
        // Reste des données
        $nombre_voyageurs = $_GET['guests'];
        $date_reservation = date('Y-m-d H:i:s');
        $date_debut_sejour = $_GET['checkin'];
        $date_fin_sejour = $_GET['checkout'];
        $id_client = $user['id_client'];
        $id_chambre = $_GET['id_chambre'];
        // Insérer la réservation
        $sql = "INSERT INTO reservation (nombre_voyageurs, date_reservation, date_debut_sejour, date_fin_sejour, options, prix_total, id_client, id_chambre) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "issssdii", 
            $nombre_voyageurs, 
            $date_reservation, 
            $date_debut_sejour, 
            $date_fin_sejour, 
            $options, 
            $prix_total, 
            $id_client, 
            $id_chambre
        );

        if ($stmt->execute()) {
            $success_message = "Votre réservation a été créée avec succès.";
        } else {
            $error_message = "Erreur lors de la création de la réservation : " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="../Styles/reservation.css">
    <script src="../reservation.js"></script>
</head>
<body>
    <section class="reservation-page">
        <!-- Rappel de la chambre -->
        <div class="room-details">
            <?php if ($chambre): ?>
                <?php echo"<img src='" . htmlspecialchars($chambre['url_image']) . "' alt='Chambre'>"; ?>
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
                    <form method="post">
                        <input type="hidden" name="id_chambre" value="<?= htmlspecialchars($id_chambre) ?>">
                        
                        <h4>Informations personnelles</h4>
                        <label>Prénom : <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required></label>
                        <label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" required></label>
                        <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($user['adresse_mail'] ?? '') ?>" required></label>
                        <label>Téléphone : <input type="text" name="telephone" value="<?= htmlspecialchars($user['num_tel'] ?? '') ?>" required></label>

                        <h4>Détails du séjour</h4>
                        <label>Date d'arrivée : <input type="date" name="date_arrivee" value="<?= htmlspecialchars($_POST['checkin'] ?? $_GET['checkin'] ?? '') ?>" required></label>
                        <label>Date de départ : <input type="date" name="date_depart" value="<?= htmlspecialchars($_POST['checkout'] ?? $_GET['checkout'] ?? '') ?>" required></label>
                        <label>Nombre de voyageurs : <input type="number" name="voyageurs" min="1" value="<?= htmlspecialchars($_POST['guests'] ?? $_GET['guests'] ?? 1) ?>" required></label>

                        <h4>Options</h4>
                        <label><input type="checkbox" name="options[]" value="petit_dejeuner" <?= isset($_POST['options']) && in_array('petit_dejeuner', $_POST['options']) ? 'checked' : '' ?>> Petit déjeuner (+10€/personne/nuit)</label>
                        <label><input type="checkbox" name="options[]" value="parking" <?= isset($_POST['options']) && in_array('parking', $_POST['options']) ? 'checked' : '' ?>> Parking (+15€)</label>
                        <label><input type="checkbox" name="options[]" value="spa" <?= isset($_POST['options']) && in_array('spa', $_POST['options']) ? 'checked' : '' ?>> Spa (+20€/personne/nuit)</label>

                        <button type="button" id="confirm-button-left">Confirmer</button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Colonne de droite : Récapitulatif -->
            <div class="summary"
                data-prix-par-nuit="<?= htmlspecialchars($chambre['prix_par_nuit']) ?>" 
                data-duree-sejour="<?= htmlspecialchars($interval->days ?? 1) ?>" 
                data-voyageurs="<?= htmlspecialchars($_POST['guests'] ?? $_GET['guests'] ?? 1) ?>" 
                data-options="<?= htmlspecialchars(json_encode($options ?? [])) ?>">
                <h3>Récapitulatif de la commande</h3>
            </div>
        </div>
    </section>

    <section class="pop-ups">
        <div id="confirmation-modal" class="modal hidden">
        <div class="modal-content">
            <p>Souhaitez-vous vraiment confirmer la réservation ?</p>
            <div class="modal-actions">
                <button id="modal-no">Non</button>
                <form method="post">
                    <input type="hidden" name="confirm_reservation" value="1">
                    <input type="hidden" name="date_arrivee" value="<?= htmlspecialchars($_POST['date_arrivee'] ?? '') ?>">
                    <input type="hidden" name="date_depart" value="<?= htmlspecialchars($_POST['date_depart'] ?? '') ?>">
                    <input type="hidden" name="voyageurs" value="<?= htmlspecialchars($_POST['voyageurs'] ?? 1) ?>">
                    <input type="hidden" name="options[]" value="">
                    <button id="modal-yes">Oui</button>;
                </form>
            </div>
        </div>
    </div>

    <div id="final-message-modal" class="modal hidden">
        <div class="modal-content">
            <p id="final-message">Votre message ici.</p>
            <button id="home-final-message">Accueil</button>
        </div>
    </div>
    </section>
</body>
</html>
