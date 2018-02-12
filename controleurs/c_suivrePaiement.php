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
$utilisateurProchaineFicheTrouvee = 0; 
$moisProchainUtilisateur = 0; 


if(empty($_POST['misePaiementFiche']) && empty($_POST['miseRemboursementFiche'])) {
$idUtilisateurMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);

$positionEspaceIdMoisUtilisateur = strrpos($idUtilisateurMois, '/');    
$positionMoisUtilisateur = $positionEspaceIdMoisUtilisateur + 1; 

$idUtilisateur = substr($idUtilisateurMois, 0, $positionEspaceIdMoisUtilisateur);
$moisUtilisateur = substr($idUtilisateurMois, $positionMoisUtilisateur); 


}

else {
        $idUtilisateur = '';  
        $moisUtilisateur = ''; 
    //$lesInfosFicheFrais2 = $pdo->getLesInfosFicheFrais($idUtilisateur, $moisUtilisateur); // Recupération des informations de la fiche de frais selon l'id du visiteur selectionné et le mois
    // $nbJustificatifs2 = $lesInfosFicheFrais2['nbJustificatifs']; // Récupération du nombre de justificatif
   // echo $nbJustificatifs2;
    
}
echo "moissaaaa ". $idUtilisateur; 
$tousUtilisateurs = $pdo->getIdVisiteurs(); 
$nbUtilisateur = COUNT($tousUtilisateurs); 

switch ($action) {
    case 'selectionnerMois':
        
           
        break;
    case 'voirEtatFrais':
    
      $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur, $moisUtilisateur); // Recupération des informations de la fiche de frais selon l'id du visiteur selectionné et le mois
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisUtilisateur);  // Recupération des informations des lignes hors forfait de la fiche de frais selon l'id du visiteur selectionné et le mois
     $ficheFraisTrouver = 0;
    if(!empty($lesInfosFicheFrais)){ // Si une fiche de frais à été trouvée, on l'indique
        $ficheFraisTrouver = 1;
    }
     $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $moisUtilisateur);
     
     if(isset($_POST['misePaiementFiche'])){ // Si l'utilisateur met en paiement la fiche
         $etat = "VA";  
         $idUtilisateur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
         $moisUtilisateur = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
         $pdo ->majEtatFicheFrais($idUtilisateur, $moisUtilisateur, $etat);                 
       
     }
     else if(isset($_POST['miseRemboursementFiche'])){ // Si l'utilisateur met l'état de la fiche en remboursement
         
         $etat = "RB";       
         $idUtilisateur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
         $moisUtilisateur = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
         echo "Envoyé : " . $idUtilisateur . $moisUtilisateur;
         
          echo $idUtilisateur; 
         // echo $idUtilisateur; 
         $pdo ->majEtatFicheFrais($idUtilisateur, $moisUtilisateur, $etat);  // Mise à jour de l'état de la fiche
         
                 
        $moisProchainUtilisateur = $pdo-> getMoisDisponibleFicheValide($idUtilisateur); // Récupération du prochain mois où le visiteur à une fiche valide
         echo "mois prochain : " .$moisProchainUtilisateur; 
         
         $memeUtilisateur  = $pdo-> getLesMoisDisponiblesFichesValides($idUtilisateur); 
           
        if(isset($moisProchainUtilisateur) AND (isset($memeUtilisateur))) { // Si il y a un autre mois où l'utilisateur à une fiche valide, on l'attribut
            echo "a"; 
            $moisUtilisateur = $moisProchainUtilisateur;
            echo "mois : ". $moisUtilisateur;    
        }

         if (isset($moisProchainUtilisateur) AND (empty($memeUtilisateur)))  { // Sinon on cherche un utilisateur et un mois où il y a une fiche valide
           echo "pas coucou"; 
           foreach($tousUtilisateurs as $utilisateur)  {
             $id = $utilisateur['id'];   
 
            if($utilisateurProchaineFicheTrouvee == 0) {
                $memeUtilisateur = $pdo ->getLesMoisDisponiblesFichesValides($id); 
             if(!empty($memeUtilisateur)) {
                 $moisUtilisateurChercher =  $pdo -> getMoisDisponibleFicheValide($id);
                  $moisUtilisateur = $moisUtilisateurChercher; 
                  $idUtilisateur = $id; 
                  echo "Trouvé";
                  echo $idUtilisateur; 
                 $utilisateurProchaineFicheTrouvee = 1; 
                  
              }
            }
           }
             if($utilisateurProchaineFicheTrouvee == 0) {
              $ficheFraisTrouver = 0;
               echo "non trouvé"; 
        echo $idUtilisateur; 
           } 
            else {
                $ficheFraisTrouver = 1;
                 
           }     
        }
         echo $idUtilisateur; 
         echo $moisUtilisateur; 
         $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisUtilisateur);
         
         
        
     }
     break; 

}
$nbJustificatifs =  $pdo->getNbjustificatifs($idUtilisateur, $moisUtilisateur); // Récupération du nombre de justificatif
$lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $moisUtilisateur);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisUtilisateur);

 include 'vues/v_suiviPaiement.php';?>