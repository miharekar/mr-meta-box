<?php
require_once 'mr-post-types.php';
/**
* @package mr meta box
* @author Miha Rekar <info @ mr.si>
* @copyright Miha Rekar 2012
* @version 0.2
*/
class mrMetaBox {
	protected $_metaBox = array(
		'id' => null, //string Meta box ID - required
		'title' => 'Title', //string Title of the meta box
		'prefix' => '', //string Prefix of the field ids
		'postType' => array('post'), //array Array of post types you want to add meta box to
		'context' => 'normal', //string The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side')
		'priority' => 'default', // string The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
		'usage' => 'theme', //string 'theme', 'plugin' or 'http://example.com/path/to/mr-meta-box/folder'
		'showInColumns' => false //boolean Whether to show the mr meta box fields in 3 columns - comes handy where there is many fields in one mr meta box
	);
	protected $_fields = array();
	protected $_path;

	/**
	* mr meta box constructor
	*
	* @access public
	* @param mixed $metaBox
	* @return void
	*/
	public function __construct($metaBox) {
		if (!is_admin()) {
			return;
		}
		$this->_metaBox = array_merge($this->_metaBox, $metaBox);

		if ($this->_metaBox['usage'] === 'theme') {
			$this->_path = get_template_directory_uri() . '/mr-meta-box';
		} else if ($this->_metaBox['usage'] === 'plugin') {
				$this->_path = plugins_url('mr-meta-box', plugin_basename(dirname( __FILE__)));
			} else {
			$this->_path = $this->_metaBox['usage'];
		}

		add_action('admin_enqueue_scripts', array(&$this, 'loadScripts'));
		add_action('add_meta_boxes', array(&$this, 'addMetaBoxes'));
		add_action('save_post', array(&$this, 'saveMetaBoxes'));
	}

	/**
	* load all the scripts necessary for all types of fields to work
	*
	* @access public
	* @return void
	*/
	public function loadScripts() {
		//scripts included with WordPress
		wp_enqueue_script('farbtastic');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('media-upload');
		//scripts from mr-meta-box/js/
		wp_enqueue_script('mr-timepicker', $this->_path.'/js/timepicker.js', array('jquery', 'jquery-ui-datepicker'));
		wp_enqueue_script('mr-modernizr', $this->_path.'/js/modernizr.js');
		wp_enqueue_script('mr-meta-box', $this->_path.'/js/mr-meta-box.min.js', array('jquery', 'farbtastic', 'mr-modernizr', 'mr-timepicker'), '0.2', true);
		//styles
		wp_enqueue_style('farbtastic');
		wp_enqueue_style('mr-jquery-ui', $this->_path.'/css/jqueryui.css');
		wp_enqueue_style('mr-meta-box', $this->_path.'/css/mr-meta-box.css');
		//bundles
		add_thickbox();
	}

	/**
	* adds meta box to post types
	*
	* @access public
	* @return void
	*/
	public function addMetaBoxes() {
		foreach ($this->_metaBox['postType'] as $postType) {
			add_meta_box($this->_metaBox['id'], $this->_metaBox['title'], array(&$this, 'displayMetaBox'), $postType, $this->_metaBox['context'], $this->_metaBox['priority']);
		}
	}

	/**
	* displays meta boxes
	*
	* @access public
	* @return void
	*/
	public function displayMetaBox() {
		global $post, $post_type;
		$fieldCount = 1;
		$columnCount = 1;
		$breakingPoint = round(count($this->_fields) / 3);
		echo sprintf('<input type="hidden" name="mr_meta_box_nonce" value="%s">', wp_create_nonce($post_type));
		echo '<div class="mr-meta-box">';
		if ($this->_metaBox['showInColumns']) echo '<div class="mr-meta-box-panel">';
		foreach ($this->_fields as $field) {
			$field['value'] = get_post_meta($post->ID, $field['id'], true);
			call_user_func(array(&$this, 'displayField'.$field['type']), $field);
			if ($this->_metaBox['showInColumns'] && $columnCount < 3 && $fieldCount == $breakingPoint) {
				echo '</div><div class="mr-meta-box-panel">';
				$fieldCount = 0;
				$columnCount++;
			}
			$fieldCount++;
		}
		if ($this->_metaBox['showInColumns']) echo '</div>';
		echo '</div>';
	}

	/**
	* saves meta boxes
	*
	* @access public
	* @param string $post_ID
	* @return void
	*/
	public function saveMetaBoxes($post_ID) {
		global $post_type;
		$post_type_object = get_post_type_object($post_type);

		//autosave, revision, post type, nonce and permission checks
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (!isset($_POST['post_ID']) || $post_ID != $_POST['post_ID']) || (!in_array($post_type, $this->_metaBox['postType'])) || (!wp_verify_nonce($_POST['mr_meta_box_nonce'], $post_type)) || (!current_user_can($post_type_object->cap->edit_post, $post_ID))) {
			return $post_ID;
		}

		foreach ($this->_fields as $field) {
			update_post_meta($post_ID, $field['id'], $_POST[$field['id']]);
			if ($field['type'] === 'Location') {
				update_post_meta($post_ID, $field['id'].'_lat', $_POST[$field['id'].'_lat']);
				update_post_meta($post_ID, $field['id'].'_lng', $_POST[$field['id'].'_lng']);
			}
		}
	}

	/**
	* adds field. Options are explained in the demo.php
	*
	* @access public
	* @param mixed $args
	* @return void
	*/
	public function addField($args) {
		$newField = array('type' => '', 'id' => '', 'value' => '', 'label' => '');
		$newField = array_merge($newField, $args);
		$newField['id'] = $this->_metaBox['prefix'].$newField['id'];
		$this->_fields[$newField['id']] = $newField;
	}

	/**
	* add fields simply by providing array with field types as keys and labels as values
	*
	* @access public
	* @param mixed $fields
	* @return void
	*/
	public function addFieldsSimple($fields) {
		foreach ($fields as $type => $label) {
			$this->addField(array('type' => $type, 'id' => $this->makeLabelIDFriendly($label), 'label' => $label));
		}
	}

	/**
	* returns "id friendly" label
	*
	* @access private
	* @param string $label
	* @return string
	*/
	private function makeLabelIDFriendly($label) {
		return trim(str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $label)))));
	}

	/**
	 * returns the ID of the gallery for the field. It creates a new one if it doesn't yet exist.
	 *
	 * @param array $field
	 */
	private function getPostIDForGallery($field) {
		global $post;
		$title = $post->ID . $this->_metaBox['id'] . $field['id'];
		$gallery = get_page_by_title($title, 'OBJECT', 'mr_meta_box_gallery');
		if (empty($gallery)) {
			$gallery = array(
				'post_title' => $title,
				'post_type' => 'mr_meta_box_gallery',
				'post_status' => 'publish'
			);
			$id = wp_insert_post($gallery, $wp_error);
		} else {
			$id = $gallery->ID;
		}
		return $id;
	}

	public function displayFieldText($field) {
		echo sprintf('<div class="mr-meta-box-field"><label for="%1$s">%2$s</label><input type="text" name="%1$s" id="%1$s" value="%3$s" placeholder="%4$s" size="29"></div>', $field['id'], $field['label'], $field['value'], $field['default']);
	}

	public function displayFieldTextarea($field) {
		echo sprintf('<div class="mr-meta-box-field"><label for="%1$s">%2$s</label><textarea name="%1$s" id="%1$s" cols="30" rows="5">%3$s</textarea></div>', $field['id'], $field['label'], $field['value']);
	}

	public function displayFieldWYSIWYG($field) {
		$field['showHTML'] = ($field['showHTML'] === true) ? true : false;

		echo sprintf('<div class="mr-meta-box-field"><label for="%s">%s</label>', $field['id'], $field['label']);
		wp_editor($field['value'], $field['id'], array('media_buttons' => false, 'quicktags' => $field['showHTML']));
		echo '</div>';
	}

	public function displayFieldCheckbox($field) {
		$checked = (empty($field['value'])) ? '' : ' checked="checked"';
		echo sprintf('<div class="mr-meta-box-field"><label class="no-block" for="%1$s">%2$s</label><input type="checkbox" name="%1$s" id="%1$s" value="1"%3$s></div>', $field['id'], $field['label'], $checked);
	}

	public function displayFieldSelect($field) {
		if ($field['multiple'] === true) {
			$format = '<div class="mr-meta-box-field"><label for="%1$s">%2$s</label><select name="%1$s[]" id="%1$s" multiple="multiple">%3$s</select></div>';
		} else {
			$format = '<div class="mr-meta-box-field"><label class="no-block" for="%1$s">%2$s</label><select name="%1$s" id="%1$s">%3$s</select></div>';
		}

		if ($field['value'] !== '' && !is_array($field['value'])) {
			$field['value'] = array($field['value']);
		}

		$options = '';
		if (!empty($field['default'])) {
			$options = sprintf('<option value="">%s</option>', $field['default']);
		}
		foreach ($field['options'] as $optionKey => $optionValue) {
			$selected = (is_array($field['value']) && in_array($optionKey, $field['value'])) ? ' selected="selected"' : '';
			$options .= sprintf('<option value="%s"%s>%s</option>', $optionKey, $selected, $optionValue);
		}

		echo sprintf($format, $field['id'], $field['label'], $options);
	}

	public function displayFieldRadioGroup($field) {
		$options = '';
		foreach ($field['options'] as $optionKey => $optionValue) {
			$checked = ($optionKey == $field['value']) ? ' checked="checked"' : ''; // '==' intentional since keys can be integers but WP always stores as strings
			$options .= sprintf('<li class="mr-radio"><input type="radio" name="%1$s" id="%1$s-%2$s" value="%2$s"%4$s> <label class="no-block" for="%1$s-%2$s">%3$s</label></li>', $field['id'], $optionKey, $optionValue, $checked);
		}

		echo sprintf('<div class="mr-meta-box-field"><label>%s</label><ul>%s<ul></div>', $field['label'], $options);
	}

	public function displayFieldCheckboxGroup($field) {
		if ($field['value'] !== '' && !is_array($field['value'])) {
			$field['value'] = array($field['value']);
		}

		$options = '';
		foreach ($field['options'] as $optionKey => $optionValue) {
			$checked = (is_array($field['value']) && in_array($optionKey, $field['value'])) ? ' checked="checked"' : '';
			$options .= sprintf('<li class="mr-checkbox"><input type="checkbox" name="%1$s[]" id="%1$s-%2$s" value="%2$s"%4$s> <label class="no-block" for="%1$s-%2$s">%3$s</li></span>', $field['id'], $optionKey, $optionValue, $checked);
		}

		echo sprintf('<div class="mr-meta-box-field"><label>%s</label><ul>%s</ul></div>', $field['label'], $options);
	}

	public function displayFieldColor($field) {
		echo sprintf('<div class="mr-meta-box-field"><label class="no-block" for="%1$s">%2$s</label><input type="color" name="%1$s" id="%1$s" class="mr-color" value="%3$s" size="10"><div class="color-picker"></div></div>', $field['id'], $field['label'], $field['value']);
	}

	public function displayFieldDate($field) {
		$field['dateFormat'] = empty($field['dateFormat']) ? 'mm/dd/yy' : $field['dateFormat'];
		$field['minDate'] = empty($field['minDate']) ? '' : $field['minDate'];
		$field['maxDate'] = empty($field['maxDate']) ? '' : $field['maxDate'];

		echo sprintf('<div class="mr-meta-box-field"><label class="no-block" for="%1$s">%2$s</label><input type="text" name="%1$s" id="%1$s" class="mr-date" value="%3$s" size="10" data-dateformat="%4$s" data-mindate="%5$s" data-maxdate="%6$s"></div>', $field['id'], $field['label'], $field['value'], $field['dateFormat'], $field['minDate'], $field['maxDate']);
	}

	public function displayFieldTime($field) {
		$field['timeFormat'] = empty($field['timeFormat']) ? 'hh:mm tt' : $field['timeFormat'];
		$field['ampm'] = empty($field['ampm']) ? false : $field['ampm'];
		$field['show'] = empty($field['show']) ? array('Hour', 'Minute') : $field['show'];

		$inputs = array('Hour', 'Minute', 'Second', 'Millisec', 'Timezone');
		$show = '';
		foreach ($inputs as $input) {
			$show .= sprintf('data-show%s="%b"', $input, (in_array($input, $field['show'])));
		}

		echo sprintf('<div class="mr-meta-box-field"><label class="no-block" for="%1$s">%2$s</label><input type="text" name="%1$s" id="%1$s" class="mr-time" value="%3$s" size="10" data-timeformat="%4$s" data-ampm="%5$s" %6$s></div>', $field['id'], $field['label'], $field['value'], $field['timeFormat'], $field['ampm'], $show);
	}

	public function displayFieldRange($field) {
		$field['min'] = empty($field['min']) ? '0' : $field['min'];
		$field['max'] = empty($field['max']) ? '100' : $field['max'];
		$field['step'] = empty($field['step']) ? '1' : $field['step'];

		echo sprintf('<div class="mr-meta-box-field"><label for="%1$s">%2$s</label><input type="range" name="range_%1$s" id="range_%1$s" class="mr-range" value="%3$s" size="29" min="%4$s" max="%5$s" step="%6$s"><div class="mr-range-slider"></div><input type="text" name="%1$s" id="%1$s" class="mr-range-text" value="%3$s" size="3"></div>', $field['id'], $field['label'], $field['value'], $field['min'], $field['max'], $field['step']);
	}

	public function displayFieldImage($field) {
		global $post;
		$postID = $field['attachToPost'] ? $post->ID : 0;

		if (!empty($field['value'])) {
			$image = wp_get_attachment_image_src($field['value'], 'medium');
			$hide = '';
		} else {
			$image = array('');
			$hide = ' style="display: none;"';
		}
		$image = sprintf('<a href="#"><img class="mr-image" src="%s"%s></a>', $image[0], $hide);

		echo sprintf('<div class="mr-meta-box-field"><label for="%1$s">%2$s</label><input type="hidden" name="%1$s" id="%1$s" class="mr-image-hidden" value="%3$s">%5$s<a href="#" class="button mr-image-button" data-post="%4$s">Upload %2$s</a> <a href="#" class="mr-image-delete"%6$s>Remove %2$s</a></div>', $field['id'], $field['label'], $field['value'], $postID, $image, $hide);
	}

	public function displayFieldGallery($field) {
		if(empty($field['value'])) {
			$field['value'] = $this->getPostIDForGallery($field);
		}
		echo sprintf('<div class="mr-meta-box-field"><label for="%1$s">%2$s</label><input type="hidden" name="%1$s" id="%1$s" class="mr-image-hidden" value="%3$s"><a href="#" class="button mr-image-button" data-post="%3$s">Upload images to %2$s</a></div>', $field['id'], $field['label'], $field['value']);
	}

	//http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=hra%C5%A1e%2018a,%20smlednik,%20slovenia
	//https://developers.google.com/maps/documentation/geocoding/#GeocodingRequests
	public function displayFieldLocation($field) {
		global $post;
		$lat = get_post_meta($post->ID, $field['id'] . '_lat', true);
		$lng = get_post_meta($post->ID, $field['id'] . '_lng', true);
		echo sprintf('<div class="mr-meta-box-field"><label for="%1$s">%2$s</label><input type="text" name="%1$s" id="%1$s" class="mr-location" value="%3$s" placeholder="%4$s" size="15"><input type="text" name="%1$s_lat" id="%1$s_lat" value="%5$s" placeholder="Lat" size="4"><input type="text" name="%1$s_lng" id="%1$s_lng" value="%6$s" placeholder="Lng" size="4"></div>', $field['id'], $field['label'], $field['value'], $field['default'], $lat, $lng);
	}
}