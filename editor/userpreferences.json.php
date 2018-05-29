<?php
// This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
//
// VPL for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// VPL for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with VPL for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Processes AJAX requests from IDE
 *
 * @package mod_vpl
 * @copyright 2017 Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */

define( 'AJAX_SCRIPT', true );

require(__DIR__ . '/../../../config.php');
$outcome = new stdClass();
$outcome->success = false;
$outcome->error = '';
$outcome->preferences = new stdClass();
try {
    require_login();
    $rawdata = file_get_contents( "php://input" );
    $actiondata = json_decode( $rawdata );
    if ( isset($actiondata->fontSize) ) {
        $fontsize = (int) $actiondata->fontSize;
        $fontsize = min(max(1, $actiondata->fontSize), 48);
        set_user_preference('vpl_editor_fontsize', $fontsize);
        $outcome->success = true;
    }
    if ( isset($actiondata->aceTheme) ) {
        $theme = substr($actiondata->aceTheme, 0, 50);
        set_user_preference('vpl_acetheme', $theme);
        $outcome->success = true;
    }
    if ( isset($actiondata->terminalTheme) ) {
        $theme = substr($actiondata->terminalTheme, 0, 10);
        set_user_preference('vpl_terminaltheme', $theme);
        $outcome->success = true;
    }
    if ( isset($actiondata->blocklyLang) ) {
        $theme = substr($actiondata->blocklyLang, 0, 10);
        set_user_preference('vpl_blocklylang', $theme);
        $outcome->success = true;
    }
    if (isset($actiondata->getPreferences)) {
        $outcome->preferences->fontSize = (int)  get_user_preferences('vpl_editor_fontsize', 12);
        $outcome->preferences->aceTheme = get_user_preferences('vpl_acetheme', '');
        $outcome->preferences->terminalTheme = (int)  get_user_preferences('vpl_terminaltheme', 0);
        $outcome->preferences->blocklyLang = get_user_preferences('vpl_blocklylang', '');
        $outcome->success = true;
    }
} catch ( Exception $e ) {
    $outcome->error = $e->getMessage();
}
echo json_encode( $outcome );
die();
