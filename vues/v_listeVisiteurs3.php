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
$reponse2 = $bdd->query('SELECT * FROM visiteur ORDER BY id ASC LIMIT 1');
$reponse3 = $bdd->query('SELECT * FROM visiteur WHERE id NOT IN (SELECT MIN(id) FROM visiteur)');
$reponse4 = $bdd->query('SELECT * FROM visiteur WHERE id != "'.$leVisiteur.'"');

    
     if($action == "selectionnerMois") { ?> <?php 
	   while($donnees2 = $reponse2->fetch()){ ?>
    		<option selected value="<?php echo $donnees2['id'] ?>"> <?php echo $donnees2['nom'] ?> <?php echo $donnees2['prenom'] ?></option> <?php 
       }
        $reponse2->closeCursor();
        while ($donnees3 = $reponse3->fetch()) { ?>
           <option value="<?php echo $donnees3['id']?>"> <?php echo $donnees3['nom'] ?> <?php echo $donnees3['prenom'] ?></option>
		<?php 
        }
        $reponse3->closeCursor();
     }
     else if($action == "validerMajFraisForfait") {
         ?>
       <?php 
            while ($donnees = $reponse->fetch()) {   
                if($donnees['id'] == $visiteurASelectionner) { ?>
    		<option selected value="<?php echo $leVisiteur ?>"> <?php echo $leVisiteurNom ?> <?php echo $leVisiteurPrenom ?></option> 
    		<?php }
    		else { 
    		    ?>
           		<option value="<?php echo $donnees['id']?>"> <?php echo $donnees['nom']; ?> <?php echo $donnees['prenom'] ?></option> <?php 
            }
            $reponse4->closeCursor();
        }
        }


 
$reponse->closeCursor();
 
?>
</select>
<select id="lstMois" name="lstMois" onChange="javascript:document.getElementById('mois').value = this.value;">
 <?php  
 
 if($action == "validerMajFraisForfait"){
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
 }
 else {
     $reponse5 = $bdd->query('SELECT * FROM fichefrais ORDER BY mois DESC LIMIT 1');
     while ($donnees5 = $reponse5->fetch())
     {
         $moisASelectionner = $donnees5['mois'];
         $numAnneeSelection = substr($mois, 0, 4); 
         $numMoisSelection = substr($mois, 4, 2);
        
     }
     $reponse5->closeCursor();   
     foreach ($lesMois as $unMois) {
         $mois = $unMois['mois'];
         $numAnnee = $unMois['numAnnee'];
         $numMois = $unMois['numMois'];
                         if ($mois == $moisASelectionner) { ?>
     
      <option selected value="<?php echo $moisASelectionner ?>">
                                <?php echo $numMoisSelection . '/' . $numAnneeSelection ?> </option>   <?php  
                         }
                          else { ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                         }
                        }             
 }
                    ?>    </select> <button class="btn btn-success" type="submit">Selectionner</button>
        </form>
    </div>
</div>
<input type="text" name="visiteur" value="" id="visiteur"/> <!-- test pour voir si on peut prendre la valeur selectionner -->
<input type="text" name="mois" value="" id="mois"/>
<?php echo $moisASelectionner; 
echo $leVisiteur; 
if($action == "validerMajFraisForfait") { 
    if($ficheFraisTrouver == 1) {
?>
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
                if(isset($lesFraisForfait[0])){
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
                    <?php }
                }
                else{
                echo "Aucun élément forfaitisé trouvé."; 
                }
                ?>
                <input type="hidden" name="element" value="1">
					<input type="hidden" name="lstVisiteur" value="<?php echo $leVisiteur ?>">
                	<input type="hidden" name="lstMois" value="<?php echo $leMois ?>">
                
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading">Descriptif des éléments hors forfait </div>
       
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant']; 
            $id = $unFraisHorsForfait['id']; 
            ?> <form method="post" 
              action="index.php?uc=validerFrais&action=validerMajFraisForfait" 
              role="form">
    <table class="table table-bordered table-responsive">
        <tr>
            <th class="date">Date (JJ/MM/AAAA)</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>                
        </tr>
           
            <tr>
                <td><input type="text" name="date" value="<?php echo $date ?>" maxlength="10"></td>
                <td><input type="text" name="libelle" size="50" value="<?php echo $libelle ?>"></td>
                <td><input type="text" name="montant" maxlength="11" value="<?php echo $montant ?>"></td>
                <input type="hidden" name="lstMois" value="<?php echo $leMois ?>">
				<input type="hidden" name="lstVisiteur" value="<?php echo $leVisiteur ?>">
                
                 <td>               
                 <button class="btn btn-success" type="submit" name="idSuppressionLigneHorsForfait" value="<?php echo $id ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce frais ?');">Supprimer</button> 
                <button class="btn btn-danger" type="reset">Réinitialiser</button></td>
                           
            </tr>
            <?php echo $id; 
            echo $date;  ?> </form> <?php 
        } ?>
        
    </table>
       
</div> Nombre de justificatifs : <input type="text" name="nbJustificatif" value="<?php echo $nbJustificatifs ?>" size="5"> 
<form method="post" 
              action="index.php?uc=validerFrais&action=validerMajFraisForfait" 
              role="form">
              <input type="hidden" name="lstMois" value="<?php echo $leMois ?>">
			  <input type="hidden" name="lstVisiteur" value="<?php echo $leVisiteur ?>">
              <button class="btn btn-success" type="submit" name="validation" onclick="return confirm('Voulez-vous confirmer ?');">Valider</button>
              </form>
<?php }
    } 
?>
