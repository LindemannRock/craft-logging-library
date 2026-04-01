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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'فحص سجلات النظام ومراجعة مخرجات تسجيل الإضافات وتجميع التشخيصات من مساحة عمل واحدة في لوحة التحكم.',
    'Open All Logs' => 'فتح جميع السجلات',

    // Navigation
    'All Logs' => 'جميع السجلات',
    'Logs' => 'السجلات',
    'Settings' => 'الإعدادات',
    'System Logs' => 'سجلات النظام',
    'System' => 'النظام',
    'General' => 'عام',
    'Interface' => 'الواجهة',

    // Log levels
    'All Levels' => 'جميع المستويات',
    'Error' => 'خطأ',
    'Warning' => 'تحذير',
    'Info' => 'معلومات',
    'Debug' => 'تصحيح',

    // Log sources
    'All Sources' => 'جميع المصادر',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'أخطاء PHP',
    'Other' => 'أخرى',

    // Filters
    'Select File' => 'اختيار ملف',
    'Select Date' => 'اختيار تاريخ',
    'Search messages and context...' => 'البحث في الرسائل والسياق...',

    // Table
    'Time' => 'الوقت',
    'Level' => 'المستوى',
    'Source' => 'المصدر',
    'User' => 'المستخدم',
    'Message' => 'الرسالة',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'لم يتم العثور على ملفات سجل. يتم إنشاء ملفات السجل عند حدوث أنشطة الإضافة.',
    'No log entries found for the selected filters.' => 'لم يتم العثور على إدخالات سجل للمرشحات المحددة.',

    // Pagination
    'entry' => 'إدخال',
    'entries' => 'إدخالات',

    // Row detail
    'Context' => 'السياق',
    'No context data available.' => 'لا تتوفر بيانات سياقية.',

    // Sidebar
    'Current Level' => 'المستوى الحالي',
    'Current log level' => 'مستوى السجل الحالي',
    'Retention' => 'الاحتفاظ',
    'days' => 'أيام',
    'Available Logs' => 'السجلات المتاحة',
    'file' => 'ملف',
    'files' => 'ملفات',
    'Current File' => 'الملف الحالي',
    'Entries' => 'الإدخالات',
    'Download File' => 'تنزيل الملف',
    'Log Location' => 'موقع السجل',

    // Common
    'Save Settings' => 'حفظ الإعدادات',

    // Controller messages
    'Settings saved.' => 'تم حفظ الإعدادات.',
    'Could not save settings.' => 'تعذّر حفظ الإعدادات.',

    // Validation messages
    'Value must be a whole number.' => 'يجب أن تكون القيمة عدداً صحيحاً.',

    // Settings: General
    'General Settings' => 'الإعدادات العامة',
    'Plugin Name' => 'اسم الإضافة',
    'The name of the plugin as it appears in the Control Panel menu' => 'اسم الإضافة كما يظهر في قائمة لوحة التحكم',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>pluginName</code> في <code>config/logging-library.php</code>.',
    'Show Main Menu' => 'إظهار القائمة الرئيسية',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'إظهار Logging Library في التنقل الرئيسي للوحة التحكم. عند التعطيل، تظل جميع السجلات متاحة من إعدادات الإضافة والروابط المباشرة.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>showCpSection</code> في <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'إعدادات الواجهة',
    'Items Per Page' => 'العناصر في الصفحة',
    'Number of log entries to display per page in the log viewers' => 'عدد إدخالات السجل المراد عرضها في كل صفحة في عارضات السجلات',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>itemsPerPage</code> في <code>config/logging-library.php</code>.',
];
