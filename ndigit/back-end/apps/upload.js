// Barre de progression du téléversement
const form = document.getElementById("uploadForm");
const progressBar = document.getElementById("progressBar");
const progressContainer = document.querySelector(".progress");

form.addEventListener("submit", function(event) {
    event.preventDefault();

    let fileInput = document.getElementById("fichierUpload");
    let file = fileInput.files[0];
    
    // Vérifier si un fichier est sélectionné
    if (!file) {
        alert("Veuillez sélectionner un fichier.");
        return;
    }

    // Vérifier la taille du fichier (ne doit pas dépasser 500 Mo)
    const maxFileSize = 500 * 1024 * 1024; // 500 Mo en octets
    if (file.size > maxFileSize) {
        alert("Le fichier ne doit pas dépasser 500 Mo.");
        return;
    }

    // Vérifier que le fichier est bien au format ZIP
    const allowedFileTypes = ["application/zip"];
    const fileExtension = file.name.split('.').pop().toLowerCase(); // Extraire l'extension du fichier
    if (!allowedFileTypes.includes(file.type) && fileExtension !== "zip") {
        alert("Le fichier doit être au format ZIP.");
        return;
    }

    // Afficher la barre de progression
    progressContainer.classList.remove("d-none");
    progressBar.style.width = "0%"; // Réinitialiser la largeur de la barre à 0
    progressBar.innerText = "0%";   // Réinitialiser le texte de la barre de progression

    let formData = new FormData();
    formData.append("file", file);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "upload2.php", true);

    // Suivi de la barre de progression du téléversement
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + "%";
            progressBar.innerText = percent + "%";  // Afficher le pourcentage de progression
        }
    };

    // Quand le téléversement est terminé avec succès
    xhr.onload = function() {
        if (xhr.status == 200) {
            alert("Fichier téléversé avec succès !");
        } else {
            alert("Erreur lors du téléversement du fichier.");
        }
        progressContainer.classList.add("d-none");  // Masquer la barre de progression après le téléversement
    };

    // Quand une erreur se produit
    xhr.onerror = function() {
        alert("Une erreur est survenue lors du téléversement du fichier.");
        progressContainer.classList.add("d-none");  // Masquer la barre de progression après une erreur
    };

    // Envoyer les données
    xhr.send(formData);
});
