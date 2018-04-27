<?php

namespace hypeJunction\Draft;

use Elgg\Hook;

class HideDraftComments {

	/**
	 * Invoke
	 *
	 * @param Hook $hook Hook
	 *
	 * @return mixed
	 */
	public function __invoke(Hook $hook) {

		$entity = $hook->getEntityParam();

		if (!$entity) {
			return;
		}

		if ($entity->published_status && $entity->published_status !== H_PUBLISHED) {
			return false;
		}
	}
}
