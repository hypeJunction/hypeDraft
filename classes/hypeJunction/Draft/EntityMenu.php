<?php

namespace hypeJunction\Draft;

use Elgg\Hook;

class EntityMenu {

	/**
	 * Setup entity menu
	 *
	 * @param Hook $hook
	 *
	 * @return \ElggMenuItem[]|null
	 */
	public function __invoke(Hook $hook) {

		$entity = $hook->getEntityParam();

		if (!$entity instanceof \ElggObject || !$entity->canEdit()) {
			return null;
		}

		$menu = $hook->getValue();

		$svc = elgg()->{'posts.draft'};
		/* @var $svc \hypeJunction\Draft\DraftService */

		if ($svc->getPublishedStatus($entity) === H_DRAFT) {
			$menu[] = \ElggMenuItem::factory([
				'name' => 'publish',
				'text' => elgg_echo('post:publish'),
				'confirm' => true,
				'href' => elgg_generate_action_url('post/publish', [
					'guid' => $entity->guid,
				]),
				'icon' => 'eye',
			]);
		} else if ($svc->getPublishedStatus($entity) === H_PUBLISHED) {
			$menu[] = \ElggMenuItem::factory([
				'name' => 'archive',
				'text' => elgg_echo('post:archive'),
				'confirm' => elgg_echo('post:archive:confirm'),
				'href' => elgg_generate_action_url('post/archive', [
					'guid' => $entity->guid,
				]),
				'icon' => 'archive',
			]);
		} else if ($svc->getPublishedStatus($entity) === H_ARCHIVED) {
			$menu[] = \ElggMenuItem::factory([
				'name' => 'unarchive',
				'text' => elgg_echo('post:unarchive'),
				'confirm' => true,
				'href' => elgg_generate_action_url('post/unarchive', [
					'guid' => $entity->guid,
				]),
				'icon' => 'eye',
			]);
		}

		return $menu;
	}
}