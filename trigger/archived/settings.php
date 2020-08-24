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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings page which gives an overview over running lifecycle processes.
 *
 * @package lifecycletrigger_archived
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $key = 'lifecycletrigger_archived/archivesourcewwwroot';
    $label = get_string('configarchivesourcewwwroot', 'lifecycletrigger_archived');
    $desc = get_string('configarchivesourcewwwroot_desc', 'lifecycletrigger_archived');
    $default = '';
    $settings->add(new admin_setting_configtext($key, $label, $desc, $default));

    $key = 'lifecycletrigger_archived/archivesourcetoken';
    $label = get_string('configarchivesourcetoken', 'lifecycletrigger_archived');
    $desc = get_string('configarchivesourcetoken_desc', 'lifecycletrigger_archived');
    $default = '';
    $settings->add(new admin_setting_configtext($key, $label, $desc, $default));

    $key = 'lifecycletrigger_archived/maxcoursepullspercron';
    $label = get_string('configmaxcoursepullspercron', 'lifecycletrigger_archived');
    $desc = get_string('configmaxcoursepullspercron_desc', 'lifecycletrigger_archived');
    $default = '10';
    $settings->add(new admin_setting_configtext($key, $label, $desc, $default));
}