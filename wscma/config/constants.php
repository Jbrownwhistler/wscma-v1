<?php
/**
 * WSCMA — config/constants.php
 * Constantes globales du site institutionnel WSCMA
 * Chargé en premier par toutes les pages du site.
 * @version  1.0
 * @requires PHP 8.2
 * @env      MAMP localhost:8888/wscma/
 */

// ────────────────────────────────────────────────────────────────────────────
// IDENTITÉ DU SITE
// ────────────────────────────────────────────────────────────────────────────

define('SITE_NOM',       'WSCMA');
define('SITE_NOM_FR',    'Autorité Mondiale du Marché des Concombres de Mer');
define('SITE_NOM_EN',    'World Sea Cucumber Market Authority');
define('SITE_SLOGAN_FR', 'Réguler Aujourd\'hui, Pérenniser Demain');
define('SITE_SLOGAN_EN', 'Regulating Today, Sustaining Tomorrow');
define('SITE_VERSION',   '2.0');

// ────────────────────────────────────────────────────────────────────────────
// URLS ET CHEMINS
// ────────────────────────────────────────────────────────────────────────────

define('SITE_URL',      'http://localhost:8888/wscma');
define('SITE_URL_PROD', 'https://www.wscma.int');
define('ASSETS_URL',    SITE_URL . '/assets');

// ────────────────────────────────────────────────────────────────────────────
// INFORMATIONS INSTITUTIONNELLES
// ────────────────────────────────────────────────────────────────────────────

define('SIEGE_FR',     '14, Avenue des Nations Maritimes, 1202 Genève, Suisse');
define('SIEGE_EN',     '14, Avenue des Nations Maritimes, 1202 Geneva, Switzerland');
define('VILLE_FR',     'Genève, Suisse');
define('VILLE_EN',     'Geneva, Switzerland');
define('TEL',          '+41 22 740 0100');
define('FAX',          '+41 22 740 0199');
define('EMAIL',        'secretariat@wscma.int');
define('EMAIL_PRESSE', 'presse@wscma.int');
define('EMAIL_DOCS',   'documents@wscma.int');
define('EMAIL_DPO',    'dpo@wscma.int');

// ────────────────────────────────────────────────────────────────────────────
// DONNÉES HISTORIQUES
// ────────────────────────────────────────────────────────────────────────────

define('ANNEE_FOND',   '1989');
define('DATE_CONV',    '12 mars 1987');
define('DATE_CONV_EN', 'March 12, 1987');
define('NB_MEMBRES',   '127');
define('NB_PAYS_FOND', '42');

// ────────────────────────────────────────────────────────────────────────────
// STATISTIQUES CLÉS 2024
// ────────────────────────────────────────────────────────────────────────────

define('STAT_CERTIF',         '284 750');
define('STAT_CERTIF_EN',      '284,750');
define('STAT_COMMERCE',       'USD 4,2 milliards');
define('STAT_COMMERCE_EN',    'USD 4.2 billion');
define('STAT_INSPECTEURS',    '2 800');
define('STAT_INSPECTEURS_EN', '2,800');
define('STAT_ESPECES',        '847');
define('STAT_LABOS',          '234');

// ────────────────────────────────────────────────────────────────────────────
// CONFIGURATION TECHNIQUE
// ────────────────────────────────────────────────────────────────────────────

define('LANG_DEFAULT',   'fr');
define('LANGS_DISPO',    ['fr', 'en']);
define('DATE_FORMAT_FR', 'd/m/Y');
define('DATE_FORMAT_EN', 'm/d/Y');
define('TIMEZONE',       'Europe/Zurich');

// ────────────────────────────────────────────────────────────────────────────
// FICHIERS DE STOCKAGE (pour les formulaires, sans DB)
// ────────────────────────────────────────────────────────────────────────────

define('FICHIER_ABONNES',      dirname(__DIR__) . '/storage/abonnes.txt');
define('FICHIER_DEMANDES',     dirname(__DIR__) . '/storage/demandes.txt');
define('FICHIER_CONTACTS',     dirname(__DIR__) . '/storage/contacts.txt');
define('FICHIER_SIGNALEMENTS', dirname(__DIR__) . '/storage/signalements.txt');

// Définir le fuseau horaire par défaut
date_default_timezone_set(TIMEZONE);
