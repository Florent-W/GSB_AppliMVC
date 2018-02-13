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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING); // Récupération de l'action à effectuer

$idVisiteur = $_SESSION['idVisiteur'];
$mois       = getMois(date('d/m/Y'));
$numAnnee   = substr($mois, 0, 4);
$numMois    = substr($mois, 4, 2);
$utilisateurProchaineFicheTrouvee = 0; 
$moisProchainUtilisateur = 0; 


if(empty($_POST['misePaiementFiche']) && empty($_POST['miseRemboursementFiche'])) { // Si aucun des deux formulaires finaux n'a été validé, on récupère le mois et l'id du visiteur selectionné
    $idUtilisateurMois = filter_input(INPUT_POST, 'lstMoisId', FILTER_SANITIZE_STRING);

    $positionEspaceIdMoisUtilisateur = strrpos($idUtilisateurMois, '/');    
    $positionMoisUtilisateur = $positionEspaceIdMoisUtilisateur + 1; 

    $idUtilisateur   = substr($idUtilisateurMois, 0, $positionEspaceIdMoisUtilisateur);
    $moisUtilisateur = substr($idUtilisateurMois, $positionMoisUtilisateur); 
}

else {
        $idUtilisateur   = '';  
        $moisUtilisateur = ''; 
}
$tousUtilisateurs = $pdo->getIdVisiteurs(); // récupération de tous les id des visiteurs
$nbUtilisateur    = COUNT($tousUtilisateurs); 


switch ($action) {
    case 'selectionnerMois':
        $ficheFraisTrouver = 0;
        foreach($tousUtilisateurs as $utilisateur)  { // Parcours de tous les utilisateurs
            $id = $utilisateur['id'];   
            $moisDisponibleParUtilisateur = $pdo->getLesMoisDisponiblesFichesValides($id);
            if($ficheFraisTrouver == 0) {
                if(!empty($moisDisponibleParUtilisateur)) {
                    $ficheFraisTrouver = 1;
                }
            }
        }
        break;
    case 'voirEtatFrais':
    
        $lesInfosFicheFrais  = $pdo->getLesInfosFicheFrais($idUtilisateur, $moisUtilisateur); // Recupération des informations de la fiche de frais selon l'id du visiteur selectionné et le mois
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisUtilisateur);  // Recupération des informations des lignes hors forfait de la fiche de frais selon l'id du visiteur selectionné et le mois
        $ficheFraisTrouver   = 0;
        
        if(!empty($lesInfosFicheFrais)){ // Si une fiche de frais à été trouvée, on l'indique
            $ficheFraisTrouver = 1;
        }
        $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $moisUtilisateur);
     
        if(isset($_POST['misePaiementFiche'])){ // Si l'utilisateur met en paiement la fiche
            $etat = "VA";  
            $idUtilisateur   = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
            $moisUtilisateur = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
            $pdo ->majEtatFicheFrais($idUtilisateur, $moisUtilisateur, $etat);    // Mise à jour de la fiche de frais avec son état qui reste le même mais la date de modification qui change
            $ficheFraisTrouver = 1; 
        }
        
        else if(isset($_POST['miseRemboursementFiche'])){ // Si l'utilisateur met l'état de la fiche en remboursement
         
            $etat = "RB";       
            $idUtilisateur   = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
            $moisUtilisateur = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
         
            $pdo ->majEtatFicheFrais($idUtilisateur, $moisUtilisateur, $etat);  // Mise à jour de l'état de la fiche
         
            $moisProchainUtilisateur = $pdo-> getMoisDisponibleFicheValide($idUtilisateur); // Récupération du prochain mois où le visiteur à une fiche valide
         
            $memeUtilisateur  = $pdo-> getLesMoisDisponiblesFichesValides($idUtilisateur); // On regarde si l'utilisateur à bien un mois où il y a une fiche valide
           
            if(isset($moisProchainUtilisateur) AND (isset($memeUtilisateur))) { // Si il y a un autre mois où l'utilisateur à une fiche valide, on l'attribut
                $moisUtilisateur   = $moisProchainUtilisateur;
                $ficheFraisTrouver = 1; 
            }

            if (isset($moisProchainUtilisateur) AND (empty($memeUtilisateur)))  { // Sinon on cherche un utilisateur et un mois où il y a une fiche valide
                foreach($tousUtilisateurs as $utilisateur)  { // Parcours de tous les utilisateurs
                    $id = $utilisateur['id'];   
 
                    if($utilisateurProchaineFicheTrouvee == 0) { // On continu à chercher tant qu'une fiche valide n'a pas été trouvé
                        $memeUtilisateur = $pdo ->getLesMoisDisponiblesFichesValides($id); // On regarde si le visiteur en cours à un mois ayant une fiche valide
                          
                            if(!empty($memeUtilisateur)) { 
                                $ficheATrouver = 0; 
                                $memeUtilisateur =  $pdo -> getLesMoisDisponiblesFichesValides($id); // On attribut un des mois en question
                                foreach ($memeUtilisateur as $unMois) { // Parcours des mois
                                    $mois     = $unMois['mois'];
                                    $numAnnee = $unMois['numAnnee'];
                                    $numMois  = $unMois['numMois'];
                               
                                    if($ficheATrouver == 0){
                                        $moisUtilisateur = $mois; // Attribution du mois
                                        $ficheATrouver = 1;
                                    }
                                }
                                $idUtilisateur = $id;  
                                $utilisateurProchaineFicheTrouvee = 1; // On dit que la fiche à été trouvée
                                
                            }
                    }
                }
                if($utilisateurProchaineFicheTrouvee == 0) {
                    $ficheFraisTrouver = 0;
                } 
                else {
                    $ficheFraisTrouver = 1;
                    $lesFraisForfait     = $pdo->getLesFraisForfait($idUtilisateur, $moisUtilisateur);       
                }     
          }
          $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisUtilisateur);
        }
        break; 
}

$nbJustificatifs     =  $pdo->getNbjustificatifs($idUtilisateur, $moisUtilisateur); // Récupération du nombre de justificatif
$lesFraisForfait     = $pdo->getLesFraisForfait($idUtilisateur, $moisUtilisateur);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisUtilisateur);

include 'vues/v_suiviPaiement.php';?>