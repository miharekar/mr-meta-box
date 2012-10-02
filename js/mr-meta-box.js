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
	
	$('.mr-date').each(function() {
		var $this = $(this);
		$this.datepicker({dateFormat: $this.data('dateformat'), minDate: $(this).data('mindate'), maxDate: $(this).data('maxdate')});
	});
}(jQuery, window));