<?php

class BK_Utils {

  /* 
    Write debug code to a file
  */
  static function audit($message, $audit=false) {
    if ($audit) {
        $file = '/tmp/bk_audit.log';
    }
    else {
        $file = '/tmp/bk_debug.log';
    }
    $ts  = date('Y-m-d H:i:s');
    file_put_contents($file, $ts.' '.$message."\n", FILE_APPEND);
  }


  /**
   *
   * Displays a status in CMS-independent way
   * Statuses are displayed as Javascript popups except for 'no-popup'
   *
   * For CiviCRM 4.6, Valid values for type are 'no-popup', 'info', 'error', 'success', 'alert'
   *
   */
  static function set_status($message, $type='no-popup', $title='') {
      if ($type == '') {
        $type = 'no-popup';
      }
      CRM_Core_Session::setStatus(ts($message), $title, $type);
  }

  
  /*
    utility method to get contact along with custom field values
  */
  static function get_contact_with_custom_values($contact_id) {
    require_once "api/v3/Contact.php";
    require_once "api/v3/utils.php";
    require_once "api/v3/CustomValue.php";

    
    // get contact details
    $contact_complete = civicrm_api3_contact_get(array('contact_id' => $contact_id));
    $contact_vals = $contact_complete['values'];
    $contact = array_shift($contact_vals);
    
    // have to get custom values separately by using the custom_value api
    $params = array('entity_table'=>'Contact', 
                    'entity_id'=>$contact['contact_id'],
                    'version'=>'3');
    $cust_vals = civicrm_api3_custom_value_get($params);
    
    // if no custom values then return the contact as is
    if (civicrm_error($cust_vals)) {
      if (preg_match('/^No values found for the specified entity ID/',$cust_vals['error_message'])) {
        return $contact;
      }
      else {
        throw new Exception("Unknown Error:" . print_r($cust_vals, TRUE));
      }
    }
    
    // if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
    foreach($cust_vals['values'] as $id => $record) {
      if (isset($record['latest'])) {
        $contact["custom_" . $id . "_1"] = $record['latest'];
      }
    }
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::permission_fields(), $contact,"Permission");
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::status_fields(), $contact,"Participant Status");
    
    return $contact;
  }


  /* 
    utility method to obtain the contact associated with an activity
  */
  static function get_case_contact_with_custom_values($case_id) {
    require_once "api/v3/Case.php";
    require_once "api/v3/utils.php";
    require_once "api/v3/CustomValue.php";
    
    // get case associated with activity using the case_id
    $case_complete = civicrm_api3_case_get(array('case_id' => $case_id));
    $case_vals = $case_complete['values'];
    $first_case = array_shift($case_vals);
    
    // only get a trimmed down contact without the custom values
    $contact = array_shift($case_contacts);
    // axa20151117 set contact_id on the contact using the first id from the case_get() call.
    $contact['contact_id']=array_shift($first_case['contact_id']);

    // have to get custom values separately
    $params = array('entity_table'=>'Contact', 'entity_id'=>$contact['contact_id'],'version'=>'3');
    $cust_vals = civicrm_api3_custom_value_get($params);
    
    if (civicrm_error($cust_vals)) {
      if (preg_match('/^No values found for the specified entity ID/',$cust_vals['error_message'])) {
        return $contact;
      }
      else {
        throw new Exception("Unknown Error:" . print_r($cust_vals, TRUE));
      }
    }
    
    // if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
    foreach($cust_vals['values'] as $id => $record) {
      if (isset($record['latest'])) {
        $contact["custom_" . $id . "_1"] = $record['latest'];
      }
    }
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::permission_fields(), $contact, "Permission");
    return $contact;
  }


  /* 
    utility method to create a contact given names and DOB
  */
  static function create_contact($forename, $surname, $dob) {
    require_once "api/v3/Contact.php";
    require_once "api/v3/utils.php";
    $st = array('Individual');
    $params = array(
      'first_name' => $forename,
      'last_name' => $surname,
      'birth_date' => $dob,
      'contact_type' => 'Individual',
    );
    $contact_json = civicrm_api3_contact_create($params);
    return array_shift($contact_json['values']);
  }

  /* 
    utility method to get a contact gnd its associated custom values given an ID
  */
  static function get_contact($contact_id) {
    require_once "api/v3/Contact.php";
    require_once "api/v3/CustomValue.php";
    require_once "api/v3/utils.php";
    $params = array('contact_id'=>$contact_id);
    $contact_vals = civicrm_api3_contact_get($params);
    $contact = array_shift($contact_vals['values']);
    
    $params = array('entity_table'=>'Contact', 'entity_id'=>$contact['contact_id'],'version'=>'3');
    $cust_vals = civicrm_api3_custom_value_get($params);
    
    if (civicrm_error($cust_vals)) {
      if (preg_match('/^No values found for the specified entity ID/',$cust_vals['error_message'])) {
        return $contact;
      }
      else {
        throw new Exception("Unknown Error:" . print_r($cust_vals, TRUE));
      }
    }
    
    // if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
    foreach($cust_vals['values'] as $id => $record) {
      if (isset($record['latest'])) {
        $contact["custom_".$id."_1"]=$record['latest'];
      }
    }
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::permission_fields(),$contact,"Permission");

    return $contact;
  }


  /*
    utility method to get an activity given an ID
  */
  static function get_activity($act_id) {
    require_once "api/v3/Activity.php";
    require_once "api/v3/CustomValue.php";
    require_once "api/v3/utils.php";
    $params = array('activity_id'=>$act_id);
    $act_vals = civicrm_api3_activity_get($params);
    $act = array_shift($act_vals['values']);

    $params = array('entity_table'=>'Activity', 'entity_id'=>$act_id,'version'=>'3');
    $cust_vals = civicrm_api3_custom_value_get($params);
    
    if (civicrm_error($cust_vals)) {
      if (preg_match('/^No values found for the specified entity ID/',$cust_vals['error_message'])) {
        return $act;
      }
      else {
        throw new Exception("Unknown Error:" . print_r($cust_vals, TRUE));
      }
    }
    
    // if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
    foreach($cust_vals['values'] as $id => $record) {
      if (isset($record['latest'])) {
        $act["custom_" . $id . "_1"] = $record['latest'];
      }
    }
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::workflow_fields(),$act,"Workflow");
    
    return $act;
  }

  
  /* 
    utility method to update a contact given an array of contact info
  */
  static function update_contact($contact) {
    require_once "CRM/Contact/BAO/Contact.php";
    $contact_json = civicrm_api3_contact_create($contact);
    return array_shift($contact_json['values']);
  }

  
  /*  
    utility method to update a contact given an array of contact info
  */
  static function update_activity($activity) {
    require_once "CRM/Activity/BAO/Activity.php";
    $stat_id=null;
    
    if (isset($activity['status'])) {
      #get status ID for provided status
            $stat_id = BK_Custom_Data::get_option_group_value('activity_status',$activity['status']);
      $activity['status_id']=$stat_id;
    }
    
    $contact_json = civicrm_api3_activity_create($activity);
    return array_shift($contact_json['values']);
  }

  
  /* 
    utility method to delete a contact given their ID
  */
  static function delete_contact($contact_id) {
    require_once "CRM/Contact/BAO/Contact.php";
    CRM_Contact_BAO_Contact::delete($contact_id);
  }


  /* 
    utility method to delete all cases associated with a contact given their ID
  */
  static function delete_contact_cases($contactID) {
  require_once "CRM/Case/BAO/Case.php";
    require_once "api/v3/utils.php";
    require_once "api/v3/Case.php";

    #get case types associated with contact
    $cases = CRM_Case_BAO_Case::getContactCases($contactID);
    
    #loop through and return if already added
    foreach($cases as $key => $case) {
      CRM_Case_BAO_Case::deleteCase($case['case_id']);
    }
  }

  
  /*
    utility method to obtain date in advance of today given a DateInterval() specific pattern
  */
  static function get_date_in_advance($interval) {
    $date = new DateTime();
    $date->add(new DateInterval($interval));
    return $date->format('Y-m-dTh:i:s');
  }

  
  /* 
    add the specified activity to a case with a specific status and subject
  */
  static function add_activity_to_case($params) {
    
    // split out parameters
    $case_id = $params['case_id'];
    $activity_type = $params['activity_type'];
    $subject = $params['subject'];
    $status = $params['status'];
    $creator_id = isset($params['creator_id']) ? $params['creator_id'] : 1;
    
    $details = "";
    if (isset($params['details'])) {
      $details = $params['details'];
    }
    
    require_once "api/v3/OptionValue.php";
    require_once "api/v3/Activity.php";
    require_once "api/v3/utils.php";
    
    // get status ID for provided status
    $stat_id = BK_Custom_Data::get_option_group_value('activity_status',$status);
    
    // get ID of activity type to add (will not accept name in API)
    $at_id = BK_Custom_Data::get_option_group_value('activity_type',$activity_type);
    
    $params=array(
      'case_id' => $case_id,
      'activity_type_id' => $at_id,
      'source_contact_id' => $creator_id,
      'version' => 3,
      'subject' => $subject,
      'activity_status_id'=>$stat_id,
      'details' => $details
    );
    
    // create the activity
    civicrm_api3_activity_create($params);
    
    return true;
  }

  
  /*
    utility method to get case type from case ID
  */
  static function get_case_type($case_id) {
    require_once "api/v3/Case.php";
    require_once "api/v3/utils.php";
    
    $cs_params = array(
      'case_id' => $case_id
    );
    
    $cs = civicrm_api3_case_get($cs_params);

    if (!isset($cs['id'])) {
      throw new Exception("case ID '$case_id' could not be found or multiple options with the same name");
    }
    
    $id = $cs['id'];
      
    $values = $cs['values'][$id];
    $ct_id = $values['case_type_id'];
    return BK_Custom_Data::get_option_group_name("case_type",$ct_id);
  }

  
  /* 
    utility method to count the activities in a case given case ID and activity type name
  */
  static function count_activities_in_case($case_id, $activity_type) {
    require_once "CRM/Case/BAO/Case.php";
    
    $at_id = BK_Custom_Data::get_option_group_value('activity_type',$activity_type);
    return CRM_Case_BAO_CASE::getCaseActivityCount($case_id,$at_id);
  }


  /**
       * Function to get the case type ID by name (modified from the CiviCRM Case DAO)
       *
       * @param int $caseId
       *
       * @return  case type
       * @access public
       * @static
  */
  static function getCaseTypeId( $caseTypeName ) {
    require_once "CRM/Core/DAO.php";
    $sql = "
      SELECT      ov.value
      FROM        civicrm_option_value  ov
      INNER JOIN  civicrm_option_group og ON ov.option_group_id=og.id AND og.name='case_type'
      WHERE       ov.label = %1";

    $params = array( 1 => array( $caseTypeName, 'String' ) );
   
    return CRM_Core_DAO::singleValueQuery( $sql, $params );
  }

  
  /*
    utility method to determine name of activity type from ID
  */
  static function get_activity_type_name($activity_type_id) {
    if (!$activity_type_id) throw new Exception("No activity_type_id provided");
    require_once "api/v3/OptionValue.php";
    require_once "api/v3/Activity.php";
    require_once "api/v3/utils.php";
    
    $ov_params = array(
      'value' => $activity_type_id,
      'option_group' => 'activity_type'
    );
    
    $ov = civicrm_api3_option_value_get($ov_params);
    $ov_result = array_shift($ov['values']);
    return $ov_result['name'];
  }


  /*
    utility method to determine age in years from date of birth
  */
  static function age($date_of_birth){
     list($year, $month, $day) = explode("-",$date_of_birth);
     $day = (int)$day;
     $month = (int)$month;
     $year = (int)$year;
    
     $y = (int)gmstrftime("%Y");
     $m = (int)gmstrftime("%m");
     $d = (int)gmstrftime("%d");

     $age = $y - $year;
     if($m <= $month)
     {
     if($m == $month)
     {
     if($d < $day) $age = $age - 1;
     }
     else $age = $age - 1;
     }
     return $age;
  }

  
  /* 
  */
  static function set_contact_status_via_case($case_id, $status, $log_text) {
    $contact = get_case_contact_with_custom_values($case_id);
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::status_fields(),$contact,"Participant Status");
    return BK_Custom_Data::set_contact_status($contact, $status, $log_text);
  }


  /* 
    utility method to determine if ANY results returned in an array (from invoking modules) are true
  */
  static function check_results($results) {
    foreach($results as $key=>$value) {
      if ($value==true) {
        return true;
      }
    }
    return false;
  }


  /*
    utility method to update the template dir (e.g. the civicases directory)
  */
  static function update_template_dir($custom_dir) {
    require_once "CRM/Core/DAO.php";
    #	CRM_Core_DAO::setFieldValue("CRM_Core_DAO_Setting", "customTemplateDir", "value", "s:".strlen($custom_dir)."\"$custom_dir\";", "name");
    $sql = "UPDATE  civicrm_setting s SET s.value = %1 WHERE s.name='customTemplateDir';";

    $params = array( 1 => array( "s:".strlen($custom_dir).":\"$custom_dir\";", 'String' ) );

    CRM_Core_DAO::executeQuery( $sql, $params );
  }


  /**
   * Helper function to load groups/roles/ACLs required for Brisskit ACL config from an XML file.
   *  We need to create these groups & roles at install time and remove them at uninstall time.
   *
   * @param $filename string
   *
   * @throws CRM_Extension_Exception_ParseException
   *
   */
  static function get_brisskit_xml ($filename) {
    BK_Utils::audit($filename);

    // Load the file, we expect it to live in our extensions' xml dir
    // $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '.' . DIRECTORY_SEPARATOR . '.' . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $filename;

    $file = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), '..', '..', 'xml', $filename));
    BK_Utils::audit($file);
    try {
      list ($xml, $error) = CRM_Utils_XML::parseFile($file);
    }
    catch(Exception $ex) {
      BK_Utils::audit("Could not parse xml file $file");
      BK_Utils::set_status("Could not parse xml file $file");
      throw new Exception("");
    }

    //Complain if we can't load the file
    if ($xml === FALSE) {
      throw new CRM_Extension_Exception_ParseException("Failed to parse group XML: $file");
    }

    //We loaded the file, convert the XML into an array of groups
    foreach ($xml as $attr => $val) {
      $bk_acl_data[]= CRM_Utils_XML::xmlObjToArray($val);
    }
    return $bk_acl_data;
  }


  /*
   *
   * A very simple version of CRM_Core_DAO::singleValueQuery:
   * 1) Does not rely on DAO
   * 2) Returns a single field
   * 3) Takes a single integer or string as a parameter
   * 4) Must be guaranteed to return 0 or 1 row only
   * 5) sql must include a single ? placeholder
   *
   */
  static function single_value_query ($sql, $param) {
    $db = DB::connect(CIVICRM_DSN);
    if (PEAR::isError($db)) {
      die($db->getMessage());
    }
    $sth = $db->prepare($sql);
    $res =& $db->execute($sth, $param);

    if (PEAR::isError($res)) {
        die($res->getMessage());
    }

    BK_Utils::audit($sql);

    if ($row =& $res->fetchRow()) {
    BK_Utils::audit(print_r($row, TRUE));
      return $row[0];
    }
    else {
      return NULL;
    }
    $db->close();
  }


  /*
   *
   * From the url it is often possible to obtain the case id we are working with.
   *
   * This could break between civi versions if they change the url formats.
   * Ideally civi would have its own canonical form for GET and POST params - might
   * be worth looking into - the paths look suspiciously like REST endpoints (sort of) so 
   * there might be more structure than we've assumed. 
   *
   */
  static function extract_case_id_from_uri ($uri) {
    $uri = str_replace('&amp;', '&', $uri);

    BK_Utils::audit("Entering function " . __FUNCTION__);
    ### Just return if we're not passed a uri to work with
    if (empty($uri)) {
        return 0;
    }

    BK_Utils::audit("extr case id $uri");

    ### Create an array from the uri
    parse_str($uri, $query_parms);

    ### Handle caseid and caseID
    $query_parms = array_change_key_case($query_parms, CASE_LOWER);
    BK_Utils::audit(print_r($query_parms,TRUE));

    ### May not always exist, e.g. click Home->Civicrm



    if (array_key_exists('/civicrm/index_php?q', $query_parms)) {
      $query_path = $query_parms['/civicrm/index_php?q'];
    }
    else {
      $needle = '/civicrm/index_php?q';    

      $query_path = '';
      foreach ($query_parms as $key => $value) {
        // Check for strings ending with $needle
        $start_pos = strlen($key) - strlen($needle);
        // if (strpos($key, $needle, $start_pos) !== FALSE) {
        if (strpos($key, $needle) !== FALSE) {
          $query_path = $value;
        }
      }

      if ($query_path == '') {
        return 0;
      }
    }
    

    ### A mapping of paths from the query string to the correct parm holding the case id.
    ### TODO hold elsewhere?
    $map_query_path_case_parm = array (
        'civicrm/contact/view/case'         => 'id',
        'civicrm/ajax/activity'             => 'caseid',
        'civicrm/ajax/globalrelationships'  => 'caseid',
        'civicrm/ajax/caseroles'            => 'caseid',
        'civicrm/case/activity'             => 'caseid',
    );

    BK_Utils::audit("#######$$$ $uri $query_path");

// 2015-11-18 01:58:41 #########/civicrm/index.php?q=civicrm/case/activity&action=add&reset=1&cid=33&caseid=12&atype=2&snippet=json
// 2015-11-18 01:58:43 #########/civicrm/index.php?q=civicrm/case/activity&snippet=4&type=Activity&subType=2&qfKey=277f73a765f891a7ecd6e99d093e6cc8_3338&cgcount=1

    ### Finally, we can return the case id using the correct parm
    if (array_key_exists($query_path, $map_query_path_case_parm)) {
        $query_parms_key = $map_query_path_case_parm[$query_path];

        ### Avoid 'undefined index' errors by checking key exists

        if (array_key_exists($query_parms_key, $query_parms)) {
            $case_id = $query_parms[$query_parms_key];
        }
        else {
            $case_id = 0;
        }
    }
    else {
        $case_id = 0;
    }

    BK_Utils::audit("Caseid: $case_id");
    return $case_id;
  } 
}
?>
