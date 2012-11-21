/*
Description:	mr meta box
Version:		0.2
Author:			Miha Rekar
Author URI:		http://mr.si/
*/

(function ($, window) {
	function resizeColumns() {
		if ($('.mr-meta-box-panel').length) {
			clearTimeout(resizeColumnsTimer);
			$('.mr-meta-box-panel').css('height', 'auto');
			var maxHeight = Math.max.apply(null, $('.mr-meta-box-panel').map(function (){return $(this).height();}).get());
			$('.mr-meta-box-panel').each(function() {
				var $this = $(this);
				$this.height(maxHeight);
				$this.width(($this.parent().width() - 4)/3);
			});
			resizeColumnsTimer = setTimeout(resizeColumns, 1000);
		}
	}
	
	$('.mr-meta-box').parent().css('margin', 0).css('padding', 0);
	var resizeColumnsTimer = '';
	resizeColumns();
	
	if (!window.Modernizr.inputtypes.color) {
		$('.color-picker').each(function() {
			var $this = $(this);
			$.farbtastic($this).linkTo($this.siblings('.mr-color'));
		});
		$('.mr-color').on('focus', function() {
			$(this).siblings('.color-picker').show('blind', resizeColumns);
		}).on('focusout', function() {
			$(this).siblings('.color-picker').hide('blind', resizeColumns);
		});
	}
	
	if (window.Modernizr.inputtypes.range) {
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
	
	$('.mr-location').each(function() {
		var $this = $(this),
			$lat = $this.siblings('#'+$this.attr('id')+'_lat'),
			$lng = $this.siblings('#'+$this.attr('id')+'_lng'),
			defLocation = new window.google.maps.LatLng($lat.val(), $lng.val());
		$this.geocomplete({map: '#'+$this.attr('id')+'_map', location: defLocation, markerOptions: {draggable: true}});
		$this.on('geocode:result', function(event, result){
			$lat.val(result.geometry.location.lat());
			$lng.val(result.geometry.location.lng());
		});
		$this.on('geocode:dragged', function(event, result){
			$lat.val(result.lat());
			$lng.val(result.lng());
		});
	});
	
	$('.mr-image').click(function (event) {
		event.preventDefault();
		$(this).parent().siblings('.mr-image-button').click();
	});
	
	$('.mr-image-button').click(function(event) {
		event.preventDefault();
		var $this = $(this);
		window.tb_show($this.val(), 'media-upload.php?post_id='+$this.data('post')+'&type=image&TB_iframe=true');
		
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
		$this.parent().find('.mr-image').attr('src', '').hide('blind', resizeColumns);
		$this.hide();
	});
}(jQuery, window));