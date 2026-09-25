<?php
/**
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use Craft;
use craft\web\View;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Initial and refreshed log rows preserve and safely contain user labels.
 *
 * @since 5.19.0
 */
final class LogRowRenderingTest extends TestCase
{
    #[DataProvider('userLabelProvider')]
    public function testUserLabelsRemainCompleteAndEscapedInsideTruncatedCells(?string $label): void
    {
        $view = Craft::$app->getView();
        $mode = $view->getTemplateMode();
        $twigCache = null;
        try {
            $view->setTemplateMode(View::TEMPLATE_MODE_CP);
            $twigCache = $view->getTwig()->getCache();
            $view->getTwig()->setCache($this->createTrackedTempDirectory('ll-row-twig-'));
            foreach (['file', 'runtime', 'ajax'] as $variant) {
                $variables = [
                    'item' => [
                        'timestamp' => '2026-09-25T12:00:00Z',
                        'canonicalLevel' => 'info', 'category' => 'test',
                        'categoryLabel' => 'Test', 'userLabel' => $label,
                        'message' => 'Opening DB connection', 'context' => '',
                    ],
                    'levels' => ['info' => 'Info'], 'rowIndex' => 1, 'colspan' => 5,
                    'isStandalone' => true, 'supportsCategoryFilter' => true,
                    'isRuntime' => $variant !== 'file', 'showDateInTimeColumn' => false,
                ];
                $html = $variant === 'ajax'
                    ? $view->renderTemplate('logging-library/logs/_runtime-row', $variables)
                    : '<tr>' . $view->getTwig()->load('logging-library/logs/index')->renderBlock('tableRow', $variables) . '</tr>';
                $document = new \DOMDocument();
                $document->loadHTML('<?xml encoding="UTF-8"><table>' . $html . '</table>', LIBXML_NOERROR | LIBXML_NOWARNING);
                $xpath = new \DOMXPath($document);
                self::assertSame('truncate', $xpath->evaluate('string((//tr)[1]/td[4]/@class)'), $variant);
                self::assertSame($label ?? '', $xpath->evaluate('string((//tr)[1]/td[4]/@title)'), $variant);
                self::assertSame($label ?: Craft::t('logging-library', 'System'), trim($xpath->evaluate('string((//tr)[1]/td[4])')), $variant);
                self::assertSame(0.0, $xpath->evaluate('count((//tr)[1]/td[4]/*)'), 'Labels must never become HTML.');
                self::assertSame('Opening DB connection', trim($xpath->evaluate('string((//tr)[1]/td[5])')));
            }
        } finally {
            if ($twigCache !== null) {
                $view->getTwig()->setCache($twigCache);
            }
            $view->setTemplateMode($mode);
        }
    }

    public static function userLabelProvider(): iterable
    {
        yield 'short' => ['editor@example.com'];
        yield 'long' => ['__cs_browser_1790336339-26031@example.invalid'];
        yield 'unicode' => ['مستخدم.日本語@example.com'];
        yield 'markup' => ['<img src=x onerror="alert(1)"> & user'];
        yield 'empty' => [''];
        yield 'missing' => [null];
    }
}
