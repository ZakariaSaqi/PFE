<?php
require('../connexion.php');
if (isset($_POST['btn'])) {
    
    $titre = trim(ucfirst($_POST['titre']));
    $date = $_POST['date'];
    $description = trim($_POST['description']);
    $centre=$_POST['centre'];
    $num = rand(0, 10000000000000);
    $img = "../imgsmembre&medecin&projets/imgprojets/$titre$num.jpeg";
    $image = move_uploaded_file($_FILES["imgprj"]["tmp_name"], $img); 
    $req = "INSERT INTO campagne (titre_campagne, description_campagne, photo_campagne, date_campagne,id_centre) 
    VALUES ('$titre', '$description', '$img', '$date',$centre)";
    $req3 = "select * from membre m, centre c where m.ville = c.ville_centre and c.id_centre=$centre";
    $res3 = $pdo -> query($req3);
    while($row3 = $res3 ->fetch(PDO :: FETCH_ASSOC)){
        $notif =$row3['prenom_membre']." ".$row3['prenom_membre']." il y 
        une nouvelle campagne de don de sang dans votre ville le $date, Participez maintenant !";
        $req_notif = "insert into notification (notif_name, notif_message, active, id_membre) values('Nouvelle campagne', '$notif', 1,".$row3['id_membre'].")";
        $res_notif = $pdo -> query($req_notif);
    }
    $notif_centre = "L\'admin a ajouté une nouvelle campagne à votre centre le $date";
						$req_notif_centre = "insert into notification (notif_name, notif_message, active, id_centre) values('Nouvelle campgne', '$notif_centre', 1, $centre)";
						$res_notif_centre = $pdo -> query($req_notif_centre);
    $res = $pdo->query($req);
    header('Location:projets.php');

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="styles.css" />
    <link href="styleAjout.css?s=<?php echo date(time()) ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.1.1/css/all.css"
        integrity="sha384-/frq1SRXYH/bSyou/HUp/hib7RVN1TawQYja658FEOodR/FQBKVqT9Ol+Oz3Olq5" crossorigin="anonymous" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <link rel="shortcut icon" href="../assets/img/iconRed.svg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/fonts/css/all.css">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">


    <script src="..assets/js/bootstrap.min.js"></script>
    <title>Ajouter Projet</title>
</head>

<body>
    <?php include('sidebar.php') ?>
    <section class="ajout-form">
        <div class="container-lg">
            <form action="" method="post" class="no-styles" enctype="multipart/form-data">
                <div class="row">
                    <div class="col mb-4 ">
                        <h1>Projets <i class="fa-solid fa-angle-right"></i> Ajouter</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-4"><input type="text" class="form-control" name="titre" placeholder="Titre"
                            require></div>
                    <div class="col mb-4 search d-flex align-items-center justify-content-between">
                        <label for="centre">Centre</label>
                        <select  name="centre" class="form-control" style="width: max-content; height: 42px;" required>
                        <option disabled="disabled" selected="selected">Choisir</option>
                                    <?php
                                    $reqcentre = "SELECT * FROM centre";
                                    $rescentre = $pdo->query($reqcentre);
                                    while ($rowcentre = $rescentre->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        
						                <option value="<?=$rowcentre['id_centre']?>"><?=$rowcentre['nom_centre']?></option>
                                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-4"><label for="exampleInputPassword1">Date</label><input type="date" name="date"
                            class="form-control" placeholder="Date" require></div>
                </div>
                <div class="row">
                    <div class="col mb-4">
                                <label for="imgprojet" class="img-input form-label d-flex flex-row justify-content-between form-control">
                                    <p id="imgLabel"> Ajouter Photo</p>
                                    <i class="fa-sharp fa-regular fa-image"></i>
                                    </label>
                                    <input class="" name="imgprj" type="file" id="imgprojet" style="display: none; visibility: none;">
                                </div>
                </div>
                <div class="row">
                    <div class="col mb-4">
                        <label for="exampleInputPassword1">Description</label>
                        <textarea class="form-control texterea" id="exampleFormControlTextarea1"
                            name="description"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-4 d-flex justify-content-center">
                        <button type="submit" name="btn" class="btn btn-primary m-0 ">Ajouter</button>
                    </div>
                </div>
        </div>
        </form>
    </section>

    <script>
    // // Récupérer l'élément input et l'élément label
    const input = document.getElementById('imgprojet');
    const label = document.getElementById('imgLabel');

    // Ajouter un événement change à l'input
    input.addEventListener('change', function() {
        // Vérifier s'il y a un fichier sélectionné
        if (input.files.length > 0) {
            // Récupérer le nom du fichier
            const fileName = input.files[0].name;
            // Afficher le nom du fichier dans le label
            label.textContent = fileName;
        } else {
            // Aucun fichier sélectionné, réinitialiser le label
            label.textContent = 'Ajouter Photo';
        }
    });
</script>
</body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="../assets/js/bootstrap-select.min.js"></script>


</html>