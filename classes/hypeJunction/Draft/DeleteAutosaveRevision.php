<?php

namespace hypeJunction\Draft;

use Elgg\Event;

class DeleteAutosaveRevision {

	/**
	 * Delete autosave revisions
	 *
	 * @elgg_event update object
	 *
	 * @param Event $event Event
	 *
	 * @return void
	 */
	public function __invoke(Event $event) {

		$entity = $event->getObject();

		if (!$entity instanceof \ElggObject) {
			return;
		}

		$entity->deleteAnnotations('autosave_revision');
	}
}