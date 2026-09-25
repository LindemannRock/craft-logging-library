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
    'File Logs' => 'Journaux sur fichiers',
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
    'View all file logs' => 'Afficher tous les journaux sur fichiers',
    'Download all file logs' => 'Télécharger tous les journaux sur fichiers',
    'Clear file log cache' => 'Vider le cache des journaux sur fichiers',
    'View runtime logs' => 'Afficher les journaux d\'exécution',
    'Manage settings' => 'Gérer les paramètres',

    // Common
    '{displayName} caches' => 'Caches {displayName}',

    // Controller messages
    'Settings saved.' => 'Paramètres enregistrés.',
    'Could not save settings.' => 'Impossible d\'enregistrer les paramètres.',
    'Log cache refreshed.' => 'Cache des journaux actualisé.',
    'Failed to refresh log cache.' => 'Échec de l\'actualisation du cache des journaux.',
    'Recent runtime logs cleared.' => 'Journaux d\'exécution récents effacés.',
    'Unable to clear recent runtime logs.' => 'Impossible d\'effacer les journaux d\'exécution récents.',
    'Plugin logging not configured' => 'Journalisation du plugin non configurée',
    'Log viewer is disabled for this plugin' => 'La visionneuse de journaux est désactivée pour ce plugin',
    'Log viewer is disabled for this environment' => 'La visionneuse de journaux est désactivée pour cet environnement',
    'Recent runtime logs are disabled' => 'Les journaux d\'exécution récents sont désactivés',
    'Log file not found' => 'Fichier journal introuvable',
    'Unable to determine plugin handle from URL' => 'Impossible de déterminer l\'identifiant du plugin depuis l\'URL',
    'User does not have permission to view logs' => 'L\'utilisateur n\'a pas la permission de consulter les journaux',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Afficher Logging Library dans la navigation principale. Cela n\'active ni ne désactive la capture des journaux.',
    'General Settings' => 'Paramètres généraux',
    'Force Enable Log Viewers' => 'Forcer l\'activation des visionneuses de journaux',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forcer l\'activation des visionneuses de journaux basées sur des fichiers même lorsqu\'un environnement edge ou éphémère est détecté. Cela affecte Logging Library et la section Journaux dédiée de chaque plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library a détecté un environnement edge ou éphémère. Les visionneuses de journaux basées sur des fichiers sont donc masquées pour la vue autonome <strong>Tous les journaux</strong> et pour la section <strong>Journaux</strong> dédiée de chaque plugin. La visionneuse du menu principal n\'est pas disponible tant que vous n\'activez pas ce remplacement. Utilisez les journaux natifs de votre plateforme d\'hébergement ou activez le remplacement si un stockage persistant est disponible.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library a détecté un environnement edge ou éphémère, mais les visionneuses de journaux basées sur des fichiers sont activées de force. Ce remplacement affecte la vue autonome <strong>Tous les journaux</strong> et la section <strong>Journaux</strong> dédiée de chaque plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library ajoute une vue consolidée <strong>Tous les journaux</strong> au menu principal du panneau de contrôle. Les plugins individuels conservent leurs propres sections <strong>Journaux</strong> dédiées.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'La vue consolidée <strong>Tous les journaux</strong> est masquée dans le menu principal du panneau de contrôle. Les plugins individuels conservent leurs propres sections <strong>Journaux</strong> dédiées.',
    'Show Main Menu' => 'Afficher le menu principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Afficher Logging Library dans la navigation principale du panneau de contrôle sous forme de vue consolidée Tous les journaux lorsque des visionneuses de journaux basées sur des fichiers sont disponibles.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Forcer les visionneuses de journaux sur fichiers',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Un environnement edge ou éphémère est détecté. Les visionneuses de fichiers sont masquées sauf si leur activation est forcée ; les {runtimeLogs} restent disponibles lorsqu\'ils sont activés. Ne forcez les visionneuses que si des fichiers journaux persistants sont disponibles.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Les visionneuses de fichiers sont disponibles. Les journaux d\'exécution sont facultatifs et indépendants de la journalisation sur fichiers.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min. : {min}, max. : {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Utilise la configuration du cache d’application. La base de données Redis peut être remplacée dans {file}. Pour vérifier la capture, déclenchez un message de journal et consultez les journaux d’exécution.',
    'Enable Runtime Logs' => 'Activer les journaux d\'exécution',
    'Skip Console Requests' => 'Ignorer les requêtes de console',
    'Skip Queue Requests' => 'Ignorer les requêtes de file d\'attente',
    'Retention (seconds)' => 'Conservation (secondes)',
    'Maximum Entries' => 'Nombre maximal d\'entrées',
    'Refresh Interval (seconds)' => 'Intervalle d\'actualisation (secondes)',
    'Maximum Message Bytes' => 'Octets maximaux par message',
    'Maximum Context Bytes' => 'Octets maximaux du contexte',
    'Captured Levels' => 'Niveaux capturés',
    'Include Sources and Categories' => 'Inclure les sources et les catégories',
    'Exclude Sources and Categories' => 'Exclure les sources et les catégories',
    'Category: {pattern}' => 'Catégorie : {pattern}',
    'Include Request User ID' => 'Inclure l\'ID utilisateur de la requête',
    'Advanced' => 'Avancé',
    'Configured Storage' => 'Stockage configuré',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Capturez les nouveaux messages dans les journaux d\'exécution. Les modifications s\'appliquent aux nouvelles requêtes ; redémarrez les processus de traitement de longue durée pour les appliquer. La désactivation ne supprime pas les journaux existants.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Limite le texte conservé pour chaque nouvelle entrée des journaux d\'exécution, en octets plutôt qu\'en caractères. Les messages plus longs sont raccourcis. Les journaux sur fichiers ne sont pas affectés.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Limite les données supplémentaires conservées pour chaque nouvelle entrée des journaux d\'exécution, telles que les détails des erreurs et les traces de pile, en octets après encodage JSON. Un contexte plus volumineux est raccourci. Les journaux sur fichiers ne sont pas affectés.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Fréquence d\'actualisation automatique des journaux d\'exécution. Définissez 0 pour la désactiver. Valeur actuelle : {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Choisissez les sources par nom ou saisissez un motif de catégorie comme {pattern}, puis appuyez sur Entrée. Laissez vide pour capturer toutes les sources. Les modifications concernent uniquement les nouveaux messages.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Choisissez les sources à ignorer ou saisissez un motif de catégorie, puis appuyez sur Entrée. Les exclusions sont prioritaires. Les entrées existantes ne sont pas supprimées.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Lorsque cette option est activée, les journaux d’exécution ignorent les requêtes en ligne de commande. Désactivez-la uniquement pour diagnostiquer des commandes de console ; les journaux sur fichiers et ceux de l’hébergeur restent inchangés.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Lorsque cette option est activée, les journaux d’exécution ignorent l’exécution de file d’attente détectée. Pour capturer les processus de file d’attente en console, désactivez les deux options d’exclusion et redémarrez les processus. Ceux-ci peuvent générer de grands volumes de journaux.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Ajoute l\'ID utilisateur authentifié de la requête. Les messages et le contexte peuvent contenir des données personnelles indépendamment de ce paramètre.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Âge maximal des entrées des journaux d’exécution, en secondes. Valeur actuelle : {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min. : {min} ({minDuration}), max. : {max} ({maxDuration})',
    '{count} second' => '{count} seconde',
    '{count} seconds' => '{count} secondes',
    '{count} minute' => '{count} minute',
    '{count} minutes' => '{count} minutes',
    '{count} hour' => '{count} heure',
    '{count} hours' => '{count} heures',
    '{count} day' => '{count} jour',
    '{count} days' => '{count} jours',

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
    'DB Queries' => 'Requêtes DB',
    'DB Commands' => 'Commandes DB',
    'DB Command::{method}' => 'Commande DB::{method}',
    'DB Connection' => 'Connexion DB',
    'DB Connection::{method}' => 'Connexion DB::{method}',
    'Redis Commands' => 'Commandes Redis',
    'Redis Connection' => 'Connexion Redis',
    'Redis Connection::{method}' => 'Connexion Redis::{method}',
    'URL Routing' => 'Routage d\'URL',
    'Web Request' => 'Requête Web',
    'Session' => 'Session',
    'Template Rendering' => 'Rendu de template',
    'Modules' => 'Modules',
    'Integration Service' => 'Service d\'intégration',

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
    'Clear runtime logs' => 'Effacer les journaux d\'exécution',
    'Clear recent runtime logs? This cannot be undone.' => 'Effacer les journaux d\'exécution récents ? Cette action est irréversible.',
    'Loading' => 'Chargement',
    'Download File' => 'Télécharger le fichier',
    'Log Location' => 'Emplacement du journal',
    'Runtime Store' => 'Stockage d\'exécution',
    'Craft cache' => 'cache Craft',
    'Redis unavailable' => 'Redis indisponible',
    'Redis (SELECT disabled)' => 'Redis (SELECT désactivé)',
    'Redis database {database}' => 'Base de données Redis {database}',
    'Runtime Location' => 'Emplacement d\'exécution',
    'Dedicated Redis key' => 'Clé Redis dédiée',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Les journaux d\'exécution récents utilisent un stockage de diagnostic limité et ne constituent pas un historique complet des journaux.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>{setting}</code> dans <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>forceEnableLogViewer</code> dans <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Ce paramètre est remplacé par le paramètre <code>showCpSection</code> dans <code>config/logging-library.php</code>.',
];
