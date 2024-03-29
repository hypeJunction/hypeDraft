<?php

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

$entity = $annotation->getEntity();
if (!$entity) {
	return;
}

$byline = '';
$owner = $annotation->getOwnerEntity();
if ($owner) {
	$byline = elgg_echo('byline', [$owner->getDisplayName()]);
}

echo elgg_view('output/url', [
		'href' => elgg_generate_entity_url($entity, 'edit', null, [
			'revision' => $annotation->id,
		]),
		'text' => elgg_echo('annotation:edit_history'),
	]) . ' ' . $byline;

echo '<br />';
echo elgg_view_friendly_time($annotation->time_created);