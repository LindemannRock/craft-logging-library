<?php
/**
 * Logging Library plugin for Craft CMS 5.x
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

return [
    // Plugin meta
    'Logging Library' => 'Logging Library',
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspectez les journaux système, examinez les sorties de journalisation des plugins et centralisez les diagnostics depuis un espace de travail du panneau de contrôle.',
    'Open All Logs' => 'Ouvrir tous les journaux',
    'Open Settings' => 'Ouvrir les paramètres',

    // Navigation
    'All Logs' => 'Tous les journaux',
    'Runtime Logs' => 'Journaux d\'exécution',
    'Logs' => 'Journaux',
    'Settings' => 'Paramètres',
    'System Logs' => 'Journaux système',
    'System' => 'Système',
    'Plugins' => 'Plugins',
    'General' => 'Général',
    'Interface' => 'Interface',

    // Permissions
    'View all system logs' => 'Afficher tous les journaux système',
    'Download all system logs' => 'Télécharger tous les journaux système',
    'Clear cache' => 'Vider le cache',
    'Manage settings' => 'Gérer les paramètres',

    // Common
    '{displayName} caches' => 'Caches {displayName}',

    // Controller messages
    'Settings saved.' => 'Paramètres enregistrés.',
    'Could not save settings.' => 'Impossible d\'enregistrer les paramètres.',
    'Log cache refreshed.' => 'Cache des journaux actualisé.',
    'Failed to refresh log cache.' => 'Échec de l\'actualisation du cache des journaux.',
    'Recent runtime logs cleared.' => 'Journaux d\'exécution récents effacés.',
    'Plugin logging not configured' => 'Journalisation du plugin non configurée',
    'Log viewer is disabled for this plugin' => 'La visionneuse de journaux est désactivée pour ce plugin',
    'Log viewer is disabled for this environment' => 'La visionneuse de journaux est désactivée pour cet environnement',
    'Recent runtime logs are disabled' => 'Les journaux d\'exécution récents sont désactivés',
    'Log file not found' => 'Fichier journal introuvable',
    'Unable to determine plugin handle from URL' => 'Impossible de déterminer l\'identifiant du plugin depuis l\'URL',
    'User does not have permission to view logs' => 'L\'utilisateur n\'a pas la permission de consulter les journaux',

    // Settings: General
    'General Settings' => 'Paramètres généraux',
    'Force Enable Log Viewers' => 'Forcer l\'activation des visionneuses de journaux',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forcer l\'activation des visionneuses de journaux basées sur des fichiers même lorsqu\'un environnement edge ou éphémère est détecté. Cela affecte Logging Library et la section Journaux dédiée de chaque plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library a détecté un environnement edge ou éphémère. Les visionneuses de journaux basées sur des fichiers sont donc masquées pour la vue autonome <strong>Tous les journaux</strong> et pour la section <strong>Journaux</strong> dédiée de chaque plugin. La visionneuse du menu principal n\'est pas disponible tant que vous n\'activez pas ce remplacement. Utilisez les journaux natifs de votre plateforme d\'hébergement ou activez le remplacement si un stockage persistant est disponible.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library a détecté un environnement edge ou éphémère, mais les visionneuses de journaux basées sur des fichiers sont activées de force. Ce remplacement affecte la vue autonome <strong>Tous les journaux</strong> et la section <strong>Journaux</strong> dédiée de chaque plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library ajoute une vue consolidée <strong>Tous les journaux</strong> au menu principal du panneau de contrôle. Les plugins individuels conservent leurs propres sections <strong>Journaux</strong> dédiées.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'La vue consolidée <strong>Tous les journaux</strong> est masquée dans le menu principal du panneau de contrôle. Les plugins individuels conservent leurs propres sections <strong>Journaux</strong> dédiées.',
    'Show Main Menu' => 'Afficher le menu principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Afficher Logging Library dans la navigation principale du panneau de contrôle sous forme de vue consolidée Tous les journaux lorsque des visionneuses de journaux basées sur des fichiers sont disponibles.',

    // Settings: Interface
    'Interface Settings' => 'Paramètres d\'interface',

    // Log levels
    'All Levels' => 'Tous les niveaux',
    'Error' => 'Erreur',
    'Warning' => 'Avertissement',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Inconnu',

    // Log sources
    'All Sources' => 'Toutes les sources',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'Erreurs PHP',
    'Other' => 'Autre',

    // Filters
    'Select File' => 'Sélectionner un fichier',
    'Select Date' => 'Sélectionner une date',
    'Search messages and context...' => 'Rechercher dans les messages et le contexte...',

    // Table
    'Time' => 'Heure',
    'Level' => 'Niveau',
    'Source' => 'Source',
    'User' => 'Utilisateur',
    'Request User' => 'Utilisateur de la requête',
    'User #{id}' => 'Utilisateur #{id}',
    'Message' => 'Message',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Aucun fichier journal trouvé. Les fichiers journaux sont créés lors des activités du plugin.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Aucun journal d\'exécution récent trouvé. Les journaux d\'exécution sont de courte durée et n\'apparaissent qu\'après la capture d\'événements correspondants.',
    'No log entries found for the selected filters.' => 'Aucune entrée de journal trouvée pour les filtres sélectionnés.',

    // Pagination
    'entry' => 'entrée',
    'entries' => 'entrées',

    // Sidebar
    'Current Level' => 'Niveau actuel',
    'Current log level' => 'Niveau de journalisation actuel',
    'Retention' => 'Rétention',
    'days' => 'jours',
    'Available Logs' => 'Journaux disponibles',
    'file' => 'fichier',
    'files' => 'fichiers',
    'Current File' => 'Fichier actuel',
    'Log entries' => 'Entrées de journal',
    'Refresh Cache' => 'Actualiser le cache',
    'Clear Runtime Logs' => 'Effacer les journaux d\'exécution',
    'Loading' => 'Chargement',
    'Download File' => 'Télécharger le fichier',
    'Log Location' => 'Emplacement du journal',
    'Runtime Store' => 'Stockage d\'exécution',
    'Craft cache' => 'cache Craft',
    'Redis ({cache})' => 'Redis ({cache})',
    'Runtime Location' => 'Emplacement d\'exécution',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'Les journaux d\'exécution récents sont stockés dans le cache Craft et sont destinés aux diagnostics de courte durée, pas à l\'historique complet des journaux.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>forceEnableLogViewer</code> dans <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>showCpSection</code> dans <code>config/logging-library.php</code>.',
];
