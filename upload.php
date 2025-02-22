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

            <div id="progressBarsContainer"></div>
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const files = form.querySelector('input[type="file"]').files;

            if (files.length === 0) {
                alert('Veuillez sélectionner un fichier');
                return;
            }

            // Vider les anciennes barres de progression
            const progressContainer = document.getElementById('progressBarsContainer');
            progressContainer.innerHTML = '';

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

                // Envoyer chaque fichier individuellement
                const formData = new FormData();
                formData.append('formFile', file);

                const xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        const percentComplete = Math.round((event.loaded / event.total) * 100);
                        const progressBar = document.getElementById(`progress-${index}`);
                        progressBar.style.width = percentComplete + '%';
                        progressBar.textContent = percentComplete + '%';
                    }
                });

                xhr.addEventListener('load', function() {
                    const progressBar = document.getElementById(`progress-${index}`);
                    progressBar.textContent = 'Terminé';
                    progressBar.style.background = '#4caf50'; // Vert pour succès
                });

                xhr.addEventListener('error', function() {
                    const progressBar = document.getElementById(`progress-${index}`);
                    progressBar.textContent = 'Erreur';
                    progressBar.style.background = '#f44336'; // Rouge pour erreur
                });

                formData.append('path', form.querySelector('input[name="path"]').value);
                xhr.open('POST', 'handle-upload.php', true);
                xhr.send(formData);
            });
            fetch('check-files.php?path=<?php echo str_replace(IMAGES_PATH, "", $_GET['path']); ?>', {
                method: 'GET'
            });
        });
    </script>
</body>

</html>