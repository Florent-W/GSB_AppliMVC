<?php

/**
 * Index du projet GSB
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Florent WELTMANN <florent.weltmann@gmail.com>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte Â« Laboratoire GSB Â»
 */
require_once 'includes/class.fonction.inc.php';
require_once 'includes/class.pdogsb.inc.php';
session_start();
$pdo = PdoGsb::getPdoGsb();

$instanceFonction = new fonction();
$estConnecte = $instanceFonction->estConnecte();

if ($estConnecte and isset($_SESSION['type'])) {
    $type = $_SESSION['type'];
}

require 'vues/v_entete.php';
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
if ($uc && ! $estConnecte) {
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil';
}

// Choix de la page et gestion de l'autorisation d'accès à des parties du site
switch ($uc) {
    case 'connexion':
        include 'controleurs/c_connexion.php';
        break;
    case 'accueil':
        include 'controleurs/c_accueil.php';
        break;
    case 'majMotDePasse':
        if ($type == "Administrateur") {
            include 'controleurs/c_miseAJourMotDePasse.php';
        } else {
            ajouterErreur('Accès non autorisé pour cette page');
            include 'vues/v_erreurs.php';
        }
        break;
    case 'gererFrais':
        if ($type == "Visiteur") {
            include 'controleurs/c_gererFrais.php';
        } else {
            ajouterErreur('Accès non autorisé pour cette page');
            include 'vues/v_erreurs.php';
        }
        break;
    case 'etatFrais':
        if ($type == "Visiteur") {
            include 'controleurs/c_etatFrais.php';
        } else {
            ajouterErreur('Accès non autorisé pour cette page');
            include 'vues/v_erreurs.php';
        }
        break;
    case 'validerFrais':
        if ($type == "Comptable") {
            include 'controleurs/c_validerFrais.php';
        } else {
            ajouterErreur('Accès non autorisé pour cette page');
            include 'vues/v_erreurs.php';
        }
        break;
    case 'suivrePaiement':
        if ($type == "Comptable") {
            include 'controleurs/c_suivrePaiement.php';
        } else {
            ajouterErreur('Accès non autorisé pour cette page');
            include 'vues/v_erreurs.php';
        }
        break;
    case 'deconnexion':
        include 'controleurs/c_deconnexion.php';
        break;
}
require 'vues/v_pied.php';
