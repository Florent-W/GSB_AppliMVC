<?php

/**
 * Génération d'un jeu d'essai
 *
 * PHP Version 5
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$moisDebut = '201609';
require './fonctions.php';

// maj en local $pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'root', '');

$pdo = new PDO('mysql:host=db731402101.db.1and1.com;dbname=db731402101', 'dbo731402101', 'GsbMotdepasse');
$pdo->query('SET CHARACTER SET utf8');

set_time_limit(0);
creationFichesFrais($pdo);
creationFraisForfait($pdo);
creationFraisHorsForfait($pdo);
majFicheFrais($pdo);
echo '<br>' . getNbTable($pdo, 'fichefrais') . ' fiches de frais créées !';
echo '<br>' . getNbTable($pdo, 'lignefraisforfait') . ' lignes de frais au forfait créées !';
echo '<br>' . getNbTable($pdo, 'lignefraishorsforfait') . ' lignes de frais hors forfait créées !';
