<?php

$entity = elgg_extract('entity', $vars);

$svc = elgg()->{'posts.draft'};
/* @var $svc \hypeJunction\Draft\DraftService */

$status = $svc->getPublishedStatus($entity);

if (!$status || $status === H_PUBLISHED) {
	return;
}

$icons = [
	H_PUBLISHED => 'eye',
	H_DRAFT => 'eraser',
	H_ARCHIVED => 'archive',
];

$icon = '';

if (isset($icons[$status])) {
	$icon = elgg_view_icon($icons[$status]);
}

$status_text = $icon . elgg_echo("published_status:{$status}");

echo elgg_format_element('span', [
	'class' => 'elgg-listing-blog-status',
], $status_text);
