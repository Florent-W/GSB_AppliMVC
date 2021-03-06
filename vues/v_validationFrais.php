<?php
/**
 * Vue Validation des fiches de frais
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */
?> 
<div class="row">
	<div class="col-xs-2">
		<h4>Choisir le visiteur :</h4>
	</div>
	<div class="col-xs-2">

		<form action="index.php?uc=validerFrais&action=validerMajFraisForfait"
			method="post" role="form">

			<select id="lstVisiteur" name="lstVisiteur" class="form-control">

                <?php
                // Affichage de la sélection des visiteurs selon l'action et l'utilisateur déjà selectionné
                
                if ($action == "selectionnerMois") {
                    $visiteurSelectionMin = $pdo->getVisiteurMin();
                    $visiteurIdMin = $visiteurSelectionMin['id'];
                    
                    foreach ($tousUtilisateurs as $utilisateur) {
                        $id = $utilisateur['id'];
                        $nom = $utilisateur['nom'];
                        $prenom = $utilisateur['prenom'];
                        if ($id == $visiteurIdMin) {
                            ?>    
                            <option selected value="<?php echo $id ?>"> <?php echo $nom ?> <?php echo $prenom ?></option>  
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $id ?>"> <?php echo $nom ?> <?php echo $prenom ?></option> <?php
                        }
                    }
                } else if ($action == "validerMajFraisForfait") {
                    foreach ($tousUtilisateurs as $utilisateur) {
                        $id = $utilisateur['id'];
                        $nom = $utilisateur['nom'];
                        $prenom = $utilisateur['prenom'];
                        
                        if ($id == $visiteurASelectionner) {
                            ?> 
                            <option selected
					value="<?php echo $leVisiteur ?>"> <?php echo $leVisiteurNom ?> <?php echo $leVisiteurPrenom ?></option> 
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $id ?>"> <?php echo $nom ?> <?php echo $prenom ?></option> <?php
                        }
                    }
                }
                ?>
            </select>
	
	</div>
	<div class="col-xs-2">
		<h4>Mois :</h4>
	</div>
	<div class="col-xs-2">
		<select id="lstMois" name="lstMois" class="form-control">
            <?php
            // Affichage de la selection des mois selon l'action et le mois déjà selectionné
            
            if ($action == "validerMajFraisForfait") {
                $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
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
            } else {
                
                $utilisateur = $pdo->getInfosDerniereFiche();
                $moisASelectionner = $utilisateur['mois'];
                $numAnneeSelection = substr($moisASelectionner, 0, 4);
                $numMoisSelection = substr($moisASelectionner, 4, 2);
                
                $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
                foreach ($lesMois as $unMois) { // Selection des mois à afficher
                    $mois = $unMois['mois'];
                    $numAnnee = $unMois['numAnnee'];
                    $numMois = $unMois['numMois'];
                    if ($mois == $moisASelectionner) {
                        ?>
                        <option selected
				value="<?php echo $moisASelectionner ?>">
                            <?php echo $numMoisSelection . '/' . $numAnneeSelection ?> </option>   <?php
                    } else {
                        ?>
                        <option value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    }
                }
            }
            ?>    
        </select>
	</div>
	<div class="col-xs-2">
		<button class="btn btn-success" type="submit">Selectionner</button>
	</div>
</div>
</form>

<?php
// Affichage de la fiche de frais selon l'action, l'utilisateur, le mois et si une fiche à été trouvé pour ces valeurs
if ($action == "validerMajFraisForfait") {
    if ($ficheFraisTrouver == 1) {
        ?>
<div class="row">
	<h2 class="titreOrange">Valider la fiche de frais</h2>
	<h3>Eléments forfaitisés</h3>
	<div class="col-md-4">
		<form method="post"
			action="index.php?uc=validerFrais&action=validerMajFraisForfait"
			role="form">
			<fieldset>       
                        <?php
        if (isset($lesFraisForfait[0])) {
            foreach ($lesFraisForfait as $unFrais) {
                $idFrais = $unFrais['idfrais'];
                $libelle = htmlspecialchars($unFrais['libelle']);
                $quantite = $unFrais['quantite'];
                ?>
                                <div class="form-group">
					<label for="idFrais"><?php echo $libelle ?></label> <input
						type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]"
						size="10" maxlength="5" value="<?php echo $quantite ?>"
						class="form-control">
				</div>
                                <?php
            }
        }
        ?>
                        <input type="hidden" name="element" value="1"> <input
					type="hidden" name="lstVisiteur" value="<?php echo $leVisiteur ?>">
				<input type="hidden" name="lstMois" value="<?php echo $leMois ?>">

				<button class="btn btn-success" type="submit">Corriger</button>
				<button class="btn btn-danger" type="reset">Réinitialiser</button>
			</fieldset>
		</form>
	</div>
</div>

</br>
<div class="panel-orange">
	<div class="panel panel-info">
                <?php if ($nombreFraisHorsForfait == 0) { ?>
                    <div class="panel-heading">Aucun élément
			hors-forfait</div>
                    <?php
        } else {
            ?>
                    <div class="panel-heading">Descriptif des éléments
			hors forfait</div>
		<table class="table table-bordered table-responsive">
			<tr>
				<th class="date">Date (JJ/MM/AAAA)</th>
				<th class="libelle">Libellé</th>
				<th class='montant'>Montant</th>
				<th class='selection'></th>
			</tr>
                        <?php
            // Affichage et formulaire permettant de voir les lignes hors forfait pour la ligne concernée, un bouton permettra de supprimer une ligne en y ajoutant "REFUSE : " dans le libellé, un autre permettra de reporter d'un mois, une ligne
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $date = $unFraisHorsForfait['date'];
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                
                $ligneRefuser = strpos($libelle, "REFUSE : ");
                if ($ligneRefuser === false) {
                    ?> 

                                <form method="post"
				action="index.php?uc=validerFrais&action=validerMajFraisForfait"
				role="form">
				<tr>
					<td><input type="text" name="date" value="<?php echo $date ?>"
						maxlength="10" readonly></td>
					<td><input type="text" name="libelle" size="50"
						value="<?php echo $libelle ?>" readonly></td>
					<td><input type="text" name="montant" maxlength="11"
						value="<?php echo $montant ?>" readonly></td>
					<td>
						<button class="btn btn-success" type="submit"
							name="idReporterLigneHorsForfait" value="<?php echo $id ?>"
							onclick="return confirm('Voulez-vous vraiment reporter ce frais ?');">Reporter</button>
						<button class="btn btn-danger" type="submit"
							name="idSuppressionLigneHorsForfait" value="<?php echo $id ?>"
							onclick="return confirm('Voulez-vous vraiment supprimer ce frais ?');">Supprimer</button>
						<button class="btn btn-danger" type="reset">Réinitialiser</button>
					</td>

					<input type="hidden" name="lstMois" value="<?php echo $leMois ?>">
					<input type="hidden" name="lstVisiteur"
						value="<?php echo $leVisiteur ?>">
				</tr>
			</form> <?php
                }
            }
            ?>

                    </table>  
                    <?php
        }
        ?> 
            </div>
            <?php // Permet de valider une fiche en prenant en compte la date de modification et en passant l'état de la fiche à validé si l'utilisateur le confirme. ?>
        </div>
<p>
	Nombre de justificatifs : <input type="text" name="nbJustificatif"
		value="<?php echo $nbJustificatifs ?>" size="5" readonly>
</p>
<form method="post"
	action="index.php?uc=validerFrais&action=validerMajFraisForfait"
	role="form">
	<input type="hidden" name="lstMois" value="<?php echo $leMois ?>"> <input
		type="hidden" name="lstVisiteur" value="<?php echo $leVisiteur ?>">
	<button class="btn btn-success" type="submit" name="validation"
		onclick="return confirm('Voulez-vous confirmer ?');">Valider</button>
</form>
<?php
    } else {
        $ajoutErreur = $instanceFonction->ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois.');
        include 'vues/v_erreurs.php';
    }
}
?>
