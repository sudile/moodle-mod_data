<?php  //$Id$

// This file keeps track of upgrades to
// the data module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_data_upgrade($oldversion=0) {

    global $CFG, $THEME, $DB;

    $dbman = $DB->get_manager();

    $result = true;

//===== 1.9.0 upgrade line ======//

    if ($result && $oldversion < 2007101512) {
    /// Launch add field asearchtemplate again if does not exists yet - reported on several sites

        $table = new XMLDBTable('data');
        $field = new XMLDBField('asearchtemplate');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, null, null, 'jstemplate');

        if (!$dbman->field_exists($table, $field)) {
            $result = $result && $dbman->add_field($table, $field);
        }
    }

    if ($result && $oldversion <  2007101513) {
        // Upgrade all the data->notification currently being
        // NULL to 0
        $sql = "UPDATE {$CFG->prefix}data SET notification=0 WHERE notification IS NULL";
        $result = $DB->execute($sql);

        $table = new XMLDBTable('data');
        $field = new XMLDBField('notification');
        // First step, Set NOT NULL
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'editany');
        $result = $result && $dbman->change_field_notnull($table, $field);
        // Second step, Set default to 0
        $result = $result && $dbman->change_field_default($table, $field);
    }

    return $result;
}

?>
