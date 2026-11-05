<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Add Label Collapsed form.
 *
 * @package    mod_labelcollapsed
 * @copyright  2011 Thomas Alsén
 * @copyright  2019 Lancaster University (http://www.lancaster.ac.uk/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Phil Devine <p.devine@lancaster.ac.uk>
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_labelcollapsed_mod_form extends moodleform_mod {

    public function definition(): void {
        global $CFG;

        $mform = $this->_form;

        // Register custom colourpicker.
        MoodleQuickForm::registerElementType(
            'labelcollapsed_colourpicker',
            $CFG->dirroot . '/mod/labelcollapsed/classes/form/colourpicker_form_element.php',
            'mod_labelcollapsed_colourpicker_form_element'
        );

        // Register validation rule for colourpicker.
        MoodleQuickForm::registerRule(
            'mod_labelcollapsed_colourpicker_rule',
            null,
            'mod_labelcollapsed_colourpicker_rule',
            $CFG->dirroot . '/mod/labelcollapsed/classes/form/colourpicker_form_element.php'
        );

        // Section tag colour (optional).
        $mform->addElement(
            'labelcollapsed_colourpicker',
            'sectioncolor',
            get_string('labelsectionnumcolor', 'labelcollapsed'),
            [
                'id' => 'colourpicker_sectioncolor',
            ]
        );

        $mform->setDefault('sectioncolor', '');

        $mform->addRule(
            'sectioncolor',
            get_string('invalidhexcolour', 'labelcollapsed'),
            'mod_labelcollapsed_colourpicker_rule'
        );

        // Section tag (optional).
        $mform->addElement(
            'text',
            'labelsection',
            get_string('labelsectionnum', 'labelcollapsed')
        );

        $mform->setType('labelsection', PARAM_TEXT);

        $mform->addRule(
            'labelsection',
            get_string('maximumchars', '', 12),
            'maxlength',
            12,
            'client'
        );

        // Section tag background colour (optional).
        $mform->addElement(
            'labelcollapsed_colourpicker',
            'sectionbgcolor',
            get_string('labelsectionBGcolor', 'labelcollapsed'),
            [
                'id' => 'colourpicker_sectionbgcolor',
            ]
        );

        $mform->setDefault('sectionbgcolor', '');

        $mform->addRule(
            'sectionbgcolor',
            get_string('invalidhexcolour', 'labelcollapsed'),
            'mod_labelcollapsed_colourpicker_rule'
        );

        // Heading (mandatory).
        $mform->addElement(
            'text',
            'name',
            get_string('labelcollapsedheader', 'labelcollapsed'),
            ['size' => '64']
        );

        $mform->setType('name', PARAM_TEXT);

        $mform->addRule(
            'name',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Description (mandatory).
        $this->standard_intro_elements();

        // Add a required rule only if Moodle has not already
        // applied one through requiremodintro.
        if (empty($CFG->requiremodintro)) {
            $mform->addRule(
                'introeditor',
                get_string('required'),
                'required',
                null,
                'client'
            );
        }

        // Standard Moodle activity settings.
        $this->standard_coursemodule_elements();

        // Save and cancel buttons.
        $this->add_action_buttons(true, false);
    }

    /**
     * Validate Heading and Description before saving.
     *
     * Description may contain text, images, videos, audio
     * or supported embedded HTML media.
     *
     * @param array $data Submitted form data.
     * @param array $files Submitted files.
     * @return array Validation errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Heading must contain text.
        $heading = trim($data['name'] ?? '');

        if ($heading === '') {
            $errors['name'] = get_string('required');
        }

        // Retrieve Description HTML.
        $content = $data['introeditor']['text'] ?? '';

        // Remove HTML tags and decode entities.
        $plaintext = trim(
            html_entity_decode(
                strip_tags($content),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            )
        );

        // Allow media-only descriptions, including embedded HTML.
        $hasmedia = preg_match(
            '/<(img|picture|svg|video|audio|iframe|object|embed|source|canvas)\b/i',
            $content
        );

        // Reject an empty Description.
        if ($plaintext === '' && !$hasmedia) {
            $errors['introeditor'] = get_string('required');
        }

        return $errors;
    }
}
