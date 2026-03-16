<?php
/**
 * WSCMA — includes/functions.php
 * Fonctions utilitaires partagées par toutes les pages du site.
 * Inclus après config/lang.php dans chaque page PHP.
 * @version  1.0
 * @requires PHP 8.2 · config/lang.php · config/constants.php
 */

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 1 — formatDate()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Formate une date selon la langue active.
 * @param  string $date  Date au format YYYY-MM-DD
 * @param  string $lang  Langue active ('fr' ou 'en')
 * @return string        Date formatée ("15 mars 2024" ou "March 15, 2024")
 */
function formatDate(string $date, string $lang): string {
    // Tableau des mois en français
    $moisFr = [
        1=>'janvier', 2=>'février',  3=>'mars',      4=>'avril',
        5=>'mai',     6=>'juin',     7=>'juillet',   8=>'août',
        9=>'septembre', 10=>'octobre', 11=>'novembre', 12=>'décembre'
    ];
    $timestamp = strtotime($date);
    if ($timestamp === false) return $date;

    if ($lang === 'fr') {
        $jour  = (int) date('j', $timestamp);
        $mois  = $moisFr[(int) date('n', $timestamp)];
        $annee = date('Y', $timestamp);
        return $jour . ' ' . $mois . ' ' . $annee;
    }
    // Format anglais : March 15, 2024
    return date('F j, Y', $timestamp);
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 2 — formatDateCourte()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Formate une date en version courte selon la langue.
 * @param  string $date  Date au format YYYY-MM-DD
 * @param  string $lang  Langue active
 * @return string        Date courte ("15/03/2024" ou "03/15/2024")
 */
function formatDateCourte(string $date, string $lang): string {
    $timestamp = strtotime($date);
    if ($timestamp === false) return $date;
    if ($lang === 'fr') return date('d/m/Y', $timestamp);
    return date('m/d/Y', $timestamp);
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 3 — truncate()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Tronque un texte proprement sans couper un mot en plein milieu.
 * @param  string $texte   Le texte à tronquer
 * @param  int    $longueur Longueur maximale en caractères (défaut: 150)
 * @return string           Le texte tronqué avec "…" ou le texte original
 */
function truncate(string $texte, int $longueur = 150): string {
    // Supprimer les balises HTML éventuelles
    $texte = strip_tags($texte);
    if (mb_strlen($texte, 'UTF-8') <= $longueur) return $texte;
    // Trouver le dernier espace avant la longueur limite
    $coupe = mb_strrpos(mb_substr($texte, 0, $longueur, 'UTF-8'), ' ', 0, 'UTF-8');
    if ($coupe === false) $coupe = $longueur;
    return mb_substr($texte, 0, $coupe, 'UTF-8') . '…';
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 4 — badgeCategorie()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Génère le HTML d'un badge de catégorie coloré pour les actualités.
 * @param  string $categorie  La catégorie (alerte, communique, rapport, etc.)
 * @param  string $lang       Langue active
 * @return string             HTML du badge prêt à afficher
 */
function badgeCategorie(string $categorie, string $lang): string {
    $libelles = [
        'fr' => [
            'alerte'     => 'Alerte',
            'communique' => 'Communiqué',
            'rapport'    => 'Rapport',
            'evenement'  => 'Événement',
            'actualite'  => 'Actualité',
            'discours'   => 'Discours',
            'bulletin'   => 'Bulletin',
            'publication'=> 'Publication',
        ],
        'en' => [
            'alerte'     => 'Alert',
            'communique' => 'Press Release',
            'rapport'    => 'Report',
            'evenement'  => 'Event',
            'actualite'  => 'News',
            'discours'   => 'Speech',
            'bulletin'   => 'Bulletin',
            'publication'=> 'Publication',
        ],
    ];
    $libelle = $libelles[$lang][$categorie]
            ?? $libelles['fr'][$categorie]
            ?? ucfirst($categorie);

    $catSecure = htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8');
    $libSecure = htmlspecialchars($libelle,   ENT_QUOTES, 'UTF-8');
    return '<span class="badge badge-' . $catSecure . '">' . $libSecure . '</span>';
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 5 — isNouveau()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Vérifie si une date est récente (moins de 7 jours).
 * Utilisée pour afficher le badge "NOUVEAU" sur les actualités.
 * @param  string $date  Date au format YYYY-MM-DD
 * @return bool          true si la date est dans les 7 derniers jours
 */
function isNouveau(string $date): bool {
    $timestamp = strtotime($date);
    if ($timestamp === false) return false;
    $septJours = 7 * 24 * 3600;
    return (time() - $timestamp) < $septJours;
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 6 — activeClass()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Retourne la classe CSS 'active' si l'URL courante contient le chemin.
 * Utilisée pour surligner le bon item dans la navigation.
 * @param  string $chemin  Chemin à chercher dans l'URL courante
 * @return string          ' active' ou '' (chaîne vide)
 */
function activeClass(string $chemin): string {
    $urlCourante = $_SERVER['REQUEST_URI'] ?? '';
    return str_contains($urlCourante, $chemin) ? ' active' : '';
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 7 — tendanceIcone()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Retourne l'icône UTF-8 de tendance pour les prix du marché.
 * @param  string $tendance  'hausse', 'baisse' ou 'stable'
 * @return string            Icône avec classe CSS colorée
 */
function tendanceIcone(string $tendance): string {
    return match ($tendance) {
        'hausse' => '<span class="tendance tendance-hausse">▲</span>',
        'baisse' => '<span class="tendance tendance-baisse">▼</span>',
        default  => '<span class="tendance tendance-stable">●</span>',
    };
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 8 — niveauBadge()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Retourne le HTML du badge de niveau de certification opérateur.
 * @param  string $niveau  'bronze', 'argent', 'or', 'platine'
 * @param  string $lang    Langue active
 * @return string          HTML du badge avec emoji
 */
function niveauBadge(string $niveau, string $lang): string {
    $config = [
        'bronze'  => ['emoji' => '🥉', 'fr' => 'Bronze',  'en' => 'Bronze'],
        'argent'  => ['emoji' => '🥈', 'fr' => 'Argent',  'en' => 'Silver'],
        'or'      => ['emoji' => '🥇', 'fr' => 'Or',      'en' => 'Gold'],
        'platine' => ['emoji' => '💎', 'fr' => 'Platine', 'en' => 'Platinum'],
    ];
    $data    = $config[$niveau] ?? ['emoji' => '●', 'fr' => $niveau, 'en' => $niveau];
    $libelle = htmlspecialchars($data[$lang] ?? $data['fr'], ENT_QUOTES, 'UTF-8');
    $niv     = htmlspecialchars($niveau, ENT_QUOTES, 'UTF-8');
    return '<span class="badge badge-niveau badge-' . $niv . '">' . $data['emoji'] . ' ' . $libelle . '</span>';
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 9 — citesBadge()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Retourne le HTML du badge de statut CITES d'une espèce.
 * @param  string $statut  'Annexe_I', 'Annexe_II', 'Annexe_III', 'Non_liste'
 * @param  string $lang    Langue active
 * @return string          HTML du badge coloré
 */
function citesBadge(string $statut, string $lang): string {
    $config = [
        'Annexe_I'   => ['class' => 'badge-cites-1',
                         'fr' => 'CITES Annexe I',   'en' => 'CITES Annex I'],
        'Annexe_II'  => ['class' => 'badge-cites-2',
                         'fr' => 'CITES Annexe II',  'en' => 'CITES Annex II'],
        'Annexe_III' => ['class' => 'badge-cites-3',
                         'fr' => 'CITES Annexe III', 'en' => 'CITES Annex III'],
        'Non_liste'  => ['class' => 'badge-cites-0',
                         'fr' => 'Non listé',        'en' => 'Not Listed'],
    ];
    $data    = $config[$statut] ?? $config['Non_liste'];
    $libelle = htmlspecialchars($data[$lang] ?? $data['fr'], ENT_QUOTES, 'UTF-8');
    return '<span class="badge ' . $data['class'] . '">' . $libelle . '</span>';
}

// ────────────────────────────────────────────────────────────────────────────
// FONCTION 10 — sauvegarderLigne()
// ────────────────────────────────────────────────────────────────────────────

/**
 * Sauvegarde une ligne de données dans un fichier texte (remplace la DB).
 * Crée le dossier storage/ si inexistant.
 * @param  string $fichier  Chemin absolu du fichier de stockage
 * @param  string $ligne    Contenu à ajouter (une ligne)
 * @return bool             true si succès, false si erreur
 */
function sauvegarderLigne(string $fichier, string $ligne): bool {
    // Créer le dossier parent si nécessaire
    $dossier = dirname($fichier);
    if (!is_dir($dossier)) {
        mkdir($dossier, 0750, true);
    }
    // Horodatage + données + saut de ligne
    $contenu = date('Y-m-d H:i:s') . ' | ' . $ligne . PHP_EOL;
    return file_put_contents($fichier, $contenu, FILE_APPEND | LOCK_EX) !== false;
}
