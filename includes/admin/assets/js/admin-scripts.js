(function ($) {
    'use strict'

	$(function () {
		try {
		    $(document.body).on('init_form_field_dependency', function () {
		        $('[data-rfw_dependency]').RFWFormFieldDependency()
		    }).trigger('init_form_field_dependency')
		}
		catch (err) {
		    window.console.log(err);
		}
    });
})(jQuery);