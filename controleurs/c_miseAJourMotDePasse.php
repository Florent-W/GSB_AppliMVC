<?php



$tousUtilisateurs =  $pdo->getIdVisiteurs();


if(isset($_POST['visiteur'])) {
    $idVisiteur = $_POST['visiteur'];
    // echo $idVisiteur; 
    $recuperationMotDePasse =  $pdo->recuperationMotDePasse($idVisiteur);
    
    
    $key = 'password to (en/de)crypt';
    $string = $recuperationMotDePasse;
    
    $iv = mcrypt_create_iv(
        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
        MCRYPT_DEV_URANDOM
        );
    
    $encrypted = base64_encode(
        $iv .
        mcrypt_encrypt(
            MCRYPT_RIJNDAEL_128,
            hash('sha256', $key, true),
            $string,
            MCRYPT_MODE_CBC,
            $iv
            )
        );
    echo $encrypted . "  a"; 
   

 $pdo-> majMotDePasse($idVisiteur, $encrypted); 
}
 include('vues/v_miseAJourMotDePasse.php'); 