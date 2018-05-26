<?php

namespace hypeJunction\Draft;

use Elgg\Hook;

class AddLayoutModules {

	/**
	 * @elgg_plugin_hook modules object
	 *
	 * @param Hook $hook Hook
	 * @return array
	 */
	public function __invoke(Hook $hook) {

		$entity = $hook->getEntityParam();
		if (!$entity) {
			return;
		}

		if (!elgg_trigger_plugin_hook('uses:autosave', "$entity->type:$entity->subtype", $hook->getParams(), false)) {
			return;
		}

		$modules = $hook->getValue();

		$modules['history'] = [
			'enabled' => $entity->canEdit(),
			'position' => 'sidebar',
			'priority' => 900,
		];

		return $modules;
	}
}