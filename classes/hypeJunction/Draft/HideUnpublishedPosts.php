<?php

namespace hypeJunction\Draft;

use Elgg\Hook;

/**
 * Hook into the access system and hide all unpublished posts from users who do not own the posts
 * This is much easier than trying to restrict and sync access
 */
class HideUnpublishedPosts {

	/**
	 * Invoke
	 *
	 * @param Hook $hook Hook
	 *
	 * @return mixed
	 */
	public function __invoke(Hook $hook) {

		$value = $hook->getValue();

		$ignore_access = $hook->getParam('ignore_access');
		if ($ignore_access) {
			return null;
		}

		$qb = $hook->getParam('query_builder');
		/* @var $qb \Elgg\Database\QueryBuilder */

		$table_alias = $hook->getParam('table_alias');
		if (!$table_alias || $table_alias instanceof \Closure) {
			return null;
		}

		$user_guid = $hook->getParam('user_guid');
		$owner_guid_column = $hook->getParam('owner_guid_column');
		$guid_column = $hook->getParam('guid_column');

		$alias = $qb->joinMetadataTable($table_alias, $guid_column, 'published_status', 'left');
		$value['ands'][] = $qb->merge([
			$qb->compare("$alias.value", 'IS NULL'),
			$qb->compare("$alias.value", '=', H_PUBLISHED, ELGG_VALUE_STRING),
			$qb->compare("$table_alias.$owner_guid_column", '=', $user_guid, ELGG_VALUE_INTEGER),
		], 'OR');

		return $value;
	}
}
