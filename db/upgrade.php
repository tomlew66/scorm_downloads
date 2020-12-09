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
 * Upgrade the local_scorm_downloads database.
 *
 * @package    local_scorm_downloads
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();

/** *
 * @param int $oldversion The version number of the plugin that was installed.
 * @return boolean
 */
function xmldb_local_scorm_downloads_upgrade($oldversion) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2020082425) {

        // Define table local_hedataform_submitted to be created.
        $table = new xmldb_table('local_scorm_downloads_metadata');

        // Adding fields to table local_hedataform_submitted.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('filename', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('summary', XMLDB_TYPE_CHAR, '250', null, XMLDB_NOTNULL, null, null);
        $table->add_field('learningoutcomes', XMLDB_TYPE_CHAR, '250', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_hedataform_submitted.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_hedataform_submitted.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Hedataform savepoint reached.
        upgrade_plugin_savepoint(true, 2020082425, 'local', 'scorm_downloads');
    }


    return true;
}
