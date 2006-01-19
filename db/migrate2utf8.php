<?
function migrate2utf8_data_fields_name($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $SQL = "SELECT d.course
           FROM {$CFG->prefix}data_fields df,
                {$CFG->prefix}data d
           WHERE d.id = df.dataid
                 AND df.id = $recordid";

    if (!$data = get_record_sql($SQL)) {
        log_the_problem_somewhere();
        return false;
    }
    
    if (!$datafield = get_record('data_fields','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }
    
    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($datafield->name, $fromenc);

    $newdatafield = new object;
    $newdatafield->id = $recordid;
    $newdatafield->name = $result;
    update_record('data_fields',$newdatafield);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_fields_description($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $SQL = "SELECT d.course
           FROM {$CFG->prefix}data_fields df,
                {$CFG->prefix}data d
           WHERE d.id = df.dataid
                 AND df.id = $recordid";

    if (!$data = get_record_sql($SQL)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$datafield = get_record('data_fields','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($datafield->description, $fromenc);

    $newdatafield = new object;
    $newdatafield->id = $recordid;
    $newdatafield->description = $result;
    update_record('data_fields',$newdatafield);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_name($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->name, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->name = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_intro($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->intro, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->intro = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_singletemplate($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->singletemplate, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->singletemplate = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_listtemplate($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->listtemplate, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->listtemplate = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_addtemplate($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->addtemplate, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->addtemplate = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_rsstemplate($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->rsstemplate, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->rsstemplate = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_listtemplateheader($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)){
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->listtemplateheader, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->listtemplateheader = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_data_listtemplatefooter($recordid){
    global $CFG;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$data = get_record('data','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    $sitelang   = $CFG->lang;
    $courselang = get_course_lang($data->course);  //Non existing!
    $userlang   = get_main_teacher_lang($data->course); //N.E.!!

    $fromenc = get_original_encoding($sitelang, $courselang, $userlang);

/// We are going to use textlib facilities
    $textlib = textlib_get_instance();
/// Convert the text
    $result = $textlib->convert($data->listtemplatefooter, $fromenc);

    $newdata= new object;
    $newdata->id = $recordid;
    $newdata->listtemplatefooter = $result;
    update_record('data',$newdata);
/// And finally, just return the converted field
    return $result;
}

?>
