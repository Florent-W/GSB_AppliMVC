<?php



$tousUtilisateurs =  $pdo->getIdVisiteurs();

$motDePasseDejaHasher = 3; 
$hashageReussi = 0; 

if(isset($_POST['visiteur']) || isset($_POST['confirmation'])) { 
    if(isset($_POST['visiteur'])) {
    $idVisiteur = $_POST['visiteur'];
   
    }
    else if(isset($_POST['confirmation'])) {
    $idVisiteur = $_POST['visiteurconfirmation'];  
    }
    $recuperationMotDePasse =  $pdo->recuperationMotDePasse($idVisiteur);
    $motDePasseDejaHasher = 0;
    
    if(strlen($recuperationMotDePasse) == 64 && !isset($_POST['confirmation'])) { 
        $motDePasseDejaHasher = 2;
    }
    else {
        $motDePasseDejaHasher = 0;
    }
     }
    
     
          
        else if(isset($_POST['refuser']))   {
            $motDePasseDejaHasher = 1;
        }
   
    if($motDePasseDejaHasher == 0) {         
    
        $cle = 'c444013c7b716bf8da1548398648efadf3390154b42a8a66987c120c9feae39a';    
    
        $encrypted =  hash_hmac('sha256', $recuperationMotDePasse, $cle); 
           
   

 $pdo-> majMotDePasse($idVisiteur, $encrypted); 
 $hashageReussi = 1;
 ?>
 
 

 <?php 
}
 include('vues/v_miseAJourMotDePasse.php'); ?> 