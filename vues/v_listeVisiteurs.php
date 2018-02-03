
<?php 
$action2 = filter_input(INPUT_GET, 'action2', FILTER_SANITIZE_STRING);
?>
 <script>
 var b = '';</script>
<div class="row">
    <div class="col-md-4">
        <div>Sélectionner un mois : </div>
    </div>
     <div class="col-md-4">
        <form action="index.php?uc=validerFrais&action=selectionnerMois&action2=selectionVisiteur" 
              method="post" role="form">
              <select id="lstVisiteur" name="lstVisiteur" onChange="javascript:document.getElementById('visiteur').value = this.value; updatevariable(this.value)">
              
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
           <option value="<?php echo $donnees['id']?>"> <?php echo $donnees['nom'] ?> <?php echo $donnees['prenom'] ?></option>
<?php
}
 
$reponse->closeCursor();
 
?>
</select> 
<select id="lstMois" name="lstMois" onChange="javascript:document.getElementById('mois').value = this.value;">
 <?php
 foreach ($lesMois as $unMois) {
$mois = $unMois['mois'];
$numAnnee = $unMois['numAnnee'];
$numMois = $unMois['numMois'];
if ($mois == $moisASelectionner) {
            ?>  
            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?>    </select> <button class="btn btn-success" type="submit">Selectionner</button>
        </form>
    </div>
</div><script>
var value = "";
function updatevariable(data) { 
    value = data;
    alert(value);
} 
</script>
<input type="text" name="visiteur" value="" id="visiteur"/> <!-- test pour voir si on peut prendre la valeur selectionner -->
<input type="text" name="mois" value="" id="mois"/>
<?php if($action2 = "selectionVisiteur") {
        echo "a"; 
 include('vues/v_listeVisiteurs2.php');

}
 ?>