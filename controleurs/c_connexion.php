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
    $idVisiteur = $pdo->getIdVisiteur($login);
        
    $recuperationMotDePasseUtilisateur =  $pdo->recuperationMotDePasse($idVisiteur);
    $cle = 'c444013c7b716bf8da1548398648efadf3390154b42a8a66987c120c9feae39a';
    $encrypted = $recuperationMotDePasseUtilisateur;
    
    $decrypted = hash_hmac('sha256', $mdp, $cle);
          
    
    $visiteur = $pdo->getInfosVisiteur($login, $encrypted);
    
    if (!is_array($visiteur) OR ($recuperationMotDePasseUtilisateur != $decrypted)) {
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
