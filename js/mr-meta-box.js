(function ($, window) {
	if(!window.Modernizr.inputtypes.color) {
		$('.color-picker').each(function() {
			var $this = $(this);
			$.farbtastic($this).linkTo($this.siblings('.mr-color'));
		});
		$('.mr-color').on('focus', function() {
			$(this).siblings('.color-picker').show('blind');
		}).on('focusout', function() {
			$(this).siblings('.color-picker').hide('blind');
		});
	}
}(jQuery, window));