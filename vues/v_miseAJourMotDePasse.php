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
  // Si une fiche bien été trouvée, on affiche la page
    ?><h2> <u> Mise à jour des mots de passes en SHA-256</u> :</h2> 
	 </br>
	<div class="row">
	
    	<div class="col-xs-3">
        	<h5>Sélectionnez un utilisateur : </h5>
    	</div>
    	<div class="col-xs-3">
        	<form action="index.php?uc=majMotDePasse" 
            	  method="post" role="form">
                         	<select id="visiteur" name="visiteur" class="form-control">
                	<?php 
                	
                        foreach($tousUtilisateurs as $utilisateur) { // Parcours de tous les visiteurs
                             $nomUtilisateur    = $utilisateur['nom'];
                             $prenomUtilisateur = $utilisateur['prenom'];
                             $id                = $utilisateur['id']; 
                             ?>
                             <option value="<?php echo $id ?>">
                             <?php echo $nomUtilisateur . ' ' . $prenomUtilisateur ?> </option>
             						<?php
                                }
                                ?>
                                
                               </select>
                              </div>
                              <div class="col-xs-2">
                           <input id="ok" type="submit" value="Valider" class="btn btn-success" 
               	    role="button"> </div>
        	</form>
        	
        	
    	</div>
    	
    	</br>
    	</br>
    	</br>
    	<div class="row">
    	<div class="col-md-6">
        	<h5>Ou bien tous les utilisateurs : </h5>
    	</div>
    	  <div class="col-md-2">
                           <input id="ok" type="submit" value="Valider" class="btn btn-success" 
               	    role="button"> </div>
	    
                                