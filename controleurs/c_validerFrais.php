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
?>
<select name="visiteur" id="visiteur">
<?php
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=gsb_frais', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}
$reponse = $bdd->query('SELECT * FROM visiteur');

while ($donnees = $reponse->fetch())
{
    ?>
           <option value=" <?php echo $donnees['nom']; ?>"> <?php echo $donnees['prenom']; ?></option>
<?php
}
 
$reponse->closeCursor();
 
?>
</select>
<?php 
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idVisiteur'];
$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
// Afin de sélectionner par défaut le dernier mois dans la zone de liste
// on demande toutes les clés, et on prend la première,
// les mois étant triés décroissants
$lesCles = array_keys($lesMois);
$moisASelectionner = $lesCles[0];
include 'vues/v_listeVisiteurs.php';
?>