document.addEventListener("DOMContentLoaded", () => {
    const summaryContainer = document.querySelector(".summary");

    // Récupération des données initiales
    const prixParNuit = parseFloat(summaryContainer.dataset.prixParNuit);
    const tarifsOptions = {
        petit_dejeuner: 10, // 10€/personne/nuit
        parking: 15,       // 15€/nuit
        spa: 20            // 20€/personne/nuit
    };
    const fraisSejourParNuit = 6;

    const formInputs = {
        checkin: document.querySelector('input[name="date_arrivee"]'),
        checkout: document.querySelector('input[name="date_depart"]'),
        guests: document.querySelector('input[name="voyageurs"]'),
        options: document.querySelectorAll('input[name="options[]"]')
    };

    // Fonction pour calculer le prix total
    function updateRecap() {
        const checkinDate = new Date(formInputs.checkin.value);
        const checkoutDate = new Date(formInputs.checkout.value);
        const nbNuits = (checkoutDate - checkinDate) / (1000 * 60 * 60 * 24);
        const nbVoyageurs = parseInt(formInputs.guests.value) || 1;

        if (isNaN(nbNuits) || nbNuits <= 0) {
            summaryContainer.innerHTML = "<p>Veuillez entrer des dates valides.</p>";
            return;
        }

        // Calcul des coûts
        const totalChambre = nbNuits * prixParNuit;
        let totalOptions = 0;
        let optionsHTML = "<h4>Options :</h4>";

        formInputs.options.forEach(option => {
            if (option.checked) {
                const optionPrix = tarifsOptions[option.value] || 0;
                const optionTotal =
                    option.value === "petit_dejeuner" || option.value === "spa"
                        ? optionPrix * nbNuits * nbVoyageurs
                        : optionPrix * nbNuits;

                totalOptions += optionTotal;
                optionsHTML += `<p>${option.value.replace("_", " ")} : ${optionTotal.toFixed(2)} €</p>`;   
            }
        });

        if (totalOptions === 0) {
            optionsHTML += "<p>Aucune option sélectionnée.</p>";
        } else {
            optionsHTML += `<p>Total options : ${totalOptions} €</p>`;
        }

        const fraisSejour = fraisSejourParNuit * nbNuits;
        const totalFinal = totalChambre + totalOptions + fraisSejour;

        // Mise à jour du récapitulatif
        summaryContainer.innerHTML = `
            <h3>Récapitulatif de la commande</h3>
            <p>Prix par nuit : ${prixParNuit.toFixed(2)} €</p>
            <p>Nombre de nuits : ${nbNuits}</p>
            <p>Prix total chambre : ${totalChambre.toFixed(2)} €</p>
            ${optionsHTML}
            <p>Frais de séjour : ${fraisSejour.toFixed(2)} €</p>
            <p><strong>Total final : ${totalFinal.toFixed(2)} €</strong></p>
            <p><em>Dont TVA : ${(totalFinal * 0.1).toFixed(2)} €</em></p>
            <button type="submit" id="confirm-button-right">Confirmer</button>
        `;
    }

    // Fonction pour envoyer les données au serveur
    function sendData() {
        // Calculer les options et le prix total
        const options = [];
        formInputs.options.forEach(option => {
            if (option.checked) {
                options.push(option.value);
            }
        });

        const nbNuits = (new Date(formInputs.checkout.value) - new Date(formInputs.checkin.value)) / (1000 * 60 * 60 * 24);
        const nbVoyageurs = parseInt(formInputs.guests.value) || 1;
        const totalOptions = options.reduce((total, option) => {
            const optionPrix = tarifsOptions[option] || 0;
            return total + (option === "petit_dejeuner" || option === "spa"
                ? optionPrix * nbNuits * nbVoyageurs
                : optionPrix * nbNuits);
        }, 0);
        const totalFinal = nbNuits * prixParNuit + totalOptions + fraisSejourParNuit * nbNuits;

        // Préparer les données pour l'envoi
        const data = {
            options: options,
            prix_total: totalFinal.toFixed(2)
        };
        console.log("appelée")
        // Envoi des données via fetch
        fetch("reservation.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("Réservation confirmée !");
                showFinalMessageModal();
            } else {
                alert("Erreur : " + result.message);
            }
        })
        .catch(error => {
            console.error("Erreur lors de l'envoi des données :", error);
            alert("Une erreur est survenue. Veuillez réessayer.");
        });
    }


    // Écouteurs d'événements pour mettre à jour le récapitulatif
    formInputs.checkin.addEventListener("change", updateRecap);
    formInputs.checkout.addEventListener("change", updateRecap);
    formInputs.guests.addEventListener("input", updateRecap);
    formInputs.options.forEach(option =>
        option.addEventListener("change", updateRecap)
    );

    // Initialiser le récapitulatif
    updateRecap();

    // Boutons fenêtres pop-up

    // Sélection des éléments
    const confirmButtonLeft = document.getElementById("confirm-button-left");
    const confirmButtonRight = document.getElementById("confirm-button-right");
    const confirmationModal = document.getElementById("confirmation-modal");
    const finalMessageModal = document.getElementById("final-message-modal");
    const modalNo = document.getElementById("modal-no");
    const modalYes = document.getElementById("modal-yes");
    const homeFinalMessage = document.getElementById("home-final-message");
    
    // Afficher le modal de confirmation
    const showConfirmationModal = () => {
        confirmationModal.classList.remove("hidden");
    };
    
    // Cacher le modal de confirmation
    const hideConfirmationModal = () => {
        confirmationModal.classList.add("hidden");
    };
    
    // Afficher le modal final
    const showFinalMessageModal = () => {
        hideConfirmationModal();
        const finalMessage = document.getElementById("final-message");
        finalMessage.textContent = "Votre réservation a été confirmée ! Merci pour votre confiance.";
        finalMessageModal.classList.remove("hidden");
    };
    
    // Fermer le modal final
    const goToHomePage = () => {
        window.location.href = "../index.php";
    };
    
    // Ajouter les événements
    confirmButtonLeft?.addEventListener("click", () => {
        console.log("Bouton gauche cliqué !");
        showConfirmationModal();
    });
        
    confirmButtonRight?.addEventListener("click", showConfirmationModal);
    modalNo?.addEventListener("click", hideConfirmationModal);
    modalYes?.addEventListener("click", sendData,showFinalMessageModal);
    homeFinalMessage?.addEventListener("click", goToHomePage);

});

