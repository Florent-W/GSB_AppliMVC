<?php
/**
 * Vue suivi du paiement des fiches de frais
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */
?>  <?php
// récupération de l'action à faire dans l'URL
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
?>
<?php
if ($ficheFraisTrouver == 1) { // Si une fiche bien été trouvée, on affiche la page
    ?><h2 class="titreOrange">
	<u> Suivi du paiement</u> :
</h2>
</br>
<div class="row">

	<div class="col-md-4">
		<h3>Sélectionnez une fiche valide :</h3>
	</div>
	<div class="col-md-4">
		<form action="index.php?uc=suivrePaiement&action=voirEtatFrais"
			method="post" role="form">
			<div class="form-group">
				<label for="lstMoisId" accesskey="n">Mois : </label> <select
					id="lstMoisId" name="lstMoisId" class="form-control">
                        <?php
    foreach ($tousUtilisateurs as $utilisateur) { // Parcours de tous les visiteurs
        $lesMois = $pdo->getLesMoisDisponiblesFichesValides($utilisateur['id']); // Recupération des mois où il y a une fiche valide selon le visiteur
        $nomUtilisateur = $utilisateur['nom'];
        $prenomUtilisateur = $utilisateur['prenom'];
        $id = $utilisateur['id'];
        
        foreach ($lesMois as $unMois) { // Parcours des mois
            $mois = $unMois['mois'];
            $numAnnee = $unMois['numAnnee'];
            $numMois = $unMois['numMois'];
            
            if ($mois == $moisUtilisateur && $id == $idUtilisateur) { // Sélection automatique des fiches valides selon le mois et le visiteur selectionné précedemment
                ?>
                                    <option selected
						value="<?php echo $id . '/' . $mois ?>">
                                        <?php echo $nomUtilisateur . ' ' . $prenomUtilisateur . ' : ' . $numMois . '/' . $numAnnee ?> </option>
                                    <?php
            }            // Affichage du reste des fiches valides dans le select
            else {
                ?>
                                    <option
						value="<?php echo $id . '/' . $mois ?>"> 
                                        <?php echo $nomUtilisateur . ' ' . $prenomUtilisateur . ' : ' . $numMois . '/' . $numAnnee ?> </option>
                                    <?php
            }
        }
    }
    ?>

                    </select>
			</div>
			<input id="ok" type="submit" value="Valider" class="btn btn-success"
				role="button"> <input id="annuler" type="reset" value="Effacer"
				class="btn btn-danger" role="button">
		</form>
	</div>
</div>
<form method="post"
	action="index.php?uc=suivrePaiement&action=voirEtatFrais" role="form">
              <?php
    // Affichage de la fiche de frais selon l'action, l'utilisateur, le mois et si une fiche à été trouvé pour ces valeurs
    
    if ($action == "voirEtatFrais") { // Si on a selectionné une fiche valide
        ?>

            </br> </br>
	<h2>Détails de la fiche de frais</h2>
	<h3>Eléments forfaitisés</h3>
	<div class="row">
		</br>

		<div class="col-md-4">
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
					type="hidden" name="lstVisiteur"
					value="<?php echo $idUtilisateur ?>"> <input type="hidden"
					name="lstMois" value="<?php echo $moisUtilisateur ?>">

			</fieldset>
		</div>
	</div>
	<div class="suivi">

                <?php if ($nombreFraisHorsForfait == 0) { // Si il n'y pas de frais hors-forfait, on affiche un message?>
                    <div class="panel-orange">

			<div class="panel panel-info">
				<div class="panel-heading">Aucun élément hors-forfait</div>
			</div>
		</div> 
                    <?php
        } else {
            ?>
                    <div class="panel-orange">
			<div class="panel panel-info">
				<div class="panel-heading">Descriptif des éléments hors forfait</div>
				<table class="table table-bordered table-responsive">
					<tr>
						<th class="date">Date (JJ/MM/AAAA)</th>
						<th class="libelle">Libellé</th>
						<th class='montant'>Montant</th>
					</tr>
                                <?php
            // Affichage permettant de voir les lignes hors forfait pour la ligne concernée
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $date = $unFraisHorsForfait['date'];
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                $ligneRefuser = strpos($libelle, "REFUSE : ");
                
                if ($ligneRefuser === false) { // On affiche seulement les lignes qui ne sont pas refusées
                    ?>         					            
                                        <tr>
						<td><input type="text" name="date" value="<?php echo $date ?>"
							maxlength="10" readonly></td>
						<td><input type="text" name="libelle" size="50"
							value="<?php echo $libelle ?>" readonly></td>
						<td><input type="text" name="montant" maxlength="11"
							value="<?php echo $montant ?>" readonly></td>
					</tr>             
                                        <?php
                }
            }
            ?>       
                            </table>
			</div>
		</div>
                <?php
        
}
        ?>

                <div>
			Nombre de justificatifs : <input type="text" name="nbJustificatif"
				value="<?php echo $nbJustificatifs ?>" size="5" readonly>
		</div>

                <?php 
// Formulaire permettant d'indiquer que la fiche à été payée ou qu'elle reste en paiement
        ?> 

                <input type="hidden" name="lstMois"
			value="<?php echo $moisUtilisateur ?>"> <input type="hidden"
			name="lstVisiteur" value="<?php echo $idUtilisateur ?>">
		<button class="btn btn-success" type="submit"
			name="miseRemboursementFiche"
			onclick="return confirm('Voulez-vous confirmer le remboursement ?');"
			value="1">Fiche payée</button>
		<button class="btn btn-danger" type="submit" name="misePaiementFiche"
			value="1"
			<?php echo $id ?>" onclick="return confirm('Voulez-vous vraiment mettre en paiement ce frais ?');">Mise
			en paiement</button>

</form>
</div>
<?php
    }
} else { // Si aucune fiche de frais n'est à validée, un message d'erreur est affiché avec un retour au menu
    include 'controleurs/c_accueil.php';
    $ajoutErreur = $instanceFonction->ajouterErreur('Pas de fiche de frais validée. Retour au menu.');
    include 'vues/v_erreurs.php';
}
?>