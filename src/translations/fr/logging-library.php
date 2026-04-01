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

    // Navigation
    'All Logs' => 'Tous les journaux',
    'Logs' => 'Journaux',
    'Settings' => 'Paramètres',
    'System Logs' => 'Journaux système',
    'System' => 'Système',
    'General' => 'Général',
    'Interface' => 'Interface',

    // Log levels
    'All Levels' => 'Tous les niveaux',
    'Error' => 'Erreur',
    'Warning' => 'Avertissement',
    'Info' => 'Info',
    'Debug' => 'Debug',

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
    'Message' => 'Message',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Aucun fichier journal trouvé. Les fichiers journaux sont créés lors des activités du plugin.',
    'No log entries found for the selected filters.' => 'Aucune entrée de journal trouvée pour les filtres sélectionnés.',

    // Pagination
    'entry' => 'entrée',
    'entries' => 'entrées',

    // Row detail
    'Context' => 'Contexte',
    'No context data available.' => 'Aucune donnée de contexte disponible.',

    // Sidebar
    'Current Level' => 'Niveau actuel',
    'Current log level' => 'Niveau de journalisation actuel',
    'Retention' => 'Rétention',
    'days' => 'jours',
    'Available Logs' => 'Journaux disponibles',
    'file' => 'fichier',
    'files' => 'fichiers',
    'Current File' => 'Fichier actuel',
    'Entries' => 'Entrées',
    'Download File' => 'Télécharger le fichier',
    'Log Location' => 'Emplacement du journal',

    // Common
    'Save Settings' => 'Enregistrer les paramètres',

    // Controller messages
    'Settings saved.' => 'Paramètres enregistrés.',
    'Could not save settings.' => 'Impossible d\'enregistrer les paramètres.',

    // Validation messages
    'Found {count, number} {count, plural, =1{error} other{errors}}' => '{count, number} {count, plural, =1{erreur trouvée} other{erreurs trouvées}}',
    'Value must be a whole number.' => 'La valeur doit être un nombre entier.',

    // Settings: General
    'General Settings' => 'Paramètres généraux',
    'Plugin Name' => 'Nom du plugin',
    'The name of the plugin as it appears in the Control Panel menu' => 'Le nom du plugin tel qu\'il apparaît dans le menu du panneau de contrôle',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>pluginName</code> dans <code>config/logging-library.php</code>.',
    'Force Enable Log Viewers' => 'Forcer l\'activation des visionneuses de journaux',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forcer l\'activation des visionneuses de journaux basées sur des fichiers même lorsqu\'un environnement edge ou éphémère est détecté. Cela affecte Logging Library et la section Journaux dédiée de chaque plugin.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>forceEnableLogViewer</code> dans <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library a détecté un environnement edge ou éphémère. Les visionneuses de journaux basées sur des fichiers sont donc masquées pour la vue autonome <strong>Tous les journaux</strong> et pour la section <strong>Journaux</strong> dédiée de chaque plugin. La visionneuse du menu principal n\'est pas disponible tant que vous n\'activez pas ce remplacement. Utilisez les journaux natifs de votre plateforme d\'hébergement ou activez le remplacement si un stockage persistant est disponible.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library a détecté un environnement edge ou éphémère, mais les visionneuses de journaux basées sur des fichiers sont activées de force. Ce remplacement affecte la vue autonome <strong>Tous les journaux</strong> et la section <strong>Journaux</strong> dédiée de chaque plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library ajoute une vue consolidée <strong>Tous les journaux</strong> au menu principal du panneau de contrôle. Les plugins individuels conservent leurs propres sections <strong>Journaux</strong> dédiées.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'La vue consolidée <strong>Tous les journaux</strong> est masquée dans le menu principal du panneau de contrôle. Les plugins individuels conservent leurs propres sections <strong>Journaux</strong> dédiées.',
    'Show Main Menu' => 'Afficher le menu principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Afficher Logging Library dans la navigation principale du panneau de contrôle sous forme de vue consolidée Tous les journaux lorsque des visionneuses de journaux basées sur des fichiers sont disponibles.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>showCpSection</code> dans <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Paramètres d\'interface',
    'Items Per Page' => 'Éléments par page',
    'Number of log entries to display per page in the log viewers' => 'Nombre d\'entrées de journal à afficher par page dans les visionneuses de journaux',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>itemsPerPage</code> dans <code>config/logging-library.php</code>.',
];
