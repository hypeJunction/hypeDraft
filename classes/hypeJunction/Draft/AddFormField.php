<?php

namespace hypeJunction\Draft;

use Elgg\Hook;
use ElggEntity;
use InvalidParameterException;

class AddFormField {

	/**
	 * Add slug field
	 *
	 * @param Hook $hook Hook
	 *
	 * @return mixed
	 * @throws InvalidParameterException
	 */
	public function __invoke(Hook $hook) {

		$fields = $hook->getValue();
		/* @var $fields \hypeJunction\Fields\Collection */

		$fields->add('published_status', new PublishedStatusField([
			'type' => 'select',
			'options_values' => function (ElggEntity $entity) {
				return [
					H_DRAFT => elgg_echo('published_status:draft'),
					H_PUBLISHED => elgg_echo('published_status:published'),
				];
			},
			'required' => true,
			'section' => 'sidebar',
			'priority' => 110,
			'is_profile_field' => false,
		]));

		if ($revision = get_input('revision')) {
			// Populate fields values with revision properties

			$annotation = elgg_get_annotation_from_id($revision);
			if ($annotation) {
				$values = json_decode($annotation->value, true);

				foreach ($fields as $field) {
					$name = $field->name;
					if (isset($values[$name])) {
						$field->values = $values[$name];
					}
				}
			}
		}

		return $fields;
	}
}
