<?php
/**
 * Fichier de configuration pour le back-office
 */

require_once __DIR__ . '/bootstrap.php';

if (role(ROLE_ADMIN) !== true) {
    ajouterFlash('danger', 'Vous n\'avez pas les droits d\'accès requis.');
    header('Location: ../login.php');
}

