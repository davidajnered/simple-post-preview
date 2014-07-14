<?php
/**
 * Create settings page.
 */
namespace SimplePostPreview\Admin;

use SimplePostPreview\Helpers\SettingsHelper;

class SettingsPage
{
    /**
     * @var object
     */
    private $settingsHelper;

    /**
     * Construct.
     */
    public function __construct()
    {
        $options = array(
            'page_title' => 'Settings Admin',           // Page title
            'menu_title' => 'Simple Post Preview',      // Menu title
            'capability' => 'manage_options',           // Capability
            'menu_slug' => 'simple-post-preview',       // Menu slug
            'callback' => array($this, 'page'),         // Callback object
            'form' => array(
                array(                                  // First section
                    'name' => 'First Section',          // Section name
                    'fields' => array(                  // Fields connected to the section above
                        'post_types' => 'Post Types'    // Field
                    ),
                ),
            ),
        );

        $this->settingsHelper = new SettingsHelper($options);
    }

    /**
     * Options page callback.
     */
    public function page()
    {
        $output = '
            <div class="wrap">
                <h2>Simple Post Preview</h2>
                ' . $this->settingsHelper->renderForm() . '
            </div>
        ';

        echo $output;
    }

    /**
     * Get the settings option array and print one of it's values
     */
    public function postTypesFieldCallback()
    {
        $option = get_option('simple-post-preview_post_types');
        $postTypes = get_post_types();

        $output = '<ul>';
        foreach ($postTypes as $postType) {
            $label = ucwords(str_replace(array('-', '_'), ' ', $postType));
            $checked = is_array($option) && in_array($postType, $option) ? ' checked' : '';
            $output .= '
                <li>
                    <input type="checkbox" name="simple-post-preview_post_types[]" value="' . $postType . '"' . $checked . ' />
                    <label>' . $label . '</label>
                </li>
            ';
        }
        $output .= '</ul>';

        echo $output;
    }
}
