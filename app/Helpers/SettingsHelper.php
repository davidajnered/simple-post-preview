<?php
/**
 * Helps create and add settings to admin pages.
 */
namespace SimplePostPreview\Helpers;

use SimplePostPreview\Helpers\Utils;

class SettingsHelper
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var class
     */
    private $caller;

    /**
     * @var string
     */
    private $page;

    /**
     * @var array
     */
    private $sections;

    /**
     * @var string
     */
    private $currentSection;

    /**
     * @var fields
     */
    private $fields;

    /**
     * @var string
     */
    private $optionGroup;

    /**
     * Constructor.
     *
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param object $caller
     */
    public function __construct(array $options)
    {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        require_once(ABSPATH . 'wp-admin/includes/template.php');
        require_once(ABSPATH . 'wp-includes/pluggable.php');

        $this->options = $options;

        // If callback is in object, save object reference
        if (is_object($options['callback'][0])) {
            $this->caller = $options['callback'][0];
        }

        $this->page = $options['menu_slug'];
        $this->optionGroup = Utils::normalizeString($options['menu_title'] . '_field_group');

        add_action('admin_menu', function () use ($options) {
            add_options_page(
                $options['page_title'],
                $options['menu_title'],
                $options['capability'],
                $options['menu_slug'],
                $options['callback']
            );
        });

        $this->generateFormElements($options);
    }

    /**
     * Loop provided options and generate form elements
     */
    public function generateFormElements(array $options)
    {
        foreach ($options['form'] as $section) {
            $this->addSection($section['name']);
            foreach ($section['fields'] as $id => $name) {
                $this->addSetting($id, $name);
                $this->addField($id, $name);
            }
        }
    }

    /**
     * Add section.
     *
     * @param string $name
     */
    public function addSection($name)
    {
        // Generate id
        $id = Utils::normalizeString($name);

        // Check if section is already created
        if (isset($this->sections[$id])) {
            error_log('Section ' . $id . ' has already been created');
            return;
        }

        // Add section to the array of sections
        $this->sections[$id] = $id;

        // Make this section the current section
        // Fields added after call to this method will automatically be assigned to this section
        $this->currentSection = $id;

        // Check callback and call wordpress function
        $callback = Utils::toCamelCase($id) . 'SectionCallback';
        if (method_exists($this->caller, $callback)) {
            add_settings_section($id, $name, array($this->caller, $callback), $this->page);
        } else {
            add_settings_section($id, $name, '', $this->page);
        }
    }

    /**
     * Add setting.
     *
     * @param string $name
     */
    public function addSetting($id, $name)
    {
        // Prefix id
        $id = $this->options['menu_slug'] . '_' . $id;

        // Check callback and call wordpress function
        $sanitizeCallback = Utils::toCamelCase($name) . 'Sanitize';
        if (method_exists($this->caller, $sanitizeCallback)) {
            register_setting($this->optionGroup, $id, array($this->caller, $sanitizeCallback));
        } else {
            register_setting($this->optionGroup, $id);
        }
    }

    /**
     * Add field.
     *
     * @param string $name
     */
    public function addField($id, $name)
    {
        // Check callback and call wordpress function (before prefix)
        $callback = Utils::toCamelCase($id) . 'FieldCallback';

        // Prefix id
        $id = $this->options['menu_slug'] . '_' . $id;

        // Check if section is already created
        if (isset($this->fields[$id])) {
            error_log('Section ' . $id . ' has already been created');
            return;
        }

        // Add fields to the array of fields
        $this->fields[$id] = $id;

        if (method_exists($this->caller, $callback)) {
            add_settings_field($id, $name, array($this->caller, $callback), $this->page, $this->currentSection);
        } else {
            error_log('callback ' . $callback . ' is missing.');
        }

    }

    /**
     * Render form.
     *
     * @return string
     */
    public function renderForm()
    {
        $form = '<form method="post" action="options.php">';

        ob_start();
        settings_fields($this->optionGroup);
        do_settings_sections($this->page);
        submit_button();
        $form .= ob_get_contents();
        ob_clean();

        $form .= '</form>';

        return $form;
    }
}
