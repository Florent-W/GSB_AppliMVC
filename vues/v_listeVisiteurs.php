
<?php 
$action2 = filter_input(INPUT_GET, 'action2', FILTER_SANITIZE_STRING);
if(isset($variable)){
if($variable = 1){
    echo "n";
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    if (lesQteFraisValides($lesFrais)) {
        $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    break;
}
}
$variable = 0;
?>
<div class="row">
    <div class="col-md-4">
        <div>Sélectionner un mois : </div>
    </div>
     <div class="col-md-4">
        <form action="index.php?uc=validerFrais&action=selectionnerMois&action2=selectionVisiteur" 
              method="post" role="form">
              <select id="lstVisiteur" name="lstVisiteur" onChange="javascript:document.getElementById('visiteur').value = this.value">
              
<?php
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=gsb_frais', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}
$reponse = $bdd->query('SELECT * FROM visiteur');

while ($donnees = $reponse->fetch())
{
    ?>
           <option value="<?php echo $donnees['id']?>"> <?php echo $donnees['nom'] ?> <?php echo $donnees['prenom'] ?></option>
<?php
}
 
$reponse->closeCursor();
 
?>
</select> 
<select id="lstMois" name="lstMois" onChange="javascript:document.getElementById('mois').value = this.value;">
 <?php
 foreach ($lesMois as $unMois) {
$mois = $unMois['mois'];
$numAnnee = $unMois['numAnnee'];
$numMois = $unMois['numMois'];
if ($mois == $moisASelectionner) {
            ?>  
            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?>    </select> <button class="btn btn-success" type="submit">Selectionner</button>
        </form>
    </div>
</div>
<input type="text" name="visiteur" value="" id="visiteur"/> <!-- test pour voir si on peut prendre la valeur selectionner -->
<input type="text" name="mois" value="" id="mois"/>
<?php 
if($action2 = "selectionVisiteur") {
    if(isset($_POST['variable']))
    $variable = $_POST['variable'];
          echo $variable;
    
        echo "a"; 
        if (isset($_POST['lstVisiteur']) AND isset($_POST['lstMois'])){
            $idVisiteur = htmlspecialchars($_POST['lstVisiteur']);
            $mois = htmlspecialchars($_POST['lstMois']);
            
            echo $mois;
            echo $idVisiteur;
            echo $idVisiteur;
            
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            $lesFraisForfait = $pdo->getLesFraisForfait(
                $idVisiteur, $mois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais(
                $idVisiteur, $mois);
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
            
            ?>
 <div class="row">    
    <h2>Renseigner ma fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>ElÃ©ments forfaitisÃ©s</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=validerFrais&action=selectionnerMois&action2=selectionVisiteur" 
              role="form">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button>
                <input type="hidden" name="variable" id="variable" value="1">
            </fieldset>
        </form>
    </div>
</div>
<div class="panel panel-primary">
<div class="panel-heading">Fiche de frais du mois
<?php echo $mois ?> : </div>
    <div class="panel-body">
        <strong><u>Etat :</u></strong> <?php echo $libEtat ?>
        depuis le <?php echo $dateModif ?> <br> 
        <strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">Elements forfaitisés</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle']; ?>
                <th> <?php echo htmlspecialchars($libelle) ?></th>
                <?php
            }
            ?>
        </tr>
        <tr>
         
        </tr>
    </table>
</div>
<div class="panel panel-info">
    <div class="panel-heading">Descriptif des Elements hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reÃ§us</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">LibellÃ©</th>
            <th class='montant'>Montant</th>                
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant']; ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
            </tr>
            <?php
        }
        }
        ?>
    </table>
</div> <?php
}

 ?>