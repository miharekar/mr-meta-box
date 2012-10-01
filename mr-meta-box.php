<?php

class mrMetaBox {
	protected $_metaBox = array(
		'id' => null,
		'title' => 'Title',
		'prefix' => '',
		'postType' => array('post'),
		'context' => 'normal',
		'priority' => 'default'
	);
	
	protected $_fields = array();
	
	public function __construct($metaBox) {
		if (!is_admin()) {
			return;
		}
		
		$this->_metaBox = array_merge($this->_metaBox, $metaBox);
		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
		add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
		add_action('save_post', array(&$this, 'save_post'));
	}
	
	public function admin_enqueue_scripts() {
		wp_enqueue_style('mr-meta-box', get_template_directory_uri().'/mr-meta-box/css/mr-meta-box.css');
	}
	
	public function add_meta_boxes() {
		foreach ($this->_metaBox['postType'] as $postType) {
			add_meta_box($this->_metaBox['id'], $this->_metaBox['title'], array(&$this, 'displayMetaBox'), $postType, $this->_metaBox['context'], $this->_metaBox['priority']);
		}
	}
	
	public function displayMetaBox() {
		global $post, $post_type;
		echo sprintf('<input type="hidden" name="mr_meta_box_nonce" value="%s">', wp_create_nonce($post_type));
		foreach ($this->_fields as $field) {
			$field['value'] = get_post_meta($post->ID, $field['id'], true);
			call_user_func(array(&$this, 'displayField'.$field['type']), $field);
		}
	}
	
	public function save_post($post_ID) {
		global $post_type;
		$post_type_object = get_post_type_object($post_type);
		
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)						// Check autosave
		|| (!isset($_POST['post_ID']) || $post_ID != $_POST['post_ID'])		// Check revision
		|| (!in_array($post_type, $this->_metaBox['postType']))					// Check current post type
		|| (!wp_verify_nonce($_POST['mr_meta_box_nonce'], $post_type))			// Check nonce
		|| (!current_user_can($post_type_object->cap->edit_post, $post_ID))) {	// Check permissions
			return $post_ID;
		}
		
		foreach ($this->_fields as $field) {
			update_post_meta($post_ID, $field['id'], $_POST[$field['id']]);
		}
	}
	
	public function addField($args) {
		$newField = array('type' => 'Text', 'id' => '', 'value' => '', 'label' => 'Text Field ');
		$newField = array_merge($newField, $args);
		$newField['id'] = $this->_metaBox['prefix'].$newField['id'];
		$this->_fields[] = $newField;
	}
	
	public function displayFieldText($field) {
		echo sprintf('<div class="mr-meta-box"><label for="%1$s">%2$s</label><input type="text" name="%1$s" id="%1$s" value="%3$s" placeholder="%4$s" size="29"></div>', $field['id'], $field['label'], $field['value'], $field['default']);
	}
	
	public function displayFieldTextarea($field) {
		echo sprintf('<div class="mr-meta-box"><label for="%1$s">%2$s</label><textarea name="%1$s" id="%1$s" cols="30" rows="5" placeholder="%4$s">%3$s</textarea></div>', $field['id'], $field['label'], $field['value'], $field['default']);
	}
	
	public function displayFieldCheckbox($field) {
		$checked = (empty($field['value'])) ? '' : ' checked="checked"';
		echo sprintf('<div class="mr-meta-box"><label class="no-block" for="%1$s">%2$s</label><input type="checkbox" name="%1$s" id="%1$s" value="1"%3$s></div>', $field['id'], $field['label'], $checked);
	}
}

?>