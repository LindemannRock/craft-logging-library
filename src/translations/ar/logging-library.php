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
    'Found {count, number} {count, plural, =1{error} other{errors}}' => 'تم العثور على {count, number} {count, plural, =1{خطأ} other{أخطاء}}',
    'Value must be a whole number.' => 'يجب أن تكون القيمة عدداً صحيحاً.',

    // Settings: General
    'General Settings' => 'الإعدادات العامة',
    'Plugin Name' => 'اسم الإضافة',
    'The name of the plugin as it appears in the Control Panel menu' => 'اسم الإضافة كما يظهر في قائمة لوحة التحكم',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>pluginName</code> في <code>config/logging-library.php</code>.',
    'Force Enable Log Viewers' => 'فرض تفعيل عارضات السجلات',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'فرض تفعيل عارضات السجلات المستندة إلى الملفات حتى عند اكتشاف بيئة edge أو مؤقتة. يؤثر هذا على Logging Library وقسم السجلات المخصص لكل إضافة.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>forceEnableLogViewer</code> في <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'اكتشفت Logging Library بيئة edge أو مؤقتة، لذا تم إخفاء عارضات السجلات المستندة إلى الملفات من العرض المستقل <strong>جميع السجلات</strong> ومن قسم <strong>السجلات</strong> المخصص لكل إضافة. عارض القائمة الرئيسية غير متاح حتى تقوم بتفعيل هذا التجاوز. استخدم سجلات منصة الاستضافة الخاصة بك الأصلية، أو قم بتفعيل التجاوز إذا كان التخزين الدائم متاحاً.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'اكتشفت Logging Library بيئة edge أو مؤقتة، لكن عارضات السجلات المستندة إلى الملفات يتم تفعيلها قسراً. يؤثر هذا التجاوز على العرض المستقل <strong>جميع السجلات</strong> وقسم <strong>السجلات</strong> المخصص لكل إضافة.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'تضيف Logging Library عرضاً موحداً <strong>جميع السجلات</strong> إلى قائمة لوحة التحكم الرئيسية. تحتفظ الإضافات الفردية بأقسام <strong>السجلات</strong> المخصصة الخاصة بها.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'العرض الموحد <strong>جميع السجلات</strong> مخفي من قائمة لوحة التحكم الرئيسية. تحتفظ الإضافات الفردية بأقسام <strong>السجلات</strong> المخصصة الخاصة بها.',
    'Show Main Menu' => 'إظهار القائمة الرئيسية',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'إظهار Logging Library في التنقل الرئيسي للوحة التحكم كعرض موحد جميع السجلات عندما تكون عارضات السجلات المستندة إلى الملفات متاحة.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>showCpSection</code> في <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'إعدادات الواجهة',
    'Items Per Page' => 'العناصر في الصفحة',
    'Number of log entries to display per page in the log viewers' => 'عدد إدخالات السجل المراد عرضها في كل صفحة في عارضات السجلات',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>itemsPerPage</code> في <code>config/logging-library.php</code>.',
];
