define(function (require) {

	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');

	var interval = 60000;

	function isValid($form) {
		var isValid = true;

		$form.one('submit', function(e) {
			// Let other plugins, e.g. CKEditor populate the fields
			// with actual values
			e.preventDefault();
			return false;
		}).trigger('submit');

		$('input,select,textarea', $form).each(function() {
			if (!this.validity.valid) {
				isValid = false;
			}

			if ($(this).is('[data-cke-init]')) {
				var data = $(this).data('editorOpts');
				if (data.required && !$(this).val()) {
					isValid = false;
				}
			}
		});

		return isValid;
	}

	function autosave() {
		$('.elgg-form-post-save:has(.post-autosave)').each(function () {
			var $form = $(this);

			if (!isValid($form)) {
				return;
			}

			var ajax = new Ajax(false);

			return ajax.action('post/autosave', {
				data: ajax.objectify($form),
				processData: false,
				contentType: false,
				beforeSend: function () {
					$form.find('[type="submit"]')
						.prop('disabled', true)
						.addClass('elgg-state-loading');
				}
			}).done(function (data) {
				$form.find('.post-last-saved').text(data.last_saved);

				$.each(['guid', 'type', 'subtype', '_hash'], function (i, e) {
					if (!data[e]) {
						return;
					}

					$form.find('[name="' + e + '"]').val(data[e]);
				});

				$form.find('[type="submit"]')
					.prop('disabled', false)
					.removeClass('elgg-state-loading');

			});
		});
	}

	setInterval(autosave, interval);
});

