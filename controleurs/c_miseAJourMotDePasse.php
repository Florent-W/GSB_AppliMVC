<?php

/**
 * Gestion de la mise à jour des mots de passe via l'algorithme de hashage SHA-256
 
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */
// L'algorithme de hashage SHA-256 à été utilisé car il permet de hasher les caractères en 256 bits (64 caractères) contrairement au md5 (32 caractères) et SHA-1 (40 caractères) qui ne proposent que de crypter les mots de passes avec de faibles nombres de caractères, ce qui entraine les dangers du bruteforce qui essaye de comparer tous les combinaisons de caractères jusqu'à tomber sur le hash.
$tousUtilisateurs = $pdo->getIdVisiteurs(); // Recuperation de l'id de tous les visiteurs pour pouvoir les appeler dans un select

$motDePasseDejaHasher = 3; // 0 = Mot de passe pas encore hashé mais qui va être fait, 1 = Après confirmation, l'utilisateur à souhaiter refuser le hashage car le mot de passe du visiteur sélectionné faisait 64 caractères et cela présente des risques
                           // 2 = Mot de passe possiblement déjà hashé, donc demande de confirmation à l'utilisateur, 3 = Valeur initiale de la variable
$hashageReussi = 0; // Permet de dire si le hashage à été fait

if (isset($_POST['visiteur']) || isset($_POST['confirmation'])) { // Si l'utilisateur à été selectionné ou que l'utilisateur souhaite confirmer le hashage, on récupère l'id du visiteur sélectionné
    if (isset($_POST['visiteur'])) {
        $idVisiteur = $_POST['visiteur'];
    } else if (isset($_POST['confirmation'])) {
        $idVisiteur = $_POST['visiteurconfirmation'];
    }
    $recuperationMotDePasse = $pdo->recuperationMotDePasse($idVisiteur); // Récupération du mot de passe dans la base de donnée
    $motDePasseDejaHasher = 0;
    
    if (strlen($recuperationMotDePasse) == 64 && ! isset($_POST['confirmation'])) { // Si le mot de passe récupéré fait 64 caractères et que l'utilisateur n'est pas encore à l'étape de confirmation, on lui demande si il veut vraiment faire le hashage
        $motDePasseDejaHasher = 2;
    } else {
        $motDePasseDejaHasher = 0;
    }
} else if (isset($_POST['refuser'])) { // Si l'utilisateur a refusé, on arrête de lui demande et la page proposera à nouveau de choisir un visiteur
    $motDePasseDejaHasher = 1;
}

if ($motDePasseDejaHasher == 0) { // Commencement du hashage
    $cle = 'c444013c7b716bf8da1548398648efadf3390154b42a8a66987c120c9feae39a'; // La clé qui va servir à encrypter et décrypter le mot de passe
    $encrypted = hash_hmac('sha256', $recuperationMotDePasse, $cle); // Passage du mot de passe au hashage SHA-256
    $pdo->majMotDePasse($idVisiteur, $encrypted); // Inscription du hashage à la place du mot de passe dans la base de donnée
    $hashageReussi = 1;
}

include ('vues/v_miseAJourMotDePasse.php');
?> 