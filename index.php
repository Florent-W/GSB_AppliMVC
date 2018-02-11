<?php
/**
 * Index du projet GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    RÃ©seau CERTA <contact@reseaucerta.org>
 * @author    JosÃ© GIL <jgil@ac-nice.fr>
 * @copyright 2017 RÃ©seau CERTA
 * @license   RÃ©seau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte Â« Laboratoire GSB Â»
 */

require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
require 'vues/v_entete.php';
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
if ($uc && !$estConnecte) {
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil';
}
switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil':
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php';
    break;
case 'etatFrais':
    include 'controleurs/c_etatFrais.php';
    break;
case 'validerFrais':
    include 'controleurs/c_validerFrais.php';
    break;
case 'suivrePaiement':
    include 'controleurs/c_suivrePaiement.php';
    break;
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
}
require 'vues/v_pied.php';
