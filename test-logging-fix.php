<?php
// Test script to verify logging fix works correctly
// Run this after updating to 1.4.5 on the other installation

echo "=== Logging Library Fix Test ===\n\n";

// Test 1: Check version
echo "1. Checking installed version:\n";
echo "   Run: composer show lindemannrock/logging-library\n";
echo "   Expected: 1.4.5\n\n";

// Test 2: Database settings
echo "2. Check database settings:\n";
echo "   Run this SQL: SELECT * FROM translationmanager_settings\n";
echo "   Verify logLevel column value\n\n";

// Test 3: Test each log level
echo "3. Test procedure for each log level:\n";
echo "   a) Set logLevel to 'debug' in settings\n";
echo "   b) Clear logs: rm storage/logs/translation-manager-*.log\n";
echo "   c) Trigger test messages (visit any translation manager page)\n";
echo "   d) Check log file for all 4 levels (DEBUG, INFO, WARNING, ERROR)\n\n";

echo "4. Expected results:\n";
echo "   - debug: Shows all 4 message types\n";
echo "   - info: Shows INFO, WARNING, ERROR only\n";
echo "   - warning: Shows WARNING, ERROR only\n";
echo "   - error: Shows ERROR only\n\n";

echo "5. If debug messages still don't appear:\n";
echo "   - Check PHP error log for 'LOGGING-LIBRARY' debug messages\n";
echo "   - Look for lines showing target order and other plugins\n";
echo "   - Verify our target is at index 0\n";