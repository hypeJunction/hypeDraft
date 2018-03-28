<?php

namespace hypeJunction\Draft;

use DateTime;
use DateTimeZone;
use Elgg\BadRequestException;
use Elgg\Http\ResponseBuilder;
use Elgg\HttpException;
use Elgg\Request;
use hypeJunction\Fields\Field;
use hypeJunction\Fields\FieldInterface;
use hypeJunction\Time;

class AutosaveAction {

	/**
	 * Autosave action
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws BadRequestException
	 * @throws HttpException
	 * @throws \Elgg\EntityNotFoundException
	 * @throws \Elgg\EntityPermissionsException
	 * @throws \InvalidParameterException
	 */
	public function __invoke(Request $request) {

		$guid = (int) $request->getParam('guid');
		$type = $request->getParam('type');
		$subtype = $request->getParam('subtype');
		$hash = $request->getParam('_hash');

		$hmac = elgg_build_hmac([
			'guid' => $guid,
			'type' => $type,
			'subtype' => $subtype,
		]);

		if (!$hmac->matchesToken($hash)) {
			$msg = $request->elgg()->echo('draft:autosave:error:hmac');
			throw new BadRequestException($msg);
		}

		$svc = $request->elgg()->{'posts.model'};
		/* @var $svc \hypeJunction\Post\Model */

		if (!$guid) {
			$request->setParam('published_status', H_DRAFT);
			$entity = $svc->save($request);
		} else {
			$entity = get_entity($guid);

			$fields = $svc->getFields($entity, Field::CONTEXT_CREATE_FORM);

			$fields = $fields->filter(function (FieldInterface $field) {
				if (in_array($field->name, \ElggEntity::$primary_attr_names)) {
					return false;
				}

				return true;
			});

			$values = [];

			foreach ($fields as $field) {
				/* @var $field FieldInterface */

				$name = $field->name;

				$value = $field->raw($request, $entity);
				if (!isset($value)) {
					// Field is not present
					continue;
				}

				$values[$name] = $value;
			}

			$revisions = $entity->getAnnotations([
				'annotation_names' => 'autosave_revision',
				'guids' => (int) $entity->guid,
				'annotation_owner_guids' => elgg_get_logged_in_user_guid(),
				'limit' => 1,
			]);

			if ($revisions) {
				$revision = array_shift($revisions);
				/* @var $revision \ElggAnnotation */

				$revision->value = json_encode($values);
				$revision->save();
			} else {
				$entity->annotate('autosave_revision', json_encode($values), ACCESS_PUBLIC);
			}
		}

		if (!$entity) {
			$msg = $request->elgg()->echo('draft:autosave:error');
			throw new HttpException($msg);
		}

		$tz = new DateTimeZone(Time::getClientTimezone());
		$dt = new DateTime('now', $tz);

		$data = [
			'guid' => $entity->guid,
			'type' => $entity->type,
			'subtype' => $entity->subtype,
			'_hash' => elgg_build_hmac([
				'guid' => $entity->guid,
				'type' => $entity->type,
				'subtype' => $entity->subtype,
			])->getToken(),
			'last_saved' => $dt->format(elgg_echo('friendlytime:date_format')),
		];

		return elgg_ok_response($data);
	}
}
