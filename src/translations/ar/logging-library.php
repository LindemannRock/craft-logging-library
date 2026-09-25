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
    'Setup' => 'الإعداد',
    'File Logs' => 'سجلات الملفات',
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
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'إظهار Logging Library في التنقل الرئيسي. لا يؤدي ذلك إلى تفعيل تسجيل السجلات أو تعطيله.',
    'General Settings' => 'الإعدادات العامة',
    'Force Enable Log Viewers' => 'فرض تفعيل عارضات السجلات',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'فرض تفعيل عارضات السجلات المستندة إلى الملفات حتى عند اكتشاف بيئة edge أو مؤقتة. يؤثر هذا على Logging Library وقسم السجلات المخصص لكل إضافة.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'اكتشفت Logging Library بيئة edge أو مؤقتة، لذا تم إخفاء عارضات السجلات المستندة إلى الملفات من العرض المستقل <strong>جميع السجلات</strong> ومن قسم <strong>السجلات</strong> المخصص لكل إضافة. عارض القائمة الرئيسية غير متاح حتى تقوم بتفعيل هذا التجاوز. استخدم سجلات منصة الاستضافة الخاصة بك الأصلية، أو قم بتفعيل التجاوز إذا كان التخزين الدائم متاحاً.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'اكتشفت Logging Library بيئة edge أو مؤقتة، لكن عارضات السجلات المستندة إلى الملفات يتم تفعيلها قسراً. يؤثر هذا التجاوز على العرض المستقل <strong>جميع السجلات</strong> وقسم <strong>السجلات</strong> المخصص لكل إضافة.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'تضيف Logging Library عرضاً موحداً <strong>جميع السجلات</strong> إلى قائمة لوحة التحكم الرئيسية. تحتفظ الإضافات الفردية بأقسام <strong>السجلات</strong> المخصصة الخاصة بها.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'العرض الموحد <strong>جميع السجلات</strong> مخفي من قائمة لوحة التحكم الرئيسية. تحتفظ الإضافات الفردية بأقسام <strong>السجلات</strong> المخصصة الخاصة بها.',
    'Show Main Menu' => 'إظهار القائمة الرئيسية',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'إظهار Logging Library في التنقل الرئيسي للوحة التحكم كعرض موحد جميع السجلات عندما تكون عارضات السجلات المستندة إلى الملفات متاحة.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'فرض تفعيل عارضات سجلات الملفات',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.' => 'تم اكتشاف بيئة edge أو بيئة مؤقتة. تُخفى عارضات الملفات ما لم يُفرض تفعيلها؛ وتظل سجلات وقت التشغيل متاحة عند تفعيلها. لا تفرض تفعيل عارضات الملفات إلا عند توفر ملفات سجلات دائمة.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'عارضات الملفات متاحة. سجلات وقت التشغيل اختيارية ومستقلة عن التسجيل في الملفات.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'الحد الأدنى: {min}، الحد الأقصى: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'يستخدم إعدادات Cache التطبيق. يمكن تجاوز إعداد قاعدة بيانات Redis في {file}. للتحقق من التسجيل، أنشئ رسالة سجل وتحقق من سجلات وقت التشغيل.',
    'Enable Runtime Logs' => 'تفعيل سجلات وقت التشغيل',
    'Skip Console Requests' => 'تخطي طلبات وحدة التحكم',
    'Skip Queue Requests' => 'تخطي طلبات قائمة الانتظار',
    'Retention (seconds)' => 'مدة الاحتفاظ (بالثواني)',
    'Maximum Entries' => 'الحد الأقصى للإدخالات',
    'Refresh Interval (seconds)' => 'فاصل التحديث (بالثواني)',
    'Maximum Message Bytes' => 'الحد الأقصى لبايتات الرسالة',
    'Maximum Context Bytes' => 'الحد الأقصى لبايتات السياق',
    'Captured Levels' => 'المستويات المسجلة',
    'Include Categories' => 'تضمين الفئات',
    'Exclude Categories' => 'استبعاد الفئات',
    'Include Request User ID' => 'تضمين ID مستخدم الطلب',
    'Advanced' => 'متقدم',
    'Configured Storage' => 'التخزين المهيأ',
    'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.' => 'يتبع التخزين إعدادات Cache في Craft. يتم اختيار قاعدة بيانات Redis من ملف الإعدادات فقط. هذا ليس اختبار اتصال؛ تحقق من التسجيل في سجلات وقت التشغيل.',
    'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.' => 'استخدم تخزين Cache مشتركاً عند وجود عدة خوادم. لا تجمع Cache الملفات المحلية سجلات النسخ الأخرى.',
    'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.' => 'تسري تغييرات التسجيل على الطلبات الجديدة. أعد تشغيل عمليات المعالجة طويلة التشغيل لتحميل الإعدادات المعدلة. لا يؤدي تعطيل التسجيل إلى مسح السجلات المخزنة.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'معدل التحديث التلقائي لسجلات وقت التشغيل. اضبط القيمة على 0 لتعطيله. الحالي: {duration}',
    'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.' => 'اختر فئات السجلات المراد تسجيلها، وليس كلمات في الرسالة. أدخل فئة واحدة لكل سطر، مثل {exact}، أو استخدم {prefix} لمطابقة الفئات التي تبدأ بـ {start}. اتركه فارغاً لتسجيل كل الفئات.',
    'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.' => 'تخطَّ فئات السجلات هذه حتى إذا كانت مضمنة أعلاه. أدخل فئة واحدة لكل سطر، مثل {exact} أو {prefix}. اتركه فارغاً لعدم إضافة استبعادات للفئات.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'عند التفعيل، تتخطى سجلات وقت التشغيل طلبات سطر الأوامر. عطّل هذا الخيار فقط لتشخيص أوامر وحدة التحكم؛ لا تتأثر سجلات الملفات وسجلات الاستضافة.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'عند التفعيل، تتخطى سجلات وقت التشغيل تنفيذ قائمة الانتظار المكتشف. لتسجيل عمليات معالجة قائمة الانتظار في وحدة التحكم، عطّل خياري التخطي وأعد تشغيل عمليات المعالجة. قد تولد عمليات المعالجة كميات كبيرة من السجلات.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'يضيف ID المستخدم المصادق عليه للطلب. قد تحتوي الرسائل والسياق على بيانات شخصية بغض النظر عن هذا الإعداد.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'الحد الأقصى لعمر إدخالات سجلات وقت التشغيل، بالثواني. الحالي: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'الحد الأدنى: {min} ({minDuration})، الحد الأقصى: {max} ({maxDuration})',
    '{count} second' => '{count} ثانية',
    '{count} seconds' => '{count} ثوانٍ',
    '{count} minute' => '{count} دقيقة',
    '{count} minutes' => '{count} دقائق',
    '{count} hour' => '{count} ساعة',
    '{count} hours' => '{count} ساعات',
    '{count} day' => '{count} يوم',
    '{count} days' => '{count} أيام',

    // Settings: Interface
    'Interface Settings' => 'إعدادات الواجهة',

    // Setup
    'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.' => 'اختر عروض السجلات المناسبة لهذه البيئة. تسجيل وقت التشغيل اختياري ويظل معطّلاً حتى يتم تفعيله.',
    'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.' => 'يمكن استخدام سجلات وقت التشغيل للتشخيصات الحديثة على الاستضافة المؤقتة. تحقق من التخزين المشترك قبل الاعتماد على سجلات عدة نسخ.',

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
    'Redis unavailable' => 'Redis غير متاح',
    'Redis (SELECT disabled)' => 'Redis (تم تعطيل SELECT)',
    'Redis database {database}' => 'قاعدة بيانات Redis {database}',
    'Runtime Location' => 'موقع وقت التشغيل',
    'Dedicated Redis key' => 'مفتاح Redis مخصص',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'تستخدم سجلات وقت التشغيل الحديثة مخزنًا تشخيصيًا محدودًا ولا تمثل سجلًا كاملاً للسجلات.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>{setting}</code> في <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>forceEnableLogViewer</code> في <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'يتم تجاوز هذا الإعداد بواسطة الإعداد <code>showCpSection</code> في <code>config/logging-library.php</code>.',
];
