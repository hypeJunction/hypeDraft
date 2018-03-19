<?php

namespace hypeJunction\Draft;

use ElggEntity;

class AddFormField {

	/**
	 * Add slug field
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return mixed
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$fields = $hook->getValue();

		$fields['published_status'] = [
			'#type' => 'select',
			'options_values' => function (ElggEntity $entity) {
				return [
					H_DRAFT => elgg_echo('published_status:draft'),
					H_PUBLISHED => elgg_echo('published_status:published'),
				];
			},
			'required' => true,
			'#section' => 'sidebar',
			'#setter' => function (ElggEntity $entity, $value) {
				return elgg()->{'posts.draft'}->setPublishedStatus($entity, $value);
			},
			'#getter' => function (ElggEntity $entity) {
				return elgg()->{'posts.draft'}->getPublishedStatus($entity);
			},
			'#priority' => 110,
			'#visibility' => function (\ElggEntity $entity) use ($hook) {
				$status = elgg()->{'posts.draft'}->getPublishedStatus($entity);
				if ($status && $status !== H_DRAFT) {
					return false;
				}

				$params = [
					'entity' => $entity,
				];

				return $hook->elgg()->hooks->trigger(
					'uses:published_status',
					"$entity->type:$entity->subtype",
					$params,
					true
				);
			},
		];

		if ($revision = get_input('revision')) {
			$annotation = elgg_get_annotation_from_id($revision);
			if ($annotation) {
				$values = json_decode($annotation->value, true);

				foreach ($fields as $key => &$field) {
					$name = elgg_extract('name', $field, $key);
					if (isset($values[$name])) {
						$field['value'] = $values[$name];
					}
				}
			}
		}

		return $fields;
	}
}
