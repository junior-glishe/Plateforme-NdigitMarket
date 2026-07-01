<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ajouter un produit - NDIGITMARKET</title>

</head>

<body>

    <?php 

    $pageTitle = 'Ajouter un produit';

    require('header.php'); 

    ?>

   

    <div class="page-body">

        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="col-sm-10 m-auto">

                        <div class="card">

                            <div class="card-header">

                                <h5>Ajouter un nouvel article</h5>

                                <p class="text-muted">Remplissez tous les champs pour ajouter un produit téléchargeable</p>

                            </div>

                            <div class="card-body">

                                <form id="uploadForm" class="theme-form theme-form-2 mega-form" method="POST" action="upload.php" enctype="multipart/form-data">

                                    

                                    <div class="row">

                                        <!-- Colonne gauche -->

                                        <div class="col-md-6">

                                            <!-- Nom de l'article -->

                                            <div class="mb-4">

                                                <label class="form-label">Nom de l'article <span class="text-danger">*</span></label>

                                                <input class="form-control" type="text" name="nom_article" id="nom_article" 

                                                       placeholder="Ex: Template WordPress Premium" required>

                                                <small class="text-muted">Le nom doit être unique et descriptif</small>

                                            </div>



                                            <!-- Lien aperçu -->

                                            <div class="mb-4">

                                                <label class="form-label">Lien d'aperçu <span class="text-muted">(optionnel)</span></label>

                                                <input class="form-control" type="url" name="aperçu" id="aperçu" 

                                                       placeholder="https://demo.example.com">

                                                <small class="text-muted">Lien vers une démo en ligne du produit</small>

                                            </div>



                                            <!-- Prix -->

                                            <div class="mb-4">

                                                <label class="form-label">Prix <span class="text-danger">*</span></label>

                                                <div class="input-group">

                                                    <input class="form-control" type="number" id="prix" name="prix" 

                                                           placeholder="0" step="0.01" required>

                                                    <span class="input-group-text">CFA</span>

                                                </div>

                                                <small class="text-muted">Mettre 0 pour un produit gratuit</small>

                                            </div>



                                            <!-- Prix réduction -->

                                            <div class="mb-4">

                                                <label class="form-label">Prix promotionnel <span class="text-muted">(optionnel)</span></label>

                                                <div class="input-group">

                                                    <input class="form-control" type="number" id="prix_reduction" name="prix_reduction" 

                                                           placeholder="0" step="0.01">

                                                    <span class="input-group-text">CFA</span>

                                                </div>

                                                <small class="text-muted">Prix après réduction (doit être inférieur au prix normal)</small>

                                            </div>



                                            <!-- Auteur -->

                                            <div class="mb-4">

                                                <label class="form-label">Auteur <span class="text-danger">*</span></label>

                                                <input class="form-control" type="text" name="auteur" id="auteur" 

                                                       placeholder="Ex: John Doe" value="<?php echo $_SESSION['nom'] ?? 'Admin'; ?>" required>

                                            </div>

                                        </div>



                                        <!-- Colonne droite -->

                                        <div class="col-md-6">

                                            <!-- Image du produit -->

                                            <div class="mb-4">

                                                <label class="form-label">Image du produit <span class="text-danger">*</span></label>

                                                <div class="upload-area" id="uploadArea">

                                                    <input class="form-control" type="file" name="imageProduit" id="imageProduit" required accept="image/jpeg,image/png,image/gif">

                                                    <div class="upload-preview text-center mt-2">

                                                        <img id="imagePreview" src="#" alt="Aperçu" class="img-thumbnail d-none" style="max-width: 200px; max-height: 200px;">

                                                    </div>

                                                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF - Max 2 Mo</small>

                                                    <div id="imageError" class="text-danger small mt-1" style="display: none;"></div>

                                                </div>

                                            </div>



                                            <!-- Catégorie -->

                                            <div class="mb-4">

                                                <label class="form-label">Catégorie <span class="text-danger">*</span></label>

                                                <select class="form-control" name="categorie" id="categorie" required>

                                                    <option value="">Sélectionnez une catégorie</option>

                                                    <?php

                                                    $query = $database->query("SELECT * FROM categories ORDER BY nom_categorie");

                                                    $categories = $query->fetchAll(PDO::FETCH_ASSOC);

                                                    foreach ($categories as $category) {

                                                        echo "<option value='" . $category['id'] . "'>" . htmlspecialchars($category['nom_categorie']) . "</option>";

                                                    }

                                                    ?>

                                                </select>

                                            </div>



                                            <!-- Sous-catégorie -->

                                            <div class="mb-4">

                                                <label class="form-label">Sous-catégorie <span class="text-muted">(optionnel)</span></label>

                                                <select class="form-control" name="sous_categorie[]" id="sous_categorie" multiple>

                                                    <option value="">Sélectionnez une sous-catégorie</option>

                                                </select>

                                                <small class="text-muted">Maintenez Ctrl pour sélectionner plusieurs</small>

                                            </div>



                                            <!-- Fichier ZIP -->

                                            <div class="mb-4">

                                                <label class="form-label">Fichier à téléverser (ZIP) <span class="text-danger">*</span></label>

                                                <div class="file-upload-area">

                                                    <input class="form-control" type="file" id="fichierUpload" name="fichierUpload" 

                                                           accept=".zip,application/zip" required>

                                                    <div class="progress-container mt-2" style="display: none;">

                                                        <div class="progress">

                                                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%">0%</div>

                                                        </div>

                                                    </div>

                                                    <small class="text-muted">Format ZIP uniquement - Taille max: 500 Mo</small>

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <!-- Description (pleine largeur) -->

                                    <div class="mb-4">

                                        <label class="form-label">Description détaillée <span class="text-danger">*</span></label>

                                        <textarea name="description" id="description" class="form-control" rows="10"></textarea>

                                        <small class="text-muted">Décrivez votre produit en détail (fonctionnalités, compatibilité, etc.)</small>

                                    </div>



                                    <!-- Boutons -->

                                    <div class="text-end mt-4">

                                        <button type="button" class="btn btn-outline-secondary me-2" onclick="history.back()">

                                            <i class="fa-regular fa-times"></i> Annuler

                                        </button>

                                        <button type="submit" class="btn btn-primary" id="submitBtn">

                                            <i class="fa-regular fa-cloud-upload"></i> Publier le produit

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- TinyMCE -->

    <script src="assets/js/tinymce/tinymce.min.js"></script>

    <script>

        tinymce.init({

            selector: '#description',

            height: 400,

            plugins: 'link lists image code table',

            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',

            menubar: false,

            branding: false

        });

    </script>



    <style type="text/css">

        /* Style général */

        .card {

            border-radius: 16px;

            border: 1px solid #e5e7eb;

            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);

        }

        

        .card-header {

            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);

            border-bottom: 1px solid #e5e7eb;

            padding: 25px 30px;

            border-radius: 16px 16px 0 0;

        }

        

        .card-header h5 {

            font-size: 20px;

            font-weight: 700;

            color: #1a2634;

            margin-bottom: 5px;

        }

        

        .card-body {

            padding: 30px;

        }

        

        .form-label {

            font-weight: 600;

            color: #1a2634;

            margin-bottom: 8px;

            font-size: 14px;

        }

        

        .form-control, .input-group-text {

            border-radius: 12px;

            border: 1px solid #e5e7eb;

            padding: 10px 15px;

            transition: all 0.3s;

        }

        

        .form-control:focus {

            border-color: #087d67;

            box-shadow: 0 0 0 3px rgba(8,125,103,0.1);

            outline: none;

        }

        

        .input-group-text {

            background: #f8f9fa;

        }

        

        /* Upload area */

        .upload-area {

            border: 2px dashed #e5e7eb;

            border-radius: 12px;

            padding: 20px;

            text-align: center;

            background: #f8f9fa;

            transition: all 0.3s;

        }

        

        .upload-area:hover {

            border-color: #087d67;

            background: #e8f5f2;

        }

        

        .upload-preview {

            margin-top: 15px;

        }

        

        .upload-preview img {

            border-radius: 8px;

            border: 1px solid #e5e7eb;

            padding: 5px;

            background: white;

        }

        

        /* Progress bar */

        .progress {

            height: 30px;

            border-radius: 15px;

            background: #f0f0f0;

        }

        

        .progress-bar {

            background: linear-gradient(90deg, #087d67, #0a9c7e);

            border-radius: 15px;

            color: white;

            font-weight: 600;

            display: flex;

            align-items: center;

            justify-content: center;

        }

        

        /* Boutons */

        .btn-primary {

            background: linear-gradient(135deg, #087d67 0%, #065a4a 100%);

            border: none;

            border-radius: 40px;

            padding: 12px 30px;

            font-weight: 600;

            transition: all 0.3s;

        }

        

        .btn-primary:hover {

            transform: translateY(-2px);

            box-shadow: 0 10px 25px rgba(8,125,103,0.3);

        }

        

        .btn-outline-secondary {

            border-radius: 40px;

            padding: 12px 30px;

            font-weight: 600;

            border: 1px solid #e5e7eb;

        }

        

        .btn-outline-secondary:hover {

            background: #f8f9fa;

            border-color: #d1d5db;

        }

        

        /* Messages d'erreur */

        .text-danger {

            color: #ef4444 !important;

        }

        

        /* Toast pour les alertes */

        .toast-container {

            position: fixed;

            top: 20px;

            right: 20px;

            z-index: 9999;

        }

    </style>



    <script type="text/javascript">

        // Charger les sous-catégories

        document.addEventListener("DOMContentLoaded", function() {

            let categorieSelect = document.getElementById("categorie");

            let sousCategorieSelect = document.getElementById("sous_categorie");



            categorieSelect.addEventListener("change", function() {

                let categorieId = this.value;

                sousCategorieSelect.innerHTML = "<option value=''>Sélectionnez une sous-catégorie</option>";



                if (categorieId) {

                    fetch("get_sous_categories.php?categorie_id=" + categorieId)

                        .then(response => response.json())

                        .then(data => {

                            if (data.length > 0) {

                                data.forEach(subcat => {

                                    let option = document.createElement("option");

                                    option.value = subcat.id;

                                    option.textContent = subcat.nom_sous_categorie;

                                    sousCategorieSelect.appendChild(option);

                                });

                            } else {

                                let option = document.createElement("option");

                                option.value = "";

                                option.textContent = "Aucune sous-catégorie disponible";

                                sousCategorieSelect.appendChild(option);

                            }

                        })

                        .catch(error => {

                            console.error("Erreur:", error);

                        });

                }

            });

        });



        // Aperçu de l'image

        const imageInput = document.getElementById("imageProduit");

        const imagePreview = document.getElementById("imagePreview");

        const imageError = document.getElementById("imageError");



        imageInput.addEventListener("change", function(event) {

            const file = event.target.files[0];



            if (file) {

                // Vérifier la taille (2 Mo max)

                if (file.size > 2 * 1024 * 1024) {

                    imageError.textContent = "L'image ne doit pas dépasser 2 Mo.";

                    imageError.style.display = "block";

                    imagePreview.classList.add("d-none");

                    return;

                }



                // Vérifier le format

                const validFormats = ["image/jpeg", "image/png", "image/gif"];

                if (!validFormats.includes(file.type)) {

                    imageError.textContent = "Format accepté: JPG, PNG, GIF uniquement.";

                    imageError.style.display = "block";

                    imagePreview.classList.add("d-none");

                    return;

                }



                imageError.style.display = "none";



                const reader = new FileReader();

                reader.onload = function(e) {

                    imagePreview.src = e.target.result;

                    imagePreview.classList.remove("d-none");

                };

                reader.readAsDataURL(file);

            }

        });



        // Upload du fichier avec barre de progression

        const form = document.getElementById("uploadForm");

        const progressContainer = document.querySelector(".progress-container");

        const progressBar = document.getElementById("progressBar");

        const submitBtn = document.getElementById("submitBtn");



        form.addEventListener("submit", function(event) {

            event.preventDefault();



            // Synchroniser TinyMCE

            const descriptionContent = tinymce.get('description').getContent();

            document.getElementById('description').value = descriptionContent;



            let fileInput = document.getElementById("fichierUpload");

            let file = fileInput.files[0];



            if (!file) {

                Swal.fire({

                    icon: 'error',

                    title: 'Erreur',

                    text: 'Veuillez sélectionner un fichier ZIP'

                });

                return;

            }



            // Vérifier la taille (500 Mo max)

            const maxFileSize = 500 * 1024 * 1024;

            if (file.size > maxFileSize) {

                Swal.fire({

                    icon: 'error',

                    title: 'Erreur',

                    text: 'Le fichier ne doit pas dépasser 500 Mo'

                });

                return;

            }



            // Vérifier le format

            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (fileExtension !== "zip") {

                Swal.fire({

                    icon: 'error',

                    title: 'Erreur',

                    text: 'Le fichier doit être au format ZIP'

                });

                return;

            }



            // Afficher la barre de progression

            progressContainer.style.display = "block";

            progressBar.style.width = "0%";

            progressBar.textContent = "0%";

            submitBtn.disabled = true;



            let formData = new FormData(form);

            let xhr = new XMLHttpRequest();

            xhr.open("POST", "upload.php", true);



            xhr.upload.onprogress = function(e) {

                if (e.lengthComputable) {

                    let percent = Math.round((e.loaded / e.total) * 100);

                    progressBar.style.width = percent + "%";

                    progressBar.textContent = percent + "%";

                }

            };



            xhr.onload = function() {

                if (xhr.status == 200) {

                    const response = JSON.parse(xhr.responseText);

                    

                    Swal.fire({

                        icon: response.status === "success" ? 'success' : 'error',

                        title: response.status === "success" ? 'Succès !' : 'Erreur',

                        text: response.message,

                        confirmButtonColor: '#087d67'

                    }).then(() => {

                        if (response.status === "success") {

                            window.location.href = "produits.php";

                        }

                    });

                } else {

                    Swal.fire({

                        icon: 'error',

                        title: 'Erreur',

                        text: 'Une erreur est survenue lors du téléversement'

                    });

                }

                progressContainer.style.display = "none";

                submitBtn.disabled = false;

            };



            xhr.onerror = function() {

                Swal.fire({

                    icon: 'error',

                    title: 'Erreur',

                    text: 'Une erreur est survenue lors du téléversement'

                });

                progressContainer.style.display = "none";

                submitBtn.disabled = false;

            };



            xhr.send(formData);

        });

    </script>



    <?php require('footer.php'); ?>

</body>

</html>