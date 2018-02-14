<?php
/**
 * Gestion de la connexion
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}





switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'valideConnexion':
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    echo $login; 
    $idVisiteur = $pdo->getIdVisiteur($login);
   
    
    
    echo $idVisiteur; 
    
    $recuperationMotDePasseUtilisateur =  $pdo->recuperationMotDePasse($idVisiteur);
    echo "Recuperation mot de passe base de donnee : " . $recuperationMotDePasseUtilisateur;
    $key = 'password to (en/de)crypt';
    $encrypted = $recuperationMotDePasseUtilisateur;
    
    $data = base64_decode($encrypted);
    $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
    
    $decrypted = rtrim(
        mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            hash('sha256', $key, true),
            substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
            MCRYPT_MODE_CBC,
            $iv
            ),
        "\0"
        );
    echo " Trouve : " . $decrypted . "Fin ";
    echo $mdp; 
    echo $decrypted; 
    
    $visiteur = $pdo->getInfosVisiteur($login, $encrypted);
    
    if (!is_array($visiteur) OR ($mdp != $decrypted)) {
        ajouterErreur('Login ou mot de passe incorrect');
        include 'vues/v_erreurs.php';
        include 'vues/v_connexion.php';
    } else {
        $id = $visiteur['id'];
        $nom = $visiteur['nom'];
        $prenom = $visiteur['prenom'];
        $type = $visiteur['type'];
        connecter($id, $nom, $prenom, $type);
        header('Location: index.php');
    }
    break;
default:
    include 'vues/v_connexion.php';
    break;
}
