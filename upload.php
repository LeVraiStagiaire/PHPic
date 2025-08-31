<?php

include 'config/config.php';

session_start();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Upload - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a href="index.php"><img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"></a>
            <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE; ?></a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link active" aria-current="page" href="upload.php">Upload</a><?php } ?>
                <?php if ($_SESSION['role'] == "administrators") { ?><a class="nav-link" href="admin.php">Admin</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <form enctype="multipart/form-data" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <input type="hidden" name="path" value="<?php echo str_replace(IMAGES_PATH, "", $_GET['path']); ?>">
                <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="123">
                <label for="formFile" class="form-label">Sélectionnez une image à uploader</label>
                <input class="form-control" type="file" id="formFile" name="formFile[]" multiple>
            </div>
            <div class="mb-3"><span>Les images seront uploadées dans le dossier <?php if ($_GET['path'] == "") {
                                                                                    echo "racine";
                                                                                } else {
                                                                                    echo $_GET['path'];
                                                                                } ?></span></div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
            <div id="uploadStatus"></div>
            <button type="button" style="display: none;" class="btn btn-secondary" id="back">Retour</button>

            <div id="progressBarsContainer"></div>
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            document.querySelector('button[type="submit"]').disabled = true;
            document.querySelector('button[type="submit"]').textContent = 'Upload en cours...';
            document.querySelector('button[type="submit"]').classList.add('disabled');

            const form = e.target;
            const files = form.querySelector('input[type="file"]').files;

            if (files.length === 0) {
                alert('Veuillez sélectionner un fichier');
                return;
            }

            // Vider les anciennes barres de progression
            const progressContainer = document.getElementById('progressBarsContainer');
            progressContainer.innerHTML = '';

            var remain = files.length;

            Array.from(files).forEach((file, index) => {
                // Créer une barre de progression pour chaque fichier
                const progressDiv = document.createElement('div');
                progressDiv.classList.add('progress-container');
                progressDiv.innerHTML = `
                        <div class="alert alert-primary" role="alert">
                            ${file.name}
                            <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" id="progress-${index}" style="width: 0%">0%</div>
                            </div>
                        </div>
                    `;
                progressContainer.appendChild(progressDiv);
            });

            // Fonction pour uploader les fichiers avec un nombre maximum de uploads simultanés
            function uploadFilesWithConcurrency(files, maxConcurrent = 5) {
                let currentIndex = 0;
                let activeUploads = 0;
                let completed = 0;

                function uploadNext() {
                    if (currentIndex >= files.length) return;
                    if (activeUploads >= maxConcurrent) return;

                    const file = files[currentIndex];
                    const fileIndex = currentIndex;
                    currentIndex++;
                    activeUploads++;

                    document.getElementById('uploadStatus').textContent = `Téléchargement en cours... (${completed}/${files.length})`;

                    const formData = new FormData();
                    formData.append('formFile', file);
                    formData.append('path', document.querySelector('input[name="path"]').value);

                    const xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(event) {
                        if (event.lengthComputable) {
                            const percentComplete = Math.round((event.loaded / event.total) * 100);
                            const progressBar = document.getElementById(`progress-${fileIndex}`);
                            progressBar.style.width = percentComplete + '%';
                            progressBar.textContent = percentComplete + '%';
                        }
                    });

                    xhr.addEventListener('load', function() {
                        const progressBar = document.getElementById(`progress-${fileIndex}`);
                        progressBar.textContent = 'Terminé';
                        progressBar.style.background = '#4caf50';
                        completed++;
                        activeUploads--;
                        document.getElementById('uploadStatus').textContent = `Téléchargement en cours... (${completed}/${files.length})`;
                        fetch('gen-thumb.php?image=<?php echo str_replace(IMAGES_PATH, "", $_GET['path']); ?>' + file.name, {
                            method: 'GET'
                        });
                        if (completed === files.length) {
                            document.getElementById('back').style.display = 'block';
                        } else {
                            uploadNext();
                        }
                    });

                    xhr.addEventListener('error', function() {
                        const progressBar = document.getElementById(`progress-${fileIndex}`);
                        progressBar.textContent = 'Erreur';
                        progressBar.style.background = '#f44336';
                        completed++;
                        activeUploads--;
                        if (completed === files.length) {
                            document.getElementById('back').style.display = 'block';
                        } else {
                            uploadNext();
                        }
                    });

                    xhr.open('POST', 'handle-upload.php', true);
                    xhr.send(formData);

                    // Lancer d'autres uploads si possible
                    if (activeUploads < maxConcurrent) {
                        uploadNext();
                    }
                }

                // Démarrer les premiers uploads
                for (let i = 0; i < maxConcurrent && i < files.length; i++) {
                    uploadNext();
                }
            }

            // Remplacer l'appel à uploadBatch par la nouvelle fonction
            uploadFilesWithConcurrency(files, 5);

            uploadBatch(files);


            document.getElementById('back').addEventListener('click', function() {
                window.history.back();
            });
        });
    </script>
</body>

</html>