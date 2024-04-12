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
 * @created    23/05/17 17:59
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if (!$PAGE->requires->is_head_done()) {
    $PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
}

$settings = new admin_settingpage('kopere_dashboard', get_string('pluginname', 'local_kopere_dashboard'));
$ADMIN->add('localplugins', $settings);

if ($hassiteconfig) {
    if (!$ADMIN->locate('integracaoroot')) {
        $ADMIN->add('root', new admin_category('integracaoroot', get_string('integracaoroot', 'local_kopere_dashboard')));
    }

    $ADMIN->add('integracaoroot',
        new admin_externalpage(
            'local_kopere_dashboard',
            get_string('modulename', 'local_kopere_dashboard'),
            "{$CFG->wwwroot}/local/kopere_dashboard/open.php?classname=dashboard&method=start"
        )
    );
}

if ($ADMIN->fulltree) {

    if (method_exists($settings, "add")) {

        $setting = new admin_setting_configcheckbox('kopere_dashboard_menu',
            get_string('kopere_dashboard_menu', 'local_kopere_dashboard'),
            get_string('kopere_dashboard_menu_desc', 'local_kopere_dashboard'),
            1
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        $openitens = [
            'internal' => get_string('kopere_dashboard_open_internal', 'local_kopere_dashboard'),
            'popup' => get_string('kopere_dashboard_open_popup', 'local_kopere_dashboard'),
            '_top' => get_string('kopere_dashboard_open_top', 'local_kopere_dashboard'),
            '_blank' => get_string('kopere_dashboard_open_blank', 'local_kopere_dashboard'),
        ];
        $settings->add(
            new admin_setting_configselect('kopere_dashboard_open',
                get_string('kopere_dashboard_open', 'local_kopere_dashboard'),
                get_string('kopere_dashboard_open_desc', 'local_kopere_dashboard'),
                'internal',
                $openitens
            ));

        $settings->add(
            new admin_setting_configcheckbox('kopere_dashboard_monitor',
                get_string('kopere_dashboard_monitor', 'local_kopere_dashboard'),
                get_string('kopere_dashboard_monitor_desc', 'local_kopere_dashboard'),
                0
            ));

        $plugins = glob(__DIR__ . "/../*/settings_kopere.php");
        foreach ($plugins as $plugin) {
            require $plugin;
        }
    }
}
