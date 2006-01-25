<?php ///Class file for textarea field, extends base_field
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 2005 Martin Dougiamas  http://dougiamas.com             //
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

/// Please refer to lib.php for method comments

class data_field_textarea extends data_field_base {

    var $type = 'textarea';
    var $id;

    
    function data_field_textarea($fid=0){
        parent::data_field_base($fid);
    }
    
    
    /***********************************************
     * Saves the field into the database           *
     ***********************************************/
    function insert_field($dataid, $type='textarea', $name, $desc='', $autolink=0, $width='', $height='', $formats='') {
        $newfield = new object;
        $newfield->dataid = $dataid;
        $newfield->type = $type;
        $newfield->name = $name;
        $newfield->description = $desc;
        $newfield->param1 = $autolink;
        $newfield->param2 = $width;
        $newfield->param3 = $height;
        $newfield->param4 = '';
        
        foreach ($formats as $format) {
            $newfield->param4 .= $format . ',';
        }
        $newfield->param4 = substr($newfield->param4, 0, -1);
        
        if (!insert_record('data_fields', $newfield)) {
            notify('Insertion of new field failed!');
        }
    }
    
    
    /***********************************************
     * Prints the form element in the add template *
     ***********************************************/
    function display_add_field($id, $rid=0) {
        global $CFG;
        if (!$field = get_record('data_fields', 'id', $id)){
            notify('That is not a valid field id!');
            exit;
        }
        if ($rid) {
            $dataContent = get_record('data_content', 'fieldid', $id, 'recordid', $rid);
            $content = $dataContent->content;
        }
        else {
            $content = '';
        }
        $str = '';

        if ($field->description) {
            $str .= '<img src="'.$CFG->pixpath.'/help.gif" alt="'.$field->description.'" title="'.$field->description.'" />&nbsp;';
        }
        $str .= '<textarea name="field_' . $field->id . '" id="field_'.$field->id . '"';
        if (!empty($field->param2) && !empty($field->param3)) {
            $str .= ' style="width:' . $field->param2. 'px; height:' . $field->param3 . 'px;"';
        }
        $str .= '>' . $content . '</textarea>';
        
        // Get the available text formats for this field.
        if (!empty($field->param4)) {
            $savedFormats = explode(',', $field->param4);
            $formatsForField = array();
            
            $validFormats = format_text_menu();
            
            foreach ($validFormats as $key => $format) {
                if (array_search($key, $savedFormats) !== false) {
                    $formatsForField[$key] = $format;
                }
            }
            $str .= '<br />';
            
            if (empty($dataContent->content1)) {
                $str .= choose_from_menu($formatsForField, 'field_' . $field->id . '_content1', '', 'choose', '', '', true);
            }
            else {
                $str .= choose_from_menu($formatsForField, 'field_' . $field->id . '_content1', $dataContent->content1, 'choose', '', '', true);
            }
        }
        return $str;
    }


    function display_edit_field($id, $mode=0) {
        parent::display_edit_field($id, $mode);
    }
        

    function update($fieldobject) {
        $fieldobject->param1 = trim($fieldobject->param1);
        $fieldobject->param2 = trim($fieldobject->param2);
        $fieldobject->param3 = trim($fieldobject->param3);
        
        // Convert the param4 array to a comma-delimited string.
        $param4Str = '';
        foreach ($fieldobject->param4 as $val) {
            $param4Str .= $val . ',';
        }
        $param4Str = substr($param4Str, 0, -1);
        $fieldobject->param4 = $param4Str;
        
        if (!update_record('data_fields',$fieldobject)){
            notify ('upate failed');
        }
    }
    
    
    /************************************
     * store content of this field type *
     ************************************/
    function store_data_content($fieldid, $recordid, $value, $name=''){
        if ($value) {
            $content = new object;
            $content->fieldid = $fieldid;
            $content->recordid = $recordid;
            
            if ($oldcontent = get_record('data_content','fieldid', $fieldid, 'recordid', $recordid)) {
                // This belongs to an existing data_content.
                $content->id = $oldcontent->id;
                $nameParts = explode('_', $name);
                $column = $nameParts[count($nameParts) - 1];  // Format is field_<fieldid>_content[1 to 4]
                
                $content->$column = clean_param($value, PARAM_NOTAGS);
                update_record('data_content', $content);
            }
            else {
                // First (and maybe only) data content for this field for this record.
                $content->content = clean_param($value, PARAM_NOTAGS);
                insert_record('data_content', $content);
            }
        }
    }
    
    
    /*************************************
     * update content of this field type *
     *************************************/
    function update_data_content($fieldid, $recordid, $value, $name=''){
        // If data_content already exists, we update.
        if ($oldcontent = get_record('data_content','fieldid', $fieldid, 'recordid', $recordid)){
            $content = new object;
            $content->fieldid = $fieldid;
            $content->recordid = $recordid;
            
            $nameParts = explode('_', $name);
            if (!empty($nameParts[2])) {
                $content->$nameParts[2] = clean_param($value, PARAM_NOTAGS);
            }
            else {
                $content->content = clean_param($value, PARAM_NOTAGS);
            }
            $content->id = $oldcontent->id;
            update_record('data_content', $content);
        }
        else {    //make 1 if there isn't one already
            $this->store_data_content($fieldid, $recordid, $value, $name='');
        }
    }
}
?>
