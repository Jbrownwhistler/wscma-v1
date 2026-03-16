<?php
/**
 * WSCMA — config/lang.php
 * Gestionnaire de langue bilingue FR/EN via session PHP.
 * Charge le bon tableau de traductions selon la langue active.
 * Expose les fonctions t() et th() à toutes les pages.
 * @version  1.0
 * @requires PHP 8.2 · config/constants.php
 */

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 1 — Démarrage de session
// ────────────────────────────────────────────────────────────────────────────

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 2 — Charger les constantes si pas encore chargées
// ────────────────────────────────────────────────────────────────────────────

$configPath = dirname(__DIR__) . '/config/constants.php';
if (!defined('SITE_NOM')) {
    require_once $configPath;
}

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 3 — Gestion du changement de langue via GET
// ────────────────────────────────────────────────────────────────────────────

// Si ?lang=fr ou ?lang=en dans l'URL
if (isset($_GET['lang'])) {
    $langDemandee = strtolower(trim($_GET['lang']));
    // Valider que la langue est supportée
    if (in_array($langDemandee, ['fr', 'en'], true)) {
        $_SESSION['wscma_lang'] = $langDemandee;
    }
    // Rediriger proprement sans le paramètre lang dans l'URL
    // pour éviter qu'il reste visible et puisse être mis en cache
    $urlPropre = strtok($_SERVER['REQUEST_URI'], '?');
    // Sécurité : nettoyer l'URL pour éviter l'injection d'en-têtes HTTP
    $urlPropre = filter_var($urlPropre, FILTER_SANITIZE_URL);
    $urlPropre = preg_replace('/[\r\n]/', '', $urlPropre);
    header('Location: ' . $urlPropre);
    exit;
}

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 4 — Déterminer la langue active
// ────────────────────────────────────────────────────────────────────────────

// Ordre de priorité :
// 1. Session PHP existante
// 2. Langue par défaut définie dans constants.php
$lang = $_SESSION['wscma_lang'] ?? LANG_DEFAULT;

// Sécurité : s'assurer que $lang est bien 'fr' ou 'en'
if (!in_array($lang, ['fr', 'en'], true)) {
    $lang = LANG_DEFAULT;
}

// Sauvegarder la langue validée en session
$_SESSION['wscma_lang'] = $lang;

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 5 — Charger le fichier de traductions
// ────────────────────────────────────────────────────────────────────────────

$fichierTrads = dirname(__DIR__) . '/lang/' . $lang . '.php';
if (!file_exists($fichierTrads)) {
    // Fallback vers le français si le fichier n'existe pas
    $fichierTrads = dirname(__DIR__) . '/lang/fr.php';
}
$translations = require $fichierTrads;

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 6 — Définir la fonction t() (traduction avec échappement HTML)
// ────────────────────────────────────────────────────────────────────────────

/**
 * Retourne la traduction d'une clé avec échappement HTML.
 * Utiliser pour tout texte affiché dans le HTML.
 * @param  string $key  La clé de traduction (ex: 'nav.organisation')
 * @return string       La valeur traduite et sécurisée
 */
function t(string $key): string {
    global $translations;
    $valeur = $translations[$key] ?? '[' . $key . ']';
    return htmlspecialchars($valeur, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 7 — Définir la fonction th() (traduction sans échappement)
// ────────────────────────────────────────────────────────────────────────────

/**
 * Retourne la traduction d'une clé SANS échappement HTML.
 * Utiliser UNIQUEMENT quand la valeur contient du HTML intentionnel.
 * @param  string $key  La clé de traduction
 * @return string       La valeur traduite brute
 */
function th(string $key): string {
    global $translations;
    return $translations[$key] ?? '[' . $key . ']';
}

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 8 — Définir la fonction tl() (traduction depuis un tableau de données)
// ────────────────────────────────────────────────────────────────────────────

/**
 * Retourne la valeur FR ou EN d'un champ dans un tableau de données.
 * Utilisée pour lire les données des fichiers data/*.php
 * @param  array  $item  Tableau de données (ex: $document)
 * @param  string $champ Nom du champ sans suffixe (ex: 'nom')
 * @param  string $lang  Langue active ('fr' ou 'en')
 * @return string        La valeur traduite et sécurisée
 */
function tl(array $item, string $champ, string $lang): string {
    $cle     = $champ . '_' . $lang;
    $cleFr   = $champ . '_fr';
    $valeur  = $item[$cle] ?? $item[$cleFr] ?? '';
    return htmlspecialchars($valeur, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// ────────────────────────────────────────────────────────────────────────────
// ÉTAPE 9 — Définir la fonction langUrl()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Génère l'URL de changement de langue pour la page courante.
 * @param  string $targetLang La langue cible ('fr' ou 'en')
 * @return string             URL avec le paramètre ?lang=xx
 */
function langUrl(string $targetLang): string {
    $urlCourante = strtok($_SERVER['REQUEST_URI'], '?');
    return htmlspecialchars($urlCourante . '?lang=' . $targetLang,
                            ENT_QUOTES, 'UTF-8');
}
