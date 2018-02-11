<?php
/**
 * Vue Validation des fiches de frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */
?> <?php 
// récupération de l'action à faire dans l'URL
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);  

?>
<div class="row">
    <div class="col-md-4">
        <div>Choisir le visiteur : </div>
    	</div>

</div>   <form action="index.php?uc=validerFrais&action=validerMajFraisForfait" method="post" role="form"> 
              <select id="lstVisiteur" name="lstVisiteur">
              
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

 // Affichage de la sélection des visiteurs selon l'action et l'utilisateur déjà selectionné
     if($action == "selectionnerMois") {
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

 
<select id="lstMois" name="lstMois">
 <?php  // Affichage de la selection des mois selon l'action et le mois déjà selectionné
 if($action == "validerMajFraisForfait"){
    $lesMois = $pdo-> getLesMoisDisponibles($idVisiteur);
    foreach ($lesMois as $unMois) {
        $mois = $unMois['mois'];
        $numAnnee = $unMois['numAnnee'];
        $numMois = $unMois['numMois'];
        if ($mois == $moisASelectionner) {
            ?>  
            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } 
        else {
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
         $numAnneeSelection = substr($moisASelectionner, 0, 4); 
         $numMoisSelection = substr($moisASelectionner, 4, 2);
        
     }
     $reponse5->closeCursor();   
     $lesMois = $pdo-> getLesMoisDisponibles($idVisiteur);
     foreach ($lesMois as $unMois) { // Selection des mois à afficher
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
 } ?>    
 </select> 
 <button class="btn btn-success" type="submit">Selectionner</button>
  </form>
  
<?php // Affichage de la fiche de frais selon l'action, l'utilisateur, le mois et si une fiche à été trouvé pour ces valeurs
if($action == "validerMajFraisForfait") { 
    if($ficheFraisTrouver == 1) {
?>
<div class="row">    
    <h2>Valider la fiche de frais 
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
       
        <?php // Affichage et formulaire permettant de voir les lignes hors forfait pour la ligne concernée, un bouton permettra de supprimer une ligne en y ajoutant "REFUSE : " dans le libellé, un autre permettra de reporter d'un mois, une ligne
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant']; 
            $id = $unFraisHorsForfait['id']; 
            ?> 
    <form method="post" action="index.php?uc=validerFrais&action=validerMajFraisForfait" role="form">
    <table class="table table-bordered table-responsive">
        <tr>
            <th class="date">Date (JJ/MM/AAAA)</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>   
            <th class='selection'></th>               
        </tr>
            
            <tr>
                <td><input type="text" name="date" value="<?php echo $date ?>" maxlength="10" readonly></td>
                <td><input type="text" name="libelle" size="50" value="<?php echo $libelle ?>" readonly></td>
                <td><input type="text" name="montant" maxlength="11" value="<?php echo $montant ?>" readonly></td>
                 <td>               
                 <button class="btn btn-success" type="submit" name="idReporterLigneHorsForfait" value="<?php echo $id ?>" onclick="return confirm('Voulez-vous vraiment reporter ce frais ?');">Reporter</button> 
                 <button class="btn btn-danger"  type="submit" name="idSuppressionLigneHorsForfait" value="<?php echo $id ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce frais ?');">Supprimer</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button></td>
                <input type="hidden" name="lstMois" value="<?php echo $leMois ?>">
				<input type="hidden" name="lstVisiteur" value="<?php echo $leVisiteur ?>">
            </tr> 
         </form> <?php 
         } ?>
        
    </table>
       <?php // Permet de valider une fiche en prenant en compte la date de modification et en passant l'état de la fiche à validé si l'utilisateur le confirme. ?>
</div> Nombre de justificatifs : <input type="text" name="nbJustificatif" value="<?php echo $nbJustificatifs ?>" size="5" readonly> 
<form method="post" 
              action="index.php?uc=validerFrais&action=validerMajFraisForfait" 
              role="form">
              <input type="hidden" name="lstMois" value="<?php echo $leMois ?>">
			  <input type="hidden" name="lstVisiteur" value="<?php echo $leVisiteur ?>">
              <button class="btn btn-success" type="submit" name="validation"
               onclick="return confirm('Voulez-vous confirmer ?');">Valider</button>
              </form>
	<?php }
    else {
     echo "Pas de fiche de frais pour ce visiteur ce mois.";
    }
} 
?>
