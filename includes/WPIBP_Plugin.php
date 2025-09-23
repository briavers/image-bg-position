<?php

class WPIBP_Plugin
{

    public $version = '1.1.0';

    protected $plugin_file_name;

    /**
     * Constructor
     */
    public function __construct($plugin_file_name)
    {
        define('WPIBP_VERSION', $this->version);

        $this->plugin_file_name = $plugin_file_name;

        $this->actions();
    }

    /**
     * Create a new instance
     */
    static function create($plugin_file_name, $settings = null)
    {
        return new self($plugin_file_name);
    }

    /**
     * Register actions
     */
    public function actions()
    {
        add_action('edit_attachment', [$this, 'custom_media_save_attachment']);
        add_filter('attachment_fields_to_edit', [$this, 'custom_media_add_media_custom_field'], null, 2);
        add_filter('wp_get_attachment_image_attributes', [$this, 'filter_gallery_img_atts'], 10, 2);

        wp_enqueue_style('image-bg-css', plugin_dir_url($this->plugin_file_name ) . 'src/bg-image-admin.css');
        wp_enqueue_script('image-bg-js', plugin_dir_url($this->plugin_file_name) . 'src/bg-image-script.js', ['jquery']);
    }

    //custom media field
    function custom_media_add_media_custom_field($form_fields, $post)
    {
        $field_value = get_post_meta($post->ID, 'bg_pos_desktop', true);
        $field_value_2 = get_post_meta($post->ID, 'bg_pos_mobile_id', true);
        $disabled = ($field_value && $field_value != '50% 50%') ? '' : 'style="display:none"';
        $label = ($field_value && $field_value != '50% 50%') ? 'Change' : 'Set';
        $desktop_text = ($field_value && $field_value != '50% 50%') ? '<b>Desktop</b>: ' . $field_value : '<b>Desktop</b>: Centered (default)';
        $field_value = ($field_value) ? $field_value : '50% 50%';
        $field_value_2 = ($field_value_2) ? $field_value_2 : '50% 50%';

        $html = "
			<input type='hidden' value='" . $field_value . "'	id='bg_pos_desktop_id' name='attachments[$post->ID][bg_pos_desktop]'>
			<input type='hidden' value='" . $field_value_2 . "'	id='bg_pos_mobile_id' name='attachments[$post->ID][bg_pos_mobile]'>
			<div class='overlay image_focus_point'>
				<div class='img-container'>
					<div class='header'>
						<div class='wrapp'>
							<h3>Click on the image to set the focus point</h3>
							<div class='controls'>
								<span class='button button-secondary' onclick='cancel_focus()'>Cancel</span>
								<span class='button button-primary' onclick='close_overlay()'>Save</span>
							</div>
						</div>
					</div>
					<div class='container'>
						<div class='pin'></div>
						<img src='" . wp_get_attachment_url($post->ID) . "'>
					</div>
				</div>
			</div>
			<div class='focusp_label_holder'>
				<div id='desktop_value'>" . $desktop_text . "</div>
				<input type='button' class='button button-small' value='" . $label . "' id='label_desktop' onclick='set_focus(0)'>
				<span class='close button button-small' id='reset_desktop' " . $disabled . " onclick='reset_focus()'>Reset</span>
			</div>
		";

        $form_fields['background_postion_desktop'] = array(
            'value' => $field_value ? $field_value : '',
            'label' => __('Focus Point'),
            'helps' => __(''),
            'input' => 'html',
            'html' => $html
        );

        return $form_fields;
    }

    //save custom media field
    function custom_media_save_attachment($attachment_id)
    {
        if (isset($_REQUEST['attachments'][$attachment_id]['bg_pos_desktop'])) {
            $bg_pos_desktop = $_REQUEST['attachments'][$attachment_id]['bg_pos_desktop'];
            update_post_meta($attachment_id, 'bg_pos_desktop', $bg_pos_desktop);
        }
    }

    //apply filter in frontend to object-position
    function filter_gallery_img_atts($atts, $attachment)
    {
        $bg_pos_desktop = get_post_meta($attachment->ID, 'bg_pos_desktop', true);

        if ($bg_pos_desktop != "") {
            $atts['style'] = "object-position:" . $bg_pos_desktop;
        }
        return $atts;
    }
}

