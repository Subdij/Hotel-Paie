<?php
session_start();

$editClient = null;
$editReservation = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "", "hotel");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if (isset($_POST['delete'])) {
        $id_client = $_POST['id_client'];
        $sql = "DELETE FROM client WHERE id_client='$id_client'";
        if ($conn->query($sql) === TRUE) {
            echo "Client deleted successfully";
        } else {
            echo "Error deleting client: " . $conn->error;
        }
    } elseif (isset($_POST['edit'])) {
        $id_client = $_POST['id_client'];
        $sql = "SELECT * FROM client WHERE id_client='$id_client'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $editClient = $result->fetch_assoc();
        }
    } elseif (isset($_POST['update'])) {
        $id_client = $_POST['id_client'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $num_tel = $_POST['num_tel'];
        $adresse_mail = $_POST['adresse_mail'];
        $sql = "UPDATE client SET nom='$nom', prenom='$prenom', num_tel='$num_tel', adresse_mail='$adresse_mail' WHERE id_client='$id_client'";
        if ($conn->query($sql) === TRUE) {
            echo "Client updated successfully";
        } else {
            echo "Error updating client: " . $conn->error;
        }
    } elseif (isset($_POST['add'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $num_tel = $_POST['num_tel'];
        $adresse_mail = $_POST['adresse_mail'];
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO client (nom, prenom, num_tel, adresse_mail, mot_de_passe) VALUES ('$nom', '$prenom', '$num_tel', '$adresse_mail', '$mot_de_passe')";
        if ($conn->query($sql) === TRUE) {
            echo "Client added successfully";
        } else {
            echo "Error adding client: " . $conn->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['cancel'])) {
        $id_reservation = $_POST['id_reservation'];
        $sql = "DELETE FROM reservation WHERE id_reservation='$id_reservation'";
        if ($conn->query($sql) === TRUE) {
            echo "Reservation cancelled successfully";
        } else {
            echo "Error cancelling reservation: " . $conn->error;
        }
    } elseif (isset($_POST['edit_reservation'])) {
        $id_reservation = $_POST['id_reservation'];
        $sql = "SELECT * FROM reservation WHERE id_reservation='$id_reservation'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $editReservation = $result->fetch_assoc();
        }
    } elseif (isset($_POST['update_reservation'])) {
        $id_reservation = $_POST['id_reservation'];
        $nombre_voyageurs = $_POST['nombre_voyageurs'];
        $date_reservation = $_POST['date_reservation'];
        $date_debut_sejour = $_POST['date_debut_sejour'];
        $date_fin_sejour = $_POST['date_fin_sejour'];
        $options = $_POST['options'];
        $prix_total = $_POST['prix_total'];
        $sql = "UPDATE reservation SET nombre_voyageurs='$nombre_voyageurs', date_reservation='$date_reservation', date_debut_sejour='$date_debut_sejour', date_fin_sejour='$date_fin_sejour', options='$options', prix_total='$prix_total' WHERE id_reservation='$id_reservation'";
        if ($conn->query($sql) === TRUE) {
            echo "Reservation updated successfully";
        } else {
            echo "Error updating reservation: " . $conn->error;
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/style.css">
    <title>Dashboard</title>
</head>
<body class="h-full">
    <?php include 'Header.php'; ?>
    <div class="min-h-full">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Clients</h2>
                <button onclick="document.getElementById('addClientForm').style.display='block'" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Ajouter Client</button>
                <div id="addClientForm" style="display:none;">
                    <form method="POST" action="">
                        <label for="nom">Nom:</label>
                        <input type="text" id="nom" name="nom" required>
                        <label for="prenom">Prenom:</label>
                        <input type="text" id="prenom" name="prenom" required>
                        <label for="num_tel">Num Tel:</label>
                        <input type="text" id="num_tel" name="num_tel" required>
                        <label for="adresse_mail">Adresse Mail:</label>
                        <input type="email" id="adresse_mail" name="adresse_mail" required>
                        <label for="mot_de_passe">Mot de Passe:</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                        <button type="submit" name="add" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</button>
                    </form>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prenom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Num Tel</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse Mail</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $conn = new mysqli("localhost", "root", "", "hotel");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT * FROM client";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["nom"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["prenom"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["num_tel"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["adresse_mail"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='id_client' value='" . $row["id_client"] . "'>
                                            <button type='submit' name='edit' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>Modifier</button>
                                            <button type='submit' name='delete' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded'>Supprimer</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='px-6 py-4 whitespace-nowrap'>No clients found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <?php if ($editClient): ?>
                <div id="editClientForm">
                    <form method="POST" action="">
                        <input type="hidden" name="id_client" value="<?php echo $editClient['id_client']; ?>">
                        <label for="nom">Nom:</label>
                        <input type="text" id="nom" name="nom" value="<?php echo $editClient['nom']; ?>">
                        <label for="prenom">Prenom:</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo $editClient['prenom']; ?>">
                        <label for="num_tel">Num Tel:</label>
                        <input type="text" id="num_tel" name="num_tel" value="<?php echo $editClient['num_tel']; ?>">
                        <label for="adresse_mail">Adresse Mail:</label>
                        <input type="text" id="adresse_mail" name="adresse_mail" value="<?php echo $editClient['adresse_mail']; ?>">
                        <button type="submit" name="update" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Update</button>
                    </form>
                </div>
                <?php endif; ?>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 mt-8">Reservations</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prenom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Voyageurs</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Reservation</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Debut Sejour</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Fin Sejour</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Options</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $conn = new mysqli("localhost", "root", "", "hotel");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT r.*, c.nom, c.prenom FROM reservation r JOIN client c ON r.id_client = c.id_client";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["nom"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["prenom"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["nombre_voyageurs"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["date_reservation"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["date_debut_sejour"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["date_fin_sejour"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["options"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["prix_total"] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='id_reservation' value='" . $row["id_reservation"] . "'>
                                            <button type='submit' name='edit_reservation' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>Modifier</button>
                                            <button type='submit' name='cancel' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded'>Annuler</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='px-6 py-4 whitespace-nowrap'>No reservations found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <?php if ($editReservation): ?>
                <div id="editReservationForm">
                    <form method="POST" action="">
                        <input type="hidden" name="id_reservation" value="<?php echo $editReservation['id_reservation']; ?>">
                        <label for="nombre_voyageurs">Nombre Voyageurs:</label>
                        <input type="number" id="nombre_voyageurs" name="nombre_voyageurs" value="<?php echo $editReservation['nombre_voyageurs']; ?>" required>
                        <label for="date_reservation">Date Reservation:</label>
                        <input type="date" id="date_reservation" name="date_reservation" value="<?php echo $editReservation['date_reservation']; ?>" required>
                        <label for="date_debut_sejour">Date Debut Sejour:</label>
                        <input type="date" id="date_debut_sejour" name="date_debut_sejour" value="<?php echo $editReservation['date_debut_sejour']; ?>" required>
                        <label for="date_fin_sejour">Date Fin Sejour:</label>
                        <input type="date" id="date_fin_sejour" name="date_fin_sejour" value="<?php echo $editReservation['date_fin_sejour']; ?>" required>
                        <label for="options">Options:</label>
                        <input type="text" id="options" name="options" value="<?php echo $editReservation['options']; ?>">
                        <label for="prix_total">Prix Total:</label>
                        <input type="number" step="0.01" id="prix_total" name="prix_total" value="<?php echo $editReservation['prix_total']; ?>" required>
                        <button type="submit" name="update_reservation" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Update</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
