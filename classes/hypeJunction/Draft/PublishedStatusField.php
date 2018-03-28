<?php

namespace hypeJunction\Draft;

use ElggEntity;
use hypeJunction\Fields\Field;
use Symfony\Component\HttpFoundation\ParameterBag;

class PublishedStatusField extends Field {

	/**
	 * {@inheritdoc}
	 */
	public function isVisible(ElggEntity $entity, $context = null) {
		$status = $this->service()->getPublishedStatus($entity);
		if ($status && $status !== H_DRAFT) {
			return false;
		}

		$params = [
			'entity' => $entity,
		];

		$enabled = elgg()->hooks->trigger(
			'uses:published_status',
			"$entity->type:$entity->subtype",
			$params,
			true
		);

		if (!$enabled) {
			return false;
		}

		return parent::isVisible($entity, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(ElggEntity $entity, ParameterBag $parameters) {
		$value = $parameters->get($this->name);
		$this->service()->setPublishedStatus($entity, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve(ElggEntity $entity) {
		return $this->service()->getPublishedStatus($entity);
	}

	/**
	 * Service
	 * @return DraftService
	 */
	public function service() {
		$drafts = elgg()->{'posts.draft'};
		/* @var $drafts \hypeJunction\Draft\DraftService */

		return $drafts;
	}
}
