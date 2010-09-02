<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999-onwards Moodle Pty Ltd  http://moodle.com          //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////

class data_field_checkbox extends data_field_base {

    var $type = 'checkbox';

    function display_add_field($recordid=0) {
        global $CFG, $DB;

        $content = array();

        if ($recordid) {
            $content = $DB->get_field('data_content', 'content', array('fieldid'=>$this->field->id, 'recordid'=>$recordid));
            $content = explode('##', $content);
        } else {
            $content = array();
        }

        $str = '<div title="'.s($this->field->description).'">';
        $str .= '<fieldset><legend><span class="accesshide">'.$this->field->name.'</span></legend>';

        $i = 0;
        foreach (explode("\n", $this->field->param1) as $checkbox) {
            $checkbox = trim($checkbox);
            if ($checkbox === '') {
                continue; // skip empty lines
            }
            $str .= '<input type="hidden" name="field_' . $this->field->id . '[]" value="" />';
            $str .= '<input type="checkbox" id="field_'.$this->field->id.'_'.$i.'" name="field_' . $this->field->id . '[]" ';
            $str .= 'value="' . s($checkbox) . '" ';

            if (array_search($checkbox, $content) !== false) {
                $str .= 'checked />';
            } else {
                $str .= '/>';
            }
            $str .= '<label for="field_'.$this->field->id.'_'.$i.'">'.$checkbox.'</label><br />';
            $i++;
        }
        $str .= '</fieldset>';
        $str .= '</div>';
        return $str;
    }

    function display_search_field($value='') {
        global $CFG, $DB, $OUTPUT;
        if (is_array($value)) {
            $content = $value['checked'];
            $allrequired = $value['allrequired'] ? 'checked = "checked"' : '';
        } else {
            $content = array();
            $allrequired = '';
        }

        $str = '';
        $found = false;
        foreach (explode("\n",$this->field->param1) as $checkbox) {
            $checkbox = trim($checkbox);

            if (in_array($checkbox, $content)) {
                $str .= html_writer::checkbox('f_'.$this->field->id.'[]', s($checkbox), true, $checkbox);
            } else {
                $str .= html_writer::checkbox('f_'.$this->field->id.'[]', s($checkbox), false, $checkbox);
            }
            $found = true;
        }
        if (!$found) {
            return '';
        }

        $str .= html_writer::checkbox('f_'.$this->field->id.'_allreq', null, false, get_string('selectedrequired', 'data'));
        return $str;
    }

    function parse_search_field() {
        $selected    = optional_param('f_'.$this->field->id, array(), PARAM_NOTAGS);
        $allrequired = optional_param('f_'.$this->field->id.'_allreq', 0, PARAM_BOOL);
        if (empty($selected)) {
            // no searching
            return '';
        }
        return array('checked'=>$selected, 'allrequired'=>$allrequired);
    }

    function generate_sql($tablealias, $value) {
        static $i=0;
        $i++;
        $name = "df_checkbox_$i";
        $allrequired = $value['allrequired'];
        $selected    = $value['checked'];

        if ($selected) {
            $conditions = array();
            foreach ($selected as $sel) {
                $likesel = str_replace('%', '\%', $sel);
                $likeselsel = str_replace('_', '\_', $likesel);
                $conditions[] = "({$tablealias}.fieldid = {$this->field->id} AND ({$tablealias}.content = '$sel'
                    OR {$tablealias}.content LIKE '$likesel##%'
                    OR {$tablealias}.content LIKE '%##$likesel'
                    OR {$tablealias}.content LIKE '%##$likesel##%'))";
            }
            if ($allrequired) {
                return array(" (".implode(" AND ", $conditions).") ", array($name=>$value));
            } else {
                return array(" (".implode(" OR ", $conditions).") ", array($name=>$value));
            }
        } else {
            return array(" ", array());
        }
    }

    function update_content($recordid, $value, $name='') {
        global $DB;

        $content = new object();
        $content->fieldid = $this->field->id;
        $content->recordid = $recordid;
        $content->content = $this->format_data_field_checkbox_content($value);

        if ($oldcontent = $DB->get_record('data_content', array('fieldid'=>$this->field->id, 'recordid'=>$recordid))) {
            $content->id = $oldcontent->id;
            return $DB->update_record('data_content', $content);
        } else {
            return $DB->insert_record('data_content', $content);
        }
    }

    function display_browse_field($recordid, $template) {
        global $DB;

        if ($content = $DB->get_record('data_content', array('fieldid'=>$this->field->id, 'recordid'=>$recordid))) {
            if (empty($content->content)) {
                return false;
            }

            $options = explode("\n",$this->field->param1);
            $options = array_map('trim', $options);

            $contentArr = explode('##', $content->content);
            $str = '';
            foreach ($contentArr as $line) {
                if (!in_array($line, $options)) {
                    // hmm, looks like somebody edited the field definition
                    continue;
                }
                $str .= $line . "<br />\n";
            }
            return $str;
        }
        return false;
    }

    function format_data_field_checkbox_content($content) {
        if (!is_array($content)) {
            return NULL;
        }
        $options = explode("\n", $this->field->param1);
        $options = array_map('trim', $options);

        $vals = array();
        foreach ($content as $key=>$val) {
            if ($key === 'xxx') {
                continue;
            }
            if (!in_array($val, $options)) {
                continue;

            }
            $vals[] = $val;
        }

        if (empty($vals)) {
            return NULL;
        }

        return implode('##', $vals);
    }

}

