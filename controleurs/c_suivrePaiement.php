<?php
/**
 * Gestion du suivi du paiement des fiches de frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
  $idUtilisateurMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);

$positionEspaceIdMoisUtilisateur = strrpos($idUtilisateurMois, '/');    
$positionMoisUtilisateur = $positionEspaceIdMoisUtilisateur + 1; 

$idUtilisateur = substr($idUtilisateurMois, 0, $positionEspaceIdMoisUtilisateur);
$moisUtilisateur = substr($idUtilisateurMois, $positionMoisUtilisateur);


$tousUtilisateurs = $pdo->getIdVisiteurs(); 
$nbUtilisateur = COUNT($tousUtilisateurs); 

switch ($action) {
    case 'selectionnerMois':
        
           
        break;
case 'voirEtatFrais':
    
    // echo $idUtilisateur; 
  

    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur, $moisUtilisateur); // Recupération des informations de la fiche de frais selon l'id du visiteur selectionné et le mois
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisUtilisateur);  // Recupération des informations des lignes hors forfait de la fiche de frais selon l'id du visiteur selectionné et le mois
    $ficheFraisTrouver = 0;
    if(!empty($lesInfosFicheFrais)){ // Si une fiche de frais à été trouvée, on l'indique
        $ficheFraisTrouver = 1;
    }
     $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $moisUtilisateur);

}


 include 'vues/v_suiviPaiement.php';?>