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
 * PHPUnit data generator tests
 *
 * @package    mod_data
 * @category   phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * PHPUnit data generator testcase
 *
 * @package    mod_data
 * @category   phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_data_generator_testcase extends advanced_testcase {
    public function test_generator() {
        global $DB, $SITE;

        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('data'));

        /** @var mod_data_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_data');
        $this->assertInstanceOf('mod_data_generator', $generator);
        $this->assertEquals('data', $generator->get_modulename());

        $generator->create_instance(array('course'=>$SITE->id));
        $generator->create_instance(array('course'=>$SITE->id));
        $data = $generator->create_instance(array('course'=>$SITE->id));
        $this->assertEquals(3, $DB->count_records('data'));

        $cm = get_coursemodule_from_instance('data', $data->id);
        $this->assertEquals($data->id, $cm->instance);
        $this->assertEquals('data', $cm->modname);
        $this->assertEquals($SITE->id, $cm->course);

        $context = context_module::instance($cm->id);
        $this->assertEquals($data->cmid, $context->instanceid);
    }
}
