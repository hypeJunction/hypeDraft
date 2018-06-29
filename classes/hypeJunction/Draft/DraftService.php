<?php

namespace hypeJunction\Draft;

use ElggEntity;

class DraftService {

	/**
	 * Set published status
	 *
	 * @param ElggEntity $entity Entity
	 * @param string     $status 'published', 'draft'
	 *
	 * @return void
	 */
	public function setPublishedStatus(ElggEntity $entity, $status = null) {
		$prev_status = $this->getPublishedStatus($entity);

		$entity->setVolatileData('prev_published_status', $prev_status);

		if ($prev_status == H_PUBLISHED && $status == H_PUBLISHED) {
			if (elgg_trigger_before_event('unpublish', $entity->type, $entity)) {
				unset($entity->future_published_status);
				$entity->published_status = $status;
			} else {
				$entity->future_published_status = $status;
			}

			elgg_trigger_event('unpublish', $entity->type, $entity);
			elgg_trigger_after_event('unpublish', $entity->type, $entity);
		} else if ($status == H_PUBLISHED && $prev_status == H_DRAFT) {
			if (elgg_trigger_before_event('publish', $entity->type, $entity)) {
				unset($entity->future_published_status);
				$entity->published_status = $status;
			} else {
				$entity->future_published_status = $status;
			}

			elgg_trigger_event('publish', $entity->type, $entity);
			elgg_trigger_after_event('publish', $entity->type, $entity);
		} else {
			$entity->prev_published_status = $prev_status;
			$entity->published_status = $status;
		}
	}

	/**
	 * Get published status
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return string|null
	 */
	public function getPublishedStatus(ElggEntity $entity) {
		return $entity->published_status;
	}
}
