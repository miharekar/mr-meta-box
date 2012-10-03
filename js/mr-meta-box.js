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
			$slider.css('display', 'inline-block').slider({value: parseFloat($this.attr('value')), step: parseFloat($this.attr('step')), min: parseFloat($this.attr('min')), max: parseFloat($this.attr('max')), slide: function() {$text.val($slider.slider('value'));}});
		});
		$('.mr-range-text').on('change', function() {
			var $this = $(this);
			$this.siblings('.mr-range-slider').slider('value', parseFloat($this.val()));
		});
	}
	
	$('.mr-date').each(function() {
		var $this = $(this);
		$this.datepicker({dateFormat: $this.data('dateformat'), minDate: $(this).data('mindate'), maxDate: $(this).data('maxdate')});
	});
	
	$('.mr-time').each(function() {
		var $this = $(this);
		$this.timepicker({timeOnlyTitle: $this.siblings('label').text(), timeFormat: $this.data('timeformat'), ampm: $this.data('ampm'), showHour: $this.data('showhour'), showMinute: $this.data('showminute'), showSecond: $this.data('showsecond'), showMillisec: $this.data('showmillisec'), showTimezone: $this.data('showtimezone')});
	});
	
	$('.mr-image').click(function (event) {
		event.preventDefault();
		$(this).parent().siblings('.mr-image-button').click();
	});
	
	$('.mr-image-button').click(function(event) {
		event.preventDefault();
		var $this = $(this);
		window.tb_show($this.val(), 'media-upload.php?type=image&TB_iframe=true&post_id='+$this.data('post'));
		
		window.send_to_editor = function(html) {
			var imageid = $('img',html).attr('class').match(/wp\-image\-([0-9]+)/);
			$this.parent().find('.mr-image').attr('src', $('img', html).attr('src')).show('blind');
			$this.siblings('.mr-image-hidden').val(imageid[1]);
			$this.siblings('.mr-image-delete').show();
			window.tb_remove();
		};
	});
	
	$('.mr-image-delete').click(function(event) {
		event.preventDefault();
		var $this = $(this);
		$this.siblings('.mr-image-hidden').val('');
		$this.parent().find('.mr-image').attr('src', '').hide('blind');
		$this.hide();
	});
}(jQuery, window));