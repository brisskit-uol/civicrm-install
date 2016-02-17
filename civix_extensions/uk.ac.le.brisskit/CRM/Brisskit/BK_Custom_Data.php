<?php

class BK_Custom_Data {

  static function init_custom_data() {
    require_once 'CRM/Brisskit/BK_Core.php';

    /* 
      option group - "Current status"
    */
    $og = self::create_civi_option_group(
			array("name"      => "current_status_12345",
						"label"     => "Current status",
						"is_active" => 1,
            "title"     => "Current status",
            "default"   =>3));
    self::create_civi_option_value(
      "current_status_12345",
			array("label"     => BK_Constants::CONTACT_STATUS_NOTAVAILABLE,
						"name"      => BK_Constants::CONTACT_STATUS_NOTAVAILABLE,
						"is_active" =>1,
            "value"     =>1));
    self::create_civi_option_value(
      "current_status_12345",
			array("label"     => BK_Constants::CONTACT_STATUS_DECEASED,
						"name"      => BK_Constants::CONTACT_STATUS_DECEASED,
						"is_active" => 1,
            "value"     => 2));
    self::create_civi_option_value(
      "current_status_12345",
			array("label"     => BK_Constants::CONTACT_STATUS_AVAILABLE,
						"name"      => BK_Constants::CONTACT_STATUS_AVAILABLE,
						"is_active" => 1,
            "value"     => 3));
    self::create_civi_option_value(
      "current_status_12345",
			array("label"     => BK_Constants::CONTACT_STATUS_INSTUDY,
						"name"      => BK_Constants::CONTACT_STATUS_INSTUDY,
						"is_active" => 1,
            "value"     => 4));


    /*
      custom group - "Permission"
    */
    $cg = self::create_civi_custom_group(
			array("title"     => "Permission",
						"extends"   => "Contact",
            "is_active" => 1,
            "style"     => "Inline"));
    #set is active and is view for some and weight (order) of fields
    self::create_civi_custom_field($cg,
			array("weight"    => 1,
						"label"     => "Permission to contact",
						"data_type" => "Boolean",
						"html_type" => "Radio",
            "is_active" => 1));
    self::create_civi_custom_field($cg,
			array("weight"    => 2,
						"label"     => "BRISSkit ID",
						"data_type" => "String",
						"html_type" => "Text",
            "is_active" => 1));
    self::create_civi_custom_field($cg,
			array("weight"    => 3,
						"label"     => "Date Permission Given",
						"data_type" => "Date",
						"html_type" => "Select Date",
            "date_format" => "MM d, yy",
            "is_active" => 1));
    self::create_civi_custom_field($cg,
			array("weight"    => 4,
						"label"     => "Comments",
						"data_type" => "Memo",
						"html_type" => "TextArea",
            "is_active" => 1,
            "note_columns"=> 80,
						"note_rows" => 4));


    /*
      custom group - "Participant Status"
    */
    $cg = self::create_civi_custom_group(
			array("title"     => "Participant Status",
						"extends"   => "Contact",
            "is_active" => 1,
            "style"     => "Inline"));
    self::create_civi_custom_field($cg,
			array("label"     => "Initial study",
						"data_type" => "String",
						"html_type" => "Text",
            "is_active" => 1));
    self::create_civi_custom_field($cg,
			array("name"      => "Status log",
						"label"     => "Status log",
						"default_value"=>null,
						"data_type" => "String",
						"html_type" => "Textarea","is_active"=>1,"column_name"=>"status_log","option_group_id"=>$og['id']));
    self::create_civi_custom_field($cg,
			array("name"      => "Current status",
						"label"     => "Current status",
						"default_value"=>3,
						"data_type" => "String",
						"html_type" => "Select",
            "is_active" => 1,
            "column_name"=> "current_status",
            "option_group_id"=> $og['id']));

    /*
      custom group - "Workflow"
    */
    $cg = self::create_civi_custom_group(
			array("title"     => "Workflow",
						"extends"   => "Activity",
            "is_active" => 1,
            "style"     => "Inline",
            "collapse_display" => 1));
    self::create_civi_custom_field($cg,
			array("name"      => "workflow_triggered",
						"label"     => "Workflow triggered",
						"data_type" => "Boolean",
						"html_type" => "Radio",
            "is_active" => 1,
						"is_view"   => 1));


    
    /*
      option value - "activity_type"
    */
    self::create_civi_option_value(
      "activity_type",
			array("name"      => BK_Constants::ACTIVITY_CHECK_STATUS,
						"label"     => BK_Constants::ACTIVITY_CHECK_STATUS,
						"is_active" => 1));
    self::create_civi_option_value(
      "activity_type",
			array("name"      => BK_Constants::ACTIVITY_POSITIVE_REPLY,
						"label"     => BK_Constants::ACTIVITY_POSITIVE_REPLY,
						"is_active" => 1));
    self::create_civi_option_value(
      "activity_type",
			array("name"      => BK_Constants::ACTIVITY_DATA_TRANSFER,
						"label"     => BK_Constants::ACTIVITY_DATA_TRANSFER,
						"is_active" => 1));

    /*
      option value - "activity_status"
    */
    self::create_civi_option_value(
      "activity_status",
			array("name"      => BK_Constants::ACT_STATUS_PENDING,
            "label"     => BK_Constants::ACT_STATUS_PENDING,
						"is_active" => 1));
    self::create_civi_option_value(
      "activity_status",
			array("name"      => BK_Constants::ACT_STATUS_ACCEPTED,
						"label"     => BK_Constants::ACT_STATUS_ACCEPTED,
						"is_active" => 1));
    self::create_civi_option_value(
      "activity_status",
			array("name"      => BK_Constants::ACT_STATUS_REJECTED,
						"label"     => BK_Constants::ACT_STATUS_REJECTED,
						"is_active" => 1));
    self::create_civi_option_value(
      "activity_status",
			array("name"      => BK_Constants::ACT_STATUS_FAILED,
						"label"     => BK_Constants::ACT_STATUS_FAILED,
						"is_active" => 1));


    /*
      custom group - "Genomics Data"
    */
    $cg = self::create_civi_custom_group(
			array("title"     => "Genomics Data",
						"extends"   => "Contact",
            "is_active" => 1,
            "style"     => "Inline",
            "collapse_display" => 1));
    self::create_civi_custom_field($cg,
			array("name"      => "family_id",
						"label"     => "Family ID",
						"data_type" => "String",
						"html_type" => "Text",
            "is_active" => 1));
    self::create_civi_custom_field($cg,
			array("name"      => "s_number",
						"label"     => "S Number",
						"data_type" => "String",
						"html_type" => "Text",
            "is_active" => 1));
    self::create_civi_custom_field($cg,
			array("name"      => "nhs_number",
						"label"     => "NHS Number",
						"data_type" => "String",
						"html_type" => "Text",
            "is_active" => 1));
    self::create_civi_custom_field($cg,
			array("name"      => "gel_participant_id",
						"label"     => "Gel Participant ID",
						"data_type" => "String",
						"html_type" => "Text",
            "is_active" => 1));


    /*
      option value - "case_type"
    */
    self::create_civi_option_value(
      "case_type",
			array("name"      => BK_Constants::DEMO_CASE_TYPE,
						"label"     => BK_Constants::DEMO_CASE_TYPE,
						"is_active" => 1));
  }

  /*
    utility method which determines the custom fields in parameters ($params) and populates them with human readable keys ($fields) for a particular 
    group ($group) of custom fields in civicrm
  */
  static function populate_custom_fields($fields,&$params,$group) {
    require_once("CRM/Core/BAO/CustomField.php");
    $settings = array();
    global $custom_fields;

    // go through provided field definitions and obtain the custom ID (e.g. custom_5) => create hash of custom ID => provided label
    // e.g. custom_6 => 'brisskit_id'
    foreach($fields as $key => $value) {
      $settings["custom_".CRM_Core_BAO_CustomField::getCustomFieldID( $value, $group )]=$key;
    }

    BK_Utils::audit("Settings are : " . print_r ($settings, TRUE));

    // loop through each provided (usually POST) parameter looking for 'custom' fields
    // if they are custom insert a new value with the provided label (e.g. custom_5 also represented by brisskit_id)
    // also store the custom_ parameter with the label in the custom_fields global array so it can be used by set_custom_field
    foreach($params as $key=>$value) {
      if (substr($key,0,6)=="custom") {
        $data = CRM_Core_BAO_CustomField::getKeyID($key);

        if (isset($settings["custom_".$data])) {
          $label = $settings["custom_".$data];
          $params[$label]=$value;
          $custom_fields[$label]=$key;
        }
      }
    }	
  }

  
  /*
    utility method to set a custom field in parameters using the global variable (e.g. internally substitutes 'custom_2_1' for 'brisskit_id'
  */
  static function set_custom_field($label, $value, &$params) {
    global $custom_fields;

    BK_Utils::audit("Custom fields are: " . print_r($custom_fields, TRUE));

    if (isset($custom_fields[$label])) {
      $field_name = $custom_fields[$label]; // e.g. brisskit_id;
      $params[$field_name] = $value;
    }
    else {
      BK_Utils::set_status("$label is not a valid custom field in " . __FILE__ . " " . __FUNCTION__);
    }
  }


  /* 
    utility method containing human readable keys and names for custom  'genomics' fields in the database
  */
  static function genomics_fields() {
    $settings = array(
        "family_id"           => "Family ID",
        "s_number"            => "S Number",
        "nhs_number"          => "NHS Number",
        "gel_participant_id"  => "Gel Participant ID",
    );
    return $settings;
  }


  /* 
    utility method containing human readable keys and names for custom  'permission' fields in the database
  */
  static function permission_fields() {
    $settings = array(
        "permission_given" => "Permission to contact",
        "brisskit_id" => "BRISSkit ID",
        "date_given" => "Date Permission Given",
    );
    return $settings;
  }


  /* 
    utility method containing human readable keys and names for custom  'status' fields in the database
  */
  static function status_fields() {
    $settings = array(
        "status" => "Current status",
        "study" => "Initial study",
        "status_log" => "Status log",
    );
    return $settings;
  }

  
  /* 
    utility method to get 'option value' value for specific group and value
  */
  static function get_option_group_value($group,$name) {
    require_once "api/v3/OptionValue.php";
    require_once "api/v3/utils.php";
    
    if (!$name || strlen($name)==0) {
      return null;
    }
    
    $ov_params = array(
      'name' => $name,
      'option_group_name' => $group
    );
    
    $ov = civicrm_api3_option_value_get($ov_params);
    
    if (!isset($ov['id'])) {
      throw new Exception("'".$group."' option name '$name' could not be found or multiple options with the same name");
    }
    
    $id = $ov['id'];
      
    $values = $ov['values'][$id];
    return $values['value'];
  }

  
  /*
    utility method to get 'option value' name for specific group and value
  */
  static function get_option_group_name($group,$value) {
    require_once "api/v3/OptionValue.php";
    require_once "api/v3/utils.php";
    
    if (!$value || strlen($value)==0) {
      return null;
    }
    
    $ov_params = array(
      'value' => $value,
      'option_group_name' => $group
    );
    
    $ov = civicrm_api3_option_value_get($ov_params);
    
    if (!isset($ov['id'])) {
      throw new Exception("'".$group."' option value '$value' could not be found or multiple options with the same name");
    }
    
    $id = $ov['id'];
      
    $values = $ov['values'][$id];
    return $values['name'];
  }

  
  /*
    utility method to create civi custom group
  */
  static function create_civi_custom_group($params) {
    BK_Utils::audit(print_r($params, TRUE));
    
    require_once 'api/v3/CustomGroup.php';
    require_once 'api/v3/utils.php';
    
    $params['version']='3';
    $cg = civicrm_api3_custom_group_get($params);
    
    if ($cg['is_error']) {
      throw new Exception("Error creating custom group:".$ci['error']);
    }
    if ($cg['count']==0) {
      $cg = civicrm_api3_custom_group_create($params);
    }
    
    return array_shift($cg['values']);
  }

  
  /*
    utility method to create custom field in civi custom group (where $group is a custom group object)
  */
  static function create_civi_custom_field(&$cg, $params) {
    BK_Utils::audit("Creating custom field for group " . print_r($cg, TRUE));
    BK_Utils::audit(print_r($params, TRUE));
    require_once 'api/v3/CustomField.php';
    require_once 'api/v3/utils.php';
    
    $params['version']='3';
    $params['custom_group_id']=$cg['id'];
    
    #lookup using label (name doesn't work!)
    $cut_params = array("label"=>$params['label'], "custom_group_id"=>$cg['id']);
    
    $ci = civicrm_api3_custom_field_get($cut_params);
    if ($ci['is_error']) {
      throw new Exception("Error creating/getting custom field:".$ci['error']);
    }
    if ($ci['count']==0) {
      try {
        $ci = civicrm_api3_custom_field_create($params);
      }
      catch(Exception $ex) {
        BK_Utils::set_status("Unknown error creating custom field " . $params['label']);
        var_dump($ex);
      }
    }

    return array_shift($ci['values']);
  }


  /*
    utility method to create option value in civi group (where $group is a group name)
  */
  static function create_civi_option_value($group,$params) {
    BK_Utils::audit(print_r($group, TRUE));
    BK_Utils::audit(print_r($params, TRUE));
    require_once "api/v3/OptionGroup.php";
    require_once "api/v3/OptionValue.php";
    require_once "api/v3/utils.php";
    
    $og_pars = array('name'=>$group);
    $og = civicrm_api3_option_group_get($og_pars);
    
    if (!isset($og['id'])) {
      throw new Exception("'".$group."' option group '$group' could not be found or multiple options with the same name");
    }
    $id = $og['id'];
    
    $params['option_group_id']=$id;
    
    $ov = civicrm_api3_option_value_get($params);
    if (isset($ov['count'])) {
      if ($ov['count']>0) {
        return array_shift($ov['values']);
      }
    }

    // Count is not set or is 0
    try {
      $ov = civicrm_api3_option_value_create($params);
    }
    catch(Exception $ex) {  
      BK_Utils::set_status("Unknown error creating option value " . $params['label']);
      var_dump($ex);
    }
    
    if (!isset($ov['id'])) {
      throw new Exception("'".$group."' option value '$value' could not be found or multiple options with the same name.");
    }
    
    $id = $ov['id'];
    return $ov['values'][$id];
  }

  
  /*
    utility method to create option group in civi
  */
  static function create_civi_option_group($params) {
    BK_Utils::audit(print_r($params, TRUE));
    require_once "api/v3/OptionGroup.php";
    require_once "api/v3/utils.php";
    $params['version']=3;
    $og = civicrm_api3_option_group_get($params);
    
    if (!isset($og['id'])) {
      try {
        $og = civicrm_api3_option_group_create($params);
      }
      catch(Exception $ex) {  
        BK_Utils::set_status("Unknown error creating option group " . $params['label']);
        var_dump($ex);
      }
    }
    return array_shift($og['values']); 
  }

  static function get_custom_field_id ($custom_field_name) {
    $result = civicrm_api3('CustomField', 'get', array(
      'sequential' => 1,
      'name' => $custom_field_name,
    ));
    if (($result['count']==1) && ($result['is_error']==0) ) {
      $custom_field_id = $result['values'][0]['id'];
      return $custom_field_id;
    }
    return 0;
  }


  /* 
    Create a custom value for a custom field, belonging to the specified entity
  */
  static function create_custom_value($entity_table, $entity_id, $custom_field_name, $custom_field_value) {
    /*
      1) Get the field column name
      2) Construct the API3 call
      3) run it
    */

    BK_Utils::audit("tab id field val $entity_table, $entity_id, $custom_field_name, $custom_field_value");

    $result = civicrm_api3('CustomField', 'get', array(
      'sequential' => 1,
      'name' => "family_id",
    ));
    if (($result['count']==1) && ($result['is_error']==0) ) {
      $custom_field_id = $result['values'][0]['id'];
    }

    $custom_col_name = 'custom_' . $custom_field_id; 


    BK_Utils::audit("tab $entity_table, custom_filed_id $custom_field_id, val $custom_field_value, entity_id $entity_id");

    $result = civicrm_api3($entity_table, 'create', array(
        'sequential' => 1,
        $custom_col_name => $custom_field_value,
        'id' => $entity_id,
    ));
  }

  
  /*
    utility method to set contact status to a particular text value or ID
  */
  static function set_contact_status($contact, $status, $log_text, $status_id=null) {
    if (!$status_id) {
      $status_id = self::get_option_group_value("current_status_12345",$status);
    }
    self::set_custom_field("status",$status_id,$contact);
    $slog = "";
    if (isset($contact['status_log'])) {
      $slog = $contact['status_log'];
    }
    $slog.=date(DATE_ATOM)." - $log_text\n";

    self::set_custom_field("status_log",$slog,$contact);
    $params = array(
      'entity_table'=>"Contact",
      'entity_id'=>$contact['contact_id'],
      'version'=>3
    );
    foreach ($contact as $field => $value) {
      if (substr($field,0,6)=="custom") {
        $cust_fld = explode("_",$field);
        $params[$cust_fld[0]."_".$cust_fld[1]]=$value;
      }
    }

    try {
      $cust_vals = civicrm_api3_custom_value_create($params);
    }
    catch(Exception $ex) {
      BK_Utils::set_status("Unknown error creating custom value " . $params['label']);
    }
  }

  
  /*  
    utility method to set workflow trigger field to true
  */
  static function set_activity_triggered($id) {
    require_once("CRM/Core/BAO/CustomField.php");
    require_once("api/v3/CustomValue.php");
    $params = array(
      'entity_table'=>"Activity",
      'entity_id'=>$id,
      'version'=>3
    );
    $trigger_field = "custom_".CRM_Core_BAO_CustomField::getCustomFieldID( "Workflow triggered", "Workflow" );
    $params[$trigger_field]="1";
    try {
      $cust_vals = civicrm_api3_custom_value_create($params);
    }
    catch(Exception $ex) {
      BK_Utils::set_status("Unknown error creating custom value " . $params['label']);
      # var_dump($ex);
    }
  }


  /*
    utility method containing human readable keys and names for custom  'workflow' fields in the database
  */
  static function workflow_fields() {
    $settings = array(
        "wf_triggered" => "Workflow triggered",
    );
    return $settings;
  }

  
  /* 
    utility method to determine if workflow has been triggered (use parameters passed from civicrm_pre DB hook)
  */
  static function is_triggered(&$params) {
    self::populate_custom_fields(self::workflow_fields(),$params,"Workflow");
    if (isset($params['wf_triggered']) && $params['wf_triggered']==1) {
      return true;
    }
    return false;
  }



  /*
   *
   * Set recruitment count. This is done on case creation.
   * Depending on requirements we may want something more sophisticated, for example
   * a patient may have to get past a certain activity (such as consent form received) to be properly considered
   * a participant.
   *
   */
  static function set_recruitment_count($contact_id, $count) {
    $params = array(
      'Brisskit_Recruitment_Count_1' => 1,
      'entity_id' => $contact_id,
      'id' => 1,
      'custom_Brisskit_Contact_Data:Brisskit Recruitment Count' => $count,
    );

    try{
      $result = civicrm_api3('CustomValue', 'create', $params);
    }
    catch (CiviCRM_API3_Exception $e) {
      // Handle error here.
      $errorMessage = $e->getMessage();
      $errorCode = $e->getErrorCode();
      $errorData = $e->getExtraParams();
      BK_Utils::audit ('GVce:'.print_r(array(
        'error' => $errorMessage,
        'error_code' => $errorCode,
        'error_data' => $errorData,
      ), TRUE));
      return array(
        'error' => $errorMessage,
        'error_code' => $errorCode,
        'error_data' => $errorData,
      );
    }
    return $result;
  }


  /*
   *
   * get custom field recruitment count, example since syntax is not obvious, currently not called.
   *
   */
  static function get_recruitment_count ($contact_id) {
    $params = array(
      'bk_study_count_1' => 1,
      'entity_id' => $contact_id,
      'return.Brisskit_Contact_Data:Brisskit_Recruitment_Count_1' => 1,
    );
    try{
      $result = civicrm_api3('CustomValue', 'get', $params);
      }
    catch (CiviCRM_API3_Exception $e) {
      // Handle error here.
      $errorMessage = $e->getMessage();
      $errorCode = $e->getErrorCode();
      $errorData = $e->getExtraParams();
      BK_Utils::audit ('GVe:'.print_r(array(
          'error' => $errorMessage,
          'error_code' => $errorCode,
          'error_data' => $errorData,
      ), TRUE));

      return array(
          'error' => $errorMessage,
          'error_code' => $errorCode,
          'error_data' => $errorData,
      );
    }
    return $result;
  }
}
?>
