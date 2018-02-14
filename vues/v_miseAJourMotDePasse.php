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

?>
<?php 
  // Si une fiche bien été trouvée, on affiche la page
    ?><h2> <u> Mise à jour des mots de passes en SHA-256</u> :</h2> 
	 </br>
	<div class="row">
	
    	<div class="col-xs-3">
        	<h5>Sélectionnez un utilisateur : </h5>
    	</div>
    	<div class="col-md-3">
        	<form action="index.php?uc=majMotDePasse" 
            	  method="post" role="form">
                         	<select id="visiteur" name="visiteur" class="form-control">
                	<?php 
                	
                        foreach($tousUtilisateurs as $utilisateur) { // Parcours de tous les visiteurs
                             $nomUtilisateur    = $utilisateur['nom'];
                             $prenomUtilisateur = $utilisateur['prenom'];
                             $id                = $utilisateur['id']; 
                            
                             if($id == $idVisiteur) {  ?>
                              <option selected value="<?php echo $id ?>">
                             <?php echo $nomUtilisateur . ' ' . $prenomUtilisateur ?> </option> <?php   }
                             else {?>
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
               	    role="button"> </div>
        	</form>
        	
        	
    	</div>
    	
    	</br>
    	</br>
    
    
               	    <?php 
               	    if($motDePasseDejaHasher ==  2) { ?>
               	    <div class="row">
               	    
               	    </br>   
               	       <div class="col-md-6">
        	<h5>Etes-vous sûr de vouloir encrypter ce mot de passe ? Si il a déjà été encrypté, il risque d'être irrécupérable. (Cet option ne doit être utilisé que si le mot de passe initial de l'utilisateur faisait 64 caractères) </h5>
    	  </div>   
    	<div class="col-md-2">
        	<form action="index.php?uc=majMotDePasse" 
            	  method="post" role="form">
            	  <div class="col-md-8">
            	  	                <input type="hidden" name="visiteurconfirmation" value="<?php echo $idVisiteur ?>">
                         	<button class="btn btn-success" name="confirmation" type="submit">Confirmer</button></div>
                       <div class="col-md-3">  	<button class="btn btn-danger" name="refuser" type="submit">Refuser</button> </div>
                         	
                      </form>
                    	
                         	</div>
                         	</div>
                         	</br>
                         	<?php     ajouterErreur('Cet utilisateur semble déjà avoir un mot de passe crypté.');
        include 'vues/v_erreurs.php';}
       
        if($hashageReussi == 1) {
            ?>
            <div class="alert alert-info" role="alert">
            <p>Le mot de passe de cet utilisateur à bien été crypté.</p>
            </div> 
            <?php 
        }
       
                         	?>
                         	