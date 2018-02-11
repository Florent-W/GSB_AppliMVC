<?php
/**
 * Gestion de la validation des fiches de frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             

// Récupération du visiteur et d'autres informations nécessaires pour pouvoir faire marcher la sélection des visiteurs et des mois
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
$moisASelectionner = $leMois;
$leVisiteur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
$visiteurASelectionner = $leVisiteur;
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=gsb_frais', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}
$reponse = $bdd->query('SELECT * FROM visiteur where id = "'.$leVisiteur.'"');

while ($donnees = $reponse->fetch())
{
    $leVisiteurNom = $donnees['nom'];
    $leVisiteurPrenom = $donnees['prenom'];

}
$reponse->closeCursor();

$lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);

 // Récupération de l'action à effectuer selon l'URL
switch ($action) {
    case 'selectionnerMois':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if(isset($lesFrais)) {
            if (lesQteFraisValides($lesFrais)) {
                $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            } 
            else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
            }   
        }
        break;
    case 'validerMajFraisForfait':
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
        if(isset($_POST['lstMois'])) {
           $mois = $_POST['lstMois'];
           }
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois); // Recupération des informations de la fiche de frais selon l'id du visiteur selectionné et le mois
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);  // Recupération des informations des lignes hors forfait de la fiche de frais selon l'id du visiteur selectionné et le mois
        $ficheFraisTrouver = 0;
        if(!empty($lesInfosFicheFrais)){ // Si une fiche de frais à été trouvée, on l'indique
            $ficheFraisTrouver = 1;
        }
        
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs']; // Récupération du nombre de justificatif
        if(isset($lesFrais)){ // Si il y a des frais et qu'ils sont valides, on peut mettre à jour la fiche de frais avec les nouvelles informations
            if (lesQteFraisValides($lesFrais)) {
                $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            } 
            else {
                ajouterErreur('Les valeurs des frais doivent être numériques');
                include 'vues/v_erreurs.php';
            }
        } 
        // Si l'utilisateur à souhaité reporter une ligne de frais hors forfait, toutes les informations de cette ligne sont récupérées et son mois passe au suivant
        else if(isset($_POST['idReporterLigneHorsForfait'])) {
            $idReporterLigneHorsForfait = $_POST['idReporterLigneHorsForfait'];
            $libelle = $_POST['libelle'];
   
            $date = $_POST['date'];
            $date = dateFrancaisVersAnglais($date);
            $montant = $_POST['montant'];
            $dateSuivante = getMoisSuivant($date);
            $moisSuivant = getMoisSuivant($date);
            $numMois = substr($moisSuivant, 5, 2);
            $numAnnee = substr($moisSuivant, 0, 4);
            $moisSuivant = $numAnnee . $numMois;
              
            $prochaineFicheFrais = $pdo ->getLesInfosFicheFrais($idVisiteur, $moisSuivant);   
            // Si il n'y a pas de fiche de frais pour le mois suivant alors que l'utilisateur à souhaité reporter une ligne, une fiche est créer
            if(empty($prochaineFicheFrais[0])) {
                $pdo ->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
            }
            // Mise à jour des frais hors forfaits avec la nouvelle date
            $pdo ->majFraisHorsForfait($idReporterLigneHorsForfait, $libelle, $dateSuivante, $moisSuivant, $montant);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        }
        // Si l'utilisateur souhaite supprimer une ligne hors forfait, "REFUSE : " est ajouté au libellé et si il n'y a plus la place pour l'ajouté, les dernières lettres du libellé sont effacés pour faire de la place
        else if(isset($_POST['idSuppressionLigneHorsForfait'])) {
            $idSupprimerLigneHorsForfait = $_POST['idSuppressionLigneHorsForfait'];
            $libelle = $_POST['libelle'];
            if((strpos($libelle, "REFUSE : ") === false)){
                 $libelle = substr($libelle, 0, 91);
                 $libelle = "REFUSE : " . $libelle;
            }
                
            $date = $_POST['date'];
            $date = dateFrancaisVersAnglais($date);
            $montant = $_POST['montant'];   
            $mois = $_POST['lstMois']; 
                
            // Mise à jour de la ligne hors forfait avec le nouveau libellé
            $pdo ->majFraisHorsForfait($idSupprimerLigneHorsForfait, $libelle, $date, $mois, $montant);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        }
        // Si l'utilisateur souhaite valider la fiche, son état passe à validé et la date où à été effectué ce changement est ajoutée dans la base de données
        else if(isset($_POST['validation'])){
            $etat = "VA"; 
            $pdo ->majEtatFicheFrais($idVisiteur, $mois, $etat); 
            }
            break;
           
 
}
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);

require 'vues/v_validationFrais.php';


 ?>