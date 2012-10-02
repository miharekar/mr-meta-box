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
	
	if(window.Modernizr.inputtypes.range) {
		$('.mr-range').on('change', function() {
			var $this = $(this);
			$this.siblings('.mr-range-text').val($this.val());
		});
		$('.mr-range-text').on('change', function() {
			var $this = $(this);
			$this.siblings('.mr-range').val($this.val());
		});
	} else {
		$('.mr-range').each(function() {
			var $this = $(this),
				$slider = $this.siblings('.mr-range-slider'),
				$text = $this.siblings('.mr-range-text');
			$this.hide();
			$slider.css('display', 'inline-block').slider({value: parseFloat($this.attr('value')), step: parseFloat($this.attr('step')), min: parseFloat($this.attr('min')), max: parseFloat($this.attr('max')), slide: function(event, ui) {$text.val($slider.slider('value'))}});
		});
		$('.mr-range-text').on('change', function() {
			var $this = $(this);
			$this.siblings('.mr-range-slider').slider('value', parseFloat($this.val()));
		});
	}
}(jQuery, window));