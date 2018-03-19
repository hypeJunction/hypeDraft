<?php

namespace hypeJunction\Draft;

use Elgg\EntityPermissionsException;
use Elgg\Hook;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

class ArchiveAction {

	/**
	 * Archive a post
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws EntityPermissionsException
	 */
	public function __invoke(Request $request) {

		$entity = $request->getEntityParam();

		if (!$entity || !$entity->canEdit()) {
			throw new EntityPermissionsException();
		}

		$svc = elgg()->{'posts.draft'};
		/* @var $svc \hypeJunction\Draft\DraftService */

		$svc->setPublishedStatus($entity, H_ARCHIVED);

		$msg = $request->elgg()->echo('post:archive:success');

		return elgg_ok_response('', $msg);

	}
}