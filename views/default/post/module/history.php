<?php

$entity = elgg_extract('entity', $vars);

$output = elgg_list_annotations([
	'annotation_names' => ['autosave_revision', 'edit_history'],
	'guids' => $entity->guid,
	'limit' => 20,
	'pagination' => false,
]);

if (!$output) {
	return;
}

echo elgg_view('post/module', [
	'title' => elgg_echo('post:history'),
	'body' => $output,
	'collapsed' => true,
	'class' => 'post-history',
]);
