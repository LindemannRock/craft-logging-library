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
    'Open Settings' => 'فتح الإعدادات',

    // Navigation
    'All Logs' => 'جميع السجلات',
    'Runtime Logs' => 'سجلات وقت التشغيل',
    'Logs' => 'السجلات',
    'Settings' => 'الإعدادات',
    'System Logs' => 'سجلات النظام',
    'System' => 'النظام',
    'Plugins' => 'الإضافات',
    'General' => 'عام',
    'Interface' => 'الواجهة',

    // Permissions
    'View all system logs' => 'عرض جميع سجلات النظام',
    'Download all system logs' => 'تنزيل جميع سجلات النظام',
    'Clear cache' => 'مسح Cache',
    'Manage settings' => 'إدارة الإعدادات',

    // Common
    '{displayName} caches' => 'Caches الخاصة بـ {displayName}',

    // Controller messages
    'Settings saved.' => 'تم حفظ الإعدادات.',
    'Could not save settings.' => 'تعذّر حفظ الإعدادات.',
    'Log cache refreshed.' => 'تم تحديث Cache السجل.',
    'Failed to refresh log cache.' => 'تعذّر تحديث Cache السجل.',
    'Recent runtime logs cleared.' => 'تم مسح سجلات وقت التشغيل الأخيرة.',
    'Unable to clear recent runtime logs.' => 'تعذّر مسح سجلات وقت التشغيل الأخيرة.',
    'Plugin logging not configured' => 'تسجيل الإضافة غير مكوّن',
    'Log viewer is disabled for this plugin' => 'عارض السجلات معطّل لهذه الإضافة',
    'Log viewer is disabled for this environment' => 'عارض السجلات معطّل لهذه البيئة',
    'Recent runtime logs are disabled' => 'سجلات وقت التشغيل الحديثة معطّلة',
    'Log file not found' => 'ملف السجل غير موجود',
    'Unable to determine plugin handle from URL' => 'تعذّر تحديد مُعرِّف الإضافة من URL',
    'User does not have permission to view logs' => 'ليس لدى المستخدم صلاحية لعرض السجلات',

    // Settings: General
    'General Settings' => 'الإعدادات العامة',
    'Force Enable Log Viewers' => 'فرض تفعيل عارضات السجلات',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'فرض تفعيل عارضات السجلات المستندة إلى الملفات حتى عند اكتشاف بيئة edge أو مؤقتة. يؤثر هذا على Logging Library وقسم السجلات المخصص لكل إضافة.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'اكتشفت Logging Library بيئة edge أو مؤقتة، لذا تم إخفاء عارضات السجلات المستندة إلى الملفات من العرض المستقل <strong>جميع السجلات</strong> ومن قسم <strong>السجلات</strong> المخصص لكل إضافة. عارض القائمة الرئيسية غير متاح حتى تقوم بتفعيل هذا التجاوز. استخدم سجلات منصة الاستضافة الخاصة بك الأصلية، أو قم بتفعيل التجاوز إذا كان التخزين الدائم متاحاً.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'اكتشفت Logging Library بيئة edge أو مؤقتة، لكن عارضات السجلات المستندة إلى الملفات يتم تفعيلها قسراً. يؤثر هذا التجاوز على العرض المستقل <strong>جميع السجلات</strong> وقسم <strong>السجلات</strong> المخصص لكل إضافة.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'تضيف Logging Library عرضاً موحداً <strong>جميع السجلات</strong> إلى قائمة لوحة التحكم الرئيسية. تحتفظ الإضافات الفردية بأقسام <strong>السجلات</strong> المخصصة الخاصة بها.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'العرض الموحد <strong>جميع السجلات</strong> مخفي من قائمة لوحة التحكم الرئيسية. تحتفظ الإضافات الفردية بأقسام <strong>السجلات</strong> المخصصة الخاصة بها.',
    'Show Main Menu' => 'إظهار القائمة الرئيسية',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'إظهار Logging Library في التنقل الرئيسي للوحة التحكم كعرض موحد جميع السجلات عندما تكون عارضات السجلات المستندة إلى الملفات متاحة.',

    // Settings: Interface
    'Interface Settings' => 'إعدادات الواجهة',

    // Log levels
    'All Levels' => 'جميع المستويات',
    'Error' => 'خطأ',
    'Warning' => 'تحذير',
    'Info' => 'معلومات',
    'Debug' => 'تصحيح',
    'Unknown' => 'غير معروف',

    // Log sources
    'All Sources' => 'جميع المصادر',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'أخطاء PHP',
    'Other' => 'أخرى',
    'DB Queries' => 'استعلامات DB',
    'DB Commands' => 'أوامر DB',
    'DB Command::{method}' => 'أمر DB::{method}',
    'DB Connection' => 'اتصال DB',
    'DB Connection::{method}' => 'اتصال DB::{method}',
    'Redis Commands' => 'أوامر Redis',
    'Redis Connection' => 'اتصال Redis',
    'Redis Connection::{method}' => 'اتصال Redis::{method}',
    'URL Routing' => 'توجيه URL',
    'Web Request' => 'طلب الويب',
    'Session' => 'الجلسة',
    'Template Rendering' => 'عرض القالب',
    'Modules' => 'الوحدات',
    'Integration Service' => 'خدمة التكامل',

    // Filters
    'Select File' => 'اختيار ملف',
    'Select Date' => 'اختيار تاريخ',
    'Search messages and context...' => 'البحث في الرسائل والسياق...',

    // Table
    'Time' => 'الوقت',
    'Level' => 'المستوى',
    'Source' => 'المصدر',
    'User' => 'المستخدم',
    'Request User' => 'مستخدم الطلب',
    'User #{id}' => 'مستخدم #{id}',
    'Message' => 'الرسالة',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'لم يتم العثور على ملفات سجل. يتم إنشاء ملفات السجل عند حدوث أنشطة الإضافة.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'لم يتم العثور على سجلات وقت تشغيل حديثة. سجلات وقت التشغيل قصيرة الأجل ولا تظهر إلا بعد التقاط أحداث مطابقة.',
    'No log entries found for the selected filters.' => 'لم يتم العثور على إدخالات سجل للمرشحات المحددة.',

    // Pagination
    'entry' => 'إدخال',
    'entries' => 'إدخالات',

    // Sidebar
    'Current Level' => 'المستوى الحالي',
    'Current log level' => 'مستوى السجل الحالي',
    'Retention' => 'الاحتفاظ',
    'days' => 'أيام',
    'Available Logs' => 'السجلات المتاحة',
    'file' => 'ملف',
    'files' => 'ملفات',
    'Current File' => 'الملف الحالي',
    'Log entries' => 'إدخالات السجل',
    'Refresh Cache' => 'تحديث Cache',
    'Clear Runtime Logs' => 'مسح سجلات وقت التشغيل',
    'Clear recent runtime logs? This cannot be undone.' => 'مسح سجلات وقت التشغيل الأخيرة؟ لا يمكن التراجع عن هذا الإجراء.',
    'Loading' => 'جار التحميل',
    'Download File' => 'تنزيل الملف',
    'Log Location' => 'موقع السجل',
    'Runtime Store' => 'مخزن وقت التشغيل',
    'Craft cache' => 'Cache الخاص بـ Craft',
    'Redis ({cache})' => 'Redis ({cache})',
    'Runtime Location' => 'موقع وقت التشغيل',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'يتم تخزين سجلات وقت التشغيل الحديثة في Cache الخاص بـ Craft وهي مخصصة للتشخيصات قصيرة الأجل، وليس لسجل السجلات الكامل.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>forceEnableLogViewer</code> في <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>showCpSection</code> في <code>config/logging-library.php</code>.',
];
