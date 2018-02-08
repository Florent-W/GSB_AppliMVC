<?php
/**
 * Gestion des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 * @version   GIT: <0>
 */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             

$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
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

switch ($action) {
    case 'selectionnerMois':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if(isset($lesFrais)){
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }}
        break;
    case 'validerMajFraisForfait':
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
        if(isset($_POST['lstMois'])) {
        $mois = $_POST['lstMois'];
        }
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $ficheFraisTrouver = 0;
        if(!empty($lesInfosFicheFrais)){
            $ficheFraisTrouver = 1;
        }
        
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        if(isset($lesFrais)){
            if (lesQteFraisValides($lesFrais)) {
                $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            } else {
                ajouterErreur('Les valeurs des frais doivent être numériques');
                include 'vues/v_erreurs.php';
            }}
            else if(isset($_POST['idSuppressionLigneHorsForfait'])){
                $idSuppressionLigneHorsForfait = $_POST['idSuppressionLigneHorsForfait'];
                $libelle = $_POST['libelle'];
                if((strpos($libelle, "REFUSE : ") === false)){             
                    $libelle = substr($libelle, 0, 91);
                $libelle = "REFUSE : " . $libelle;   
                }
                   
                $date = $_POST['date'];
                $date = dateFrancaisVersAnglais($date);
                echo $date; 
                $montant = $_POST['montant'];
                $dateSuivante = getMoisSuivant($date);
                $moisSuivant = getMoisSuivant($date);
                $numMois = substr($moisSuivant, 5, 2);
                $numAnnee = substr($moisSuivant, 0, 4);
                echo " "; 
                $moisSuivant = $numAnnee . $numMois;
                echo " "; 
              
                echo $date;
                echo $moisSuivant; 
                 
                echo $dateSuivante; 
                echo " "; 
                $prochaineFicheFrais = $pdo ->getLesInfosFicheFrais($idVisiteur, $moisSuivant);   
                if(empty($prochaineFicheFrais[0])) {
                    echo "b";
                $pdo ->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
                }
              // $pdo ->majFraisHorsForfait($idSuppressionLigneHorsForfait, $libelle, $date, $montant);
                 $pdo ->majFraisHorsForfait($idSuppressionLigneHorsForfait, $libelle, $dateSuivante, $moisSuivant, $montant);
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            } 
            else if(isset($_POST['validation'])){
                $etat = "VA"; 
                $pdo ->majEtatFicheFrais($idVisiteur, $mois, $etat); 
            }
            break;
           
 
}
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);

require 'vues/v_listeVisiteurs3.php';


 ?>