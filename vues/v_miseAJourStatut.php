<?php
/**
 * Vue mise à jour des statuts des utilisateurs
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */
?>

<h2>
    <u> Mise à jour des statuts</u> :
</h2>
</br>
<div class="row">

    <div class="col-xs-3">
        <h5>Sélectionnez un utilisateur :</h5>
    </div>
    <div class="col-md-3">
        <form action="index.php?uc=majStatut" method="post" role="form">
            <select id="visiteur" name="visiteur" class="form-control">
                <?php
                foreach ($tousUtilisateurs as $utilisateur) { // Parcours de tous les visiteurs
                    $nomUtilisateur = $utilisateur['nom'];
                    $prenomUtilisateur = $utilisateur['prenom'];
                    $id = $utilisateur['id'];

                    if ($id == $idVisiteur) { // Visiteur selectionné
                        ?> 
                        <option selected value="<?php echo $id ?>">
                            <?php echo $nomUtilisateur . ' ' . $prenomUtilisateur ?> </option> <?php
                    } else { // Les autres visiteurs
                        ?>
                        <option value="<?php echo $id ?>"> 
                            <?php echo $nomUtilisateur . ' ' . $prenomUtilisateur ?> </option>
                        <?php
                    }
                }
                ?>
            </select>
    </div>
    <div class="col-xs-2">
        <input id="ok" type="submit" value="Valider" class="btn btn-success"
               role="button">
    </div>
</form>
</div>
<br/>

<?php if ($modeStatut == 1) {  // Si un utilisateur à été sélectionner, le système propose de changer son statut ?>  <div class="row">
        <div class="col-xs-3">
            <h5>Sélectionnez un statut :</h5>
        </div>

        <div class="col-md-3">
            <form action="index.php?uc=majStatut" method="post" role="form">
                <select id ="statut" name="statut" class="form-control">
                    <option value="Visiteur"  <?php if ($visiteurStatut == "Visiteur") echo "selected='selected'"; ?> >Visiteur</option> 
                    <option value="Comptable" <?php if ($visiteurStatut == "Comptable") echo "selected='selected'"; ?>>Comptable</option>
                    <option value="Administrateur" <?php if ($visiteurStatut == "Administrateur") echo "selected='selected'"; ?>>Administrateur</option>
                    <option <?php if (empty($visiteurStatut)) echo "selected='selected'"; ?></option> 
                </select>    
        </div>
        <input type="hidden" name="visiteurconfirmation"
               value="<?php echo $idVisiteur ?>">
        <div class="col-xs-2">
            <input id="ok" type="submit" value="Valider" class="btn btn-success" onclick="return confirm('Voulez-vous vraiment changer le statut ?');"
                   role="button">
        </div>
    </form> 
    </div>
    <?php
}
?>


</br>
<?php
if ($modeStatut == 2) { // Si le statut à été mis à jour, un message est affiché
    ?>
    <div class="alert alert-info" role="alert">
        <p>Le statut de l'utilisateur à été mis à jour.</p>
    </div>
    <?php
}
?>
</br>

