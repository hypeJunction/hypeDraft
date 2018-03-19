<?php

$entity = elgg_extract('entity', $vars);

if (!elgg_trigger_plugin_hook('uses:autosave', "$entity->type:$entity->subtype", $vars, true)) {
	return;
}

$last_saved = elgg_echo('never');
if ($entity->guid && $entity->time_updated) {
	$dt = new DateTime();
	$dt->setTimestamp($entity->time_updated);
	if (class_exists(\hypeJunction\Time::class)) {
		$dt->setTimezone(new DateTimeZone(\hypeJunction\Time::getClientTimezone()));
	}
	$last_saved = $dt->format(elgg_echo('friendlytime:date_format'));
}

echo elgg_format_element('div', [
	'class' => 'post-autosave elgg-subtext',
], elgg_echo('post:last_saved', [
		elgg_format_element('span', [
			'class' => 'post-last-saved',
		], $last_saved)
	])
);

elgg_require_js('post/elements/autosave');
