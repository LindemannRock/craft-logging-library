<?php
/**
 * Logging Library for Craft CMS
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\helpers;

use Craft;
use craft\elements\User;

/**
 * Adds display labels to normalized log rows that carry optional user markers.
 *
 * @since 5.14.0
 */
class UserLabelHelper
{
    /**
     * Attach display labels for optional user IDs without querying per row.
     */
    public static function withUserLabels(array $records): array
    {
        $ids = [];

        foreach ($records as $record) {
            $user = (string)($record['user'] ?? '');
            if (preg_match('/^user:(\d+)$/', $user, $matches)) {
                $ids[] = (int)$matches[1];
            }
        }

        $usernames = [];
        $ids = array_values(array_unique(array_filter($ids)));

        if ($ids !== []) {
            try {
                foreach (User::find()->id($ids)->status(null)->all() as $user) {
                    $usernames[(int)$user->id] = (string)$user->username;
                }
            } catch (\Throwable) {
                $usernames = [];
            }
        }

        foreach ($records as &$record) {
            $user = (string)($record['user'] ?? '');
            $record['userLabel'] = Craft::t('logging-library', 'System');

            if (preg_match('/^user:(\d+)$/', $user, $matches)) {
                $id = (int)$matches[1];
                $record['userLabel'] = $usernames[$id]
                    ?? Craft::t('logging-library', 'User #{id}', ['id' => $id]);
            } elseif ($user !== '') {
                $record['userLabel'] = $user;
            }
        }
        unset($record);

        return $records;
    }
}
