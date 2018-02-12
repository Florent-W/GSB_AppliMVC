<?php
/**
 * Vue suivi du paiement des fiches de frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */
?>  <?php 
// récupération de l'action à faire dans l'URL
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);  
  echo "debut " . $idUtilisateur;
?>
<h2>Mes fiches de frais</h2>
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner un mois : </h3>
    </div>
    <div class="col-md-4">
        <form action="index.php?uc=suivrePaiement&action=voirEtatFrais" 
              method="post" role="form">
            <div class="form-group">
                <label for="lstMois" accesskey="n">Mois : </label>
                <select id="lstMois" name="lstMois" class="form-control">
                    <?php 
                 
        foreach($tousUtilisateurs as $utilisateur) {
            $lesMois = $pdo->getLesMoisDisponiblesFichesValides($utilisateur['id']);
            $nomUtilisateur = $utilisateur['nom'];
            $prenomUtilisateur = $utilisateur['prenom'];
            $id = $utilisateur['id']; 
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisUtilisateur && $id == $idUtilisateur) {
                            ?>
            <option selected value="<?php echo $id . '/' . $mois ?>">
                                <?php echo  $nomUtilisateur . ' ' . $prenomUtilisateur . ' : ' . $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } 
        else {
             ?>
        	 <option value="<?php echo $id . '/' . $mois ?>">
        	 <?php echo $nomUtilisateur . ' ' . $prenomUtilisateur . ' : ' .$numMois . '/' . $numAnnee ?> </option>
             <?php
         }
                    } 
        }
                    ?>

                </select>
            </div>
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
                   role="button">
        </form>
    </div>
</div>

<?php // Affichage de la fiche de frais selon l'action, l'utilisateur, le mois et si une fiche à été trouvé pour ces valeurs

if($action == "voirEtatFrais") { 
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
					<input type="hidden" name="lstVisiteur" value="<?php echo $idUtilisateur ?>">
                	<input type="hidden" name="lstMois" value="<?php echo $moisUtilisateur ?>">
                
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>
<div class="panel panel-info"> <?php // echo $idUtilisateur ?>
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
        </tr>
            
            <tr>
                <td><input type="text" name="date" value="<?php echo $date ?>" maxlength="10" readonly></td>
                <td><input type="text" name="libelle" size="50" value="<?php echo $libelle ?>" readonly></td>
                <td><input type="text" name="montant" maxlength="11" value="<?php echo $montant ?>" readonly></td>
                 
                <input type="hidden" name="lstMois" value="<?php echo $moisUtilisateur ?>">
				<input type="hidden" name="lstVisiteur" value="<?php echo $idUtilisateur ?>">
            </tr> 
         </form> <?php 
         } ?>
        
    </table> <?php echo "Resultat : " . $idUtilisateur; ?>
</div> Nombre de justificatifs : <input type="text" name="nbJustificatif" value="<?php echo $nbJustificatifs ?>" size="5" readonly> 
<form method="post" 
              action="index.php?uc=suivrePaiement&action=voirEtatFrais" 
              role="form">
              <input type="hidden" name="lstMois" value="<?php echo $moisUtilisateur ?>">
			  <input type="hidden" name="lstVisiteur" value="<?php echo $idUtilisateur ?>">
              <button class="btn btn-success" type="submit" name="miseRemboursementFiche"
               onclick="return confirm('Voulez-vous confirmer le remboursement ?');" value="1">Fiche payée</button>
               <button class="btn btn-danger" type="submit" name="misePaiementFiche" value="1"<?php echo $id ?>" onclick="return confirm('Voulez-vous vraiment mettre en paiement ce frais ?');">Mise en paiement</button> 
               
              </form>
	<?php }
    else {
     echo "Pas de fiche de frais validée.";
    }
} 
?>