<?php

require_once __DIR__ . '/autoloader.php';

define('H_PUBLISHED', 'published');
define('H_DRAFT', 'draft');
define('H_ARCHIVED', 'archived');

return function () {
	elgg_register_event_handler('init', 'system', function () {

		elgg_register_plugin_hook_handler('get_sql', 'access', \hypeJunction\Draft\HideUnpublishedPosts::class);

		elgg_register_plugin_hook_handler('fields', 'object', \hypeJunction\Draft\AddFormField::class);

		elgg_register_event_handler('update', 'object', \hypeJunction\Draft\DeleteAutosaveRevision::class);

		elgg_register_plugin_hook_handler('modules', 'object', \hypeJunction\Draft\AddLayoutModules::class);

		elgg_register_plugin_hook_handler('register', 'menu:entity', \hypeJunction\Draft\EntityMenu::class);

		elgg_register_plugin_hook_handler('uses:comments', 'all', \hypeJunction\Draft\HideDraftComments::class);

		elgg_extend_view('post/elements/form_footer', 'post/elements/autosave');

		elgg_extend_view('object/elements/imprint/contents', 'post/imprint/status');
	});

};
