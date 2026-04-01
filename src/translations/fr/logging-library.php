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
    'Value must be a whole number.' => 'La valeur doit être un nombre entier.',

    // Settings: General
    'General Settings' => 'Paramètres généraux',
    'Plugin Name' => 'Nom du plugin',
    'The name of the plugin as it appears in the Control Panel menu' => 'Le nom du plugin tel qu\'il apparaît dans le menu du panneau de contrôle',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>pluginName</code> dans <code>config/logging-library.php</code>.',
    'Show Main Menu' => 'Afficher le menu principal',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'Afficher Logging Library dans la navigation principale du panneau de contrôle. Lorsqu\'elle est désactivée, Tous les journaux reste accessible depuis les paramètres du plugin et les URL directes.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>showCpSection</code> dans <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Paramètres d\'interface',
    'Items Per Page' => 'Éléments par page',
    'Number of log entries to display per page in the log viewers' => 'Nombre d\'entrées de journal à afficher par page dans les visionneuses de journaux',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>itemsPerPage</code> dans <code>config/logging-library.php</code>.',
];
