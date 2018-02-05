<?php
/**
 * Vue Liste des frais au forfait
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?> <?php 
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);    
?>
<div class="row">
    <div class="col-md-4">
        <div>Sélectionner un mois : </div>
    </div>
     <div class="col-md-4">
        <form action="index.php?uc=validerFrais&action=validerMajFraisForfait" 
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
    
    if($leVisiteur == $visiteurASelectionner && $action == "validerMajFraisForfait"  ){ ?>
    <option selected value="<?php echo $leVisiteur ?>"> <?php echo $leVisiteurNom ?> <?php echo $leVisiteurPrenom ?></option>
<?php }
while ($donnees = $reponse->fetch())
{ ?>
    
           <option value="<?php echo $donnees['id']?>"> <?php echo $donnees['nom'] ?> <?php echo $donnees['prenom'] ?></option>
<?php }


 
$reponse->closeCursor();
 
?>
</select> <?php if($action = "validerMajFraisForfait" && isset($_POST['lstMois'])){
              echo $_POST['lstMois'];
}

              ?>
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
<div class="row">    
    <h2>Renseigner ma fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=validerFrais&action=validerMajFraisForfait" 
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
                <input type="hidden" name="element" value="1">
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>