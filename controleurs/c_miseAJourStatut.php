<?php

/**
 * Gestion de la mise à jour des statuts
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */
$tousUtilisateurs = $pdo->getIdVisiteurs(); // Recuperation de l'id de tous les visiteurs pour pouvoir les appeler dans un select
$modeStatut = 0;  // Cette variable permet de décider à quelle étape de la modification est l'utilisateur de la page

if (isset($_POST['visiteur'])) { // Si l'utilisateur à été selectionné, on récupère l'id du visiteur sélectionné
    $idVisiteur = $_POST['visiteur']; // Récupération de la variable de l'id de l'utilisateur

    $visiteurInfo = $pdo->getVisiteurSelection($idVisiteur); 
    $visiteurStatut = $visiteurInfo['type']; // Récupération du statut actuel de l'utilisateur sélectionné
    
    $modeStatut = 1; // Le choix de l'utilisateur est fait, l'étape suivante est l'attribution du statut
}
if (isset($_POST['statut'])) {
    $idVisiteur = $_POST['visiteurconfirmation'];
    $statut = $_POST['statut']; 

    $pdo->majStatut($idVisiteur, $statut);  // Mise à jour du statut dans la base de données
    
    $modeStatut = 2; // L'administrateur à choisi de mettre à jour le statut de l'utilisateur et il sera affiché que la modification à bien été prise en compte

}
include ('vues/v_miseAJourStatut.php');
?> 