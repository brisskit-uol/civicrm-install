<?php
#utility method which determines the custom fields in parameters ($params) and populates them with human readable keys ($fields) for a particular 
#group ($group) of custom fields in civicrm
function populate_custom_fields($fields,&$params,$group) {
	require_once("CRM/Core/BAO/CustomField.php");
	$settings = array();
	global $custom_fields;
	#go through provided field definitions and obtain the custom ID (e.g. custom_5) => create hash of custom ID => provided label
	#e.g. custom_6 => 'brisskit_id'
	foreach($fields as $key => $value) {
		$settings["custom_".CRM_Core_BAO_CustomField::getCustomFieldID( $value, $group )]=$key;
	}

	#loop through each provided (usually POST) parameter looking for 'custom' fields
	#if they are custom insert a new value with the provided label (e.g. custom_5 also represented by brisskit_id)
	#also store the custom_ parameter with the label in the custom_fields global array so it can be used by set_custom_field
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

#utility method to get contact along with custom field values
function get_contact_with_custom_values($contact_id) {
	require_once "api/v3/Contact.php";
	require_once "api/v3/utils.php";
	require_once "api/v3/CustomValue.php";
	
	#get contact details
	$contact_complete = civicrm_api3_contact_get(array('contact_id' => $contact_id));
	$contact_vals = $contact_complete['values'];
	$contact = array_shift($contact_vals);
	
	#have to get custom values separately by using the custom_value api
	$params = array('entity_table'=>'Contact', 'entity_id'=>$contact['contact_id'],'version'=>'3');
	$cust_vals = civicrm_api3_custom_value_get($params);
	
	#if no custom values then return the contact as is
	if (civicrm_error($cust_vals)) {
		if (preg_match('/^No values found for the specified entity ID/',$cust_vals['error_message'])) {
			return $contact;
		}
		else {
			throw new Exception("Unknown Error:".print_r($cust_vals));
		}
	}
	
	#if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
	foreach($cust_vals['values'] as $id => $record) {
		if (isset($record['latest'])) {
			$contact["custom_".$id."_1"]=$record['latest'];
		}
	}
	populate_custom_fields(permission_fields(),$contact,"Permission");
	populate_custom_fields(status_fields(),$contact,"Participant Status");
	
	#return the contact
	return $contact;
}

#utility method to set a custom field in parameters using the global variable (e.g. internally substitutes 'custom_2_1' for 'brisskit_id'
function set_custom_field($key, $value, &$params) {
	global $custom_fields;
	$params[$custom_fields[$key]]=$value;
}

#utility method containing human readable keys and names for custom  'permission' fields in the database
function permission_fields() {
	$settings = array(
			"permission_given" => "Permission to contact",
		     "brisskit_id" => "BRISSkit ID",
			"date_given" => "Date Permission Given",
	);
	return $settings;
}

#utility method containing human readable keys and names for custom  'status' fields in the database
function status_fields() {
	$settings = array(
			"status" => "Current status",
			"study" => "Initial study",
			"status_log" => "Status log",
	);
	return $settings;
}

#utility method to obtain the contact associated with an activity
function get_case_contact_with_custom_values($case_id) {
	require_once "api/v3/Case.php";
	require_once "api/v3/utils.php";
	require_once "api/v3/CustomValue.php";
	
	#get case associated with activity using the case_id
	$case_complete = civicrm_api3_case_get(array('case_id' => $case_id));
	$case_vals = $case_complete['values'];
	$first_case = array_shift($case_vals);
	$case_contacts = $first_case['contacts'];
	
	#only get a trimmed down contact without the custom values
	$contact = array_shift($case_contacts);
	
	#have to get custom values separately
	$params = array('entity_table'=>'Contact', 'entity_id'=>$contact['contact_id'],'version'=>'3');
	$cust_vals = civicrm_api3_custom_value_get($params);
	
	if (civicrm_error($cust_vals)) {
		if (preg_match('/^No values found for the specified entity ID/',$cust_vals['error_message'])) {
			return $contact;
		}
		else {
			throw new Exception("Unknown Error:".print_r($cust_vals));
		}
	}
	
	#if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
	foreach($cust_vals['values'] as $id => $record) {
		if (isset($record['latest'])) {
			$contact["custom_".$id."_1"]=$record['latest'];
		}
	}
	populate_custom_fields(permission_fields(),$contact,"Permission");
	return $contact;
}


#utility method to create a contact given names and DOB
function create_contact($forename, $surname, $dob) {
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

#utility method to get a contact given an ID
function get_contact($contact_id) {
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
			throw new Exception("Unknown Error:".print_r($cust_vals));
		}
	}
	
	#if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
	foreach($cust_vals['values'] as $id => $record) {
		if (isset($record['latest'])) {
			$contact["custom_".$id."_1"]=$record['latest'];
		}
	}
	populate_custom_fields(permission_fields(),$contact,"Permission");

	
	return $contact;
}

#utility method to get an activity given an ID
function get_activity($act_id) {
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
			throw new Exception("Unknown Error:".print_r($cust_vals));
		}
	}
	
	#if there are custom values insert these into the contact as 'custom_N' parameters to be dealt with later
	foreach($cust_vals['values'] as $id => $record) {
		if (isset($record['latest'])) {
			$act["custom_".$id."_1"]=$record['latest'];
		}
	}
	populate_custom_fields(workflow_fields(),$act,"Workflow");
	
	return $act;
}

#utility method to update a contact given an array of contact info
function update_contact($contact) {
	require_once "CRM/Contact/BAO/Contact.php";
	$contact_json = civicrm_api3_contact_create($contact);
	return array_shift($contact_json['values']);
}

#utility method to update a contact given an array of contact info
function update_activity($activity) {
	require_once "CRM/Activity/BAO/Activity.php";
	$stat_id=null;
	
	if (isset($activity['status'])) {
		#get status ID for provided status
        	$stat_id = get_option_group_value('activity_status',$activity['status']);
		$activity['status_id']=$stat_id;
	}
	
	$contact_json = civicrm_api3_activity_create($activity);
	return array_shift($contact_json['values']);
}

#utility method to delete a contact given their ID
function delete_contact($contact_id) {
	require_once "CRM/Contact/BAO/Contact.php";
	CRM_Contact_BAO_Contact::delete($contact_id);
}

#utility method to delete all cases associated with a contact given their ID
function delete_contact_cases($contactID) {
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

#utility method to obtain date in advance of today given a DateInterval() specific pattern
function get_date_in_advance($interval) {
	$date = new DateTime();
	$date->add(new DateInterval($interval));
	return $date->format('Y-m-dTh:i:s');
}

#add the specified activity to a case with a specific status and subject
function add_activity_to_case($params) {
	
	#split out parameters
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
	
	#get status ID for provided status
	$stat_id = get_option_group_value('activity_status',$status);
	
	#get ID of activity type to add (will not accept name in API)
	$at_id = get_option_group_value('activity_type',$activity_type);
	
	$params=array(
		'case_id' => $case_id,
		'activity_type_id' => $at_id,
		'source_contact_id' => $creator_id,
		'version' => 3,
		'subject' => $subject,
		'activity_status_id'=>$stat_id,
		'details' => $details
	);
	
	#create the activity
	civicrm_api3_activity_create($params);
	
	return true;
}

#utility method to get 'option value' value for specific group and value
function get_option_group_value($group,$name) {
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

#utility method to get case type from case ID
function get_case_type($case_id) {
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
	return get_option_group_name("case_type",$ct_id);
}

#utility method to get 'option value' name for specific group and value
function get_option_group_name($group,$value) {
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

#utility method to count the activities in a case given case ID and activity type name
function count_activities_in_case($case_id, $activity_type) {
	require_once "CRM/Case/BAO/Case.php";
	
	$at_id = get_option_group_value('activity_type',$activity_type);
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
    function getCaseTypeId( $caseTypeName )
    {
    	require_once "CRM/Core/DAO.php";
        $sql = "
    SELECT  ov.value
      FROM  civicrm_option_value  ov
INNER JOIN  civicrm_option_group og ON ov.option_group_id=og.id AND og.name='case_type'
     WHERE  ov.label = %1";

        $params = array( 1 => array( $caseTypeName, 'String' ) );
        
        return CRM_Core_DAO::singleValueQuery( $sql, $params );
    }

#utility method to determine name of activity type from ID
function get_activity_type_name($activity_type_id) {
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

#utility method to determine age in years from date of birth
function age($date_of_birth){
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

#utility method to create civi custom group
function create_civi_custom_group($params) {
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

#utility method to create custom field in civi custom group (where $group is a custom group object)
function create_civi_custom_field(&$cg, $params) {
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
			var_dump($ex);
		}
	}

	return array_shift($ci['values']);
}

#utility method to create option value in civi group (where $group is a group name)
function create_civi_option_value($group,$params) {
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
	$ov = civicrm_api3_option_value_create($params);
	
	if (!isset($ov['id'])) {
		throw new Exception("'".$group."' option value '$value' could not be found or multiple options with the same name");
	}
	
	$id = $ov['id'];
	return $ov['values'][$id];
}

#utility method to create option group in civi
function create_civi_option_group($params) {
	require_once "api/v3/OptionGroup.php";
	require_once "api/v3/utils.php";
	$params['version']=3;
	$og = civicrm_api3_option_group_get($params);
	
	if (!isset($og['id'])) {
		$og = civicrm_api3_option_group_create($params);
	}
	return array_shift($og['values']); 
}

#utility method to set contact status to a particular text value or ID
function set_contact_status($contact, $status, $log_text, $status_id=null) {
	if (!$status_id) {
		$status_id = get_option_group_value("current_status_12345",$status);
	}
	set_custom_field("status",$status_id,$contact);
	$slog = "";
	if (isset($contact['status_log'])) {
		$slog = $contact['status_log'];
	}
	$slog.=date(DATE_ATOM)." - $log_text\n";

	set_custom_field("status_log",$slog,$contact);
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

	$cust_vals = civicrm_api3_custom_value_create($params);
}

#utility method to set workflow trigger field to true
function set_activity_triggered($id) {
	require_once("CRM/Core/BAO/CustomField.php");
	require_once("api/v3/CustomValue.php");
	$params = array(
		'entity_table'=>"Activity",
		'entity_id'=>$id,
		'version'=>3
	);
	$trigger_field = "custom_".CRM_Core_BAO_CustomField::getCustomFieldID( "Workflow triggered", "Workflow" );
	$params[$trigger_field]="1";
	$cust_vals = civicrm_api3_custom_value_create($params);
}

function set_contact_status_via_case($case_id, $status, $log_text) {
	$contact = get_case_contact_with_custom_values($case_id);
	populate_custom_fields(status_fields(),$contact,"Participant Status");
	return set_contact_status($contact, $status, $log_text);
}

#utility method containing human readable keys and names for custom  'workflow' fields in the database
function workflow_fields() {
	$settings = array(
			"wf_triggered" => "Workflow triggered",
	);
	return $settings;
}

#utility method to determine if workflow has been triggered (use parameters passed from civicrm_pre DB hook)
function is_triggered(&$params) {
	populate_custom_fields(workflow_fields(),$params,"Workflow");
	if (isset($params['wf_triggered']) && $params['wf_triggered']==1) {
		return true;
	}
	return false;
}

#utility method to determine if ANY results returned in an array (from invoking modules) are true
function check_results($results) {
	foreach($results as $key=>$value) {
		if ($value==true) {
			return true;
		}
	}
	return false;
}

#utility method to update the template dir (e.g. the civicases directory)
function update_template_dir($custom_dir) {
        require_once "CRM/Core/DAO.php";
#	CRM_Core_DAO::setFieldValue("CRM_Core_DAO_Setting", "customTemplateDir", "value", "s:".strlen($custom_dir)."\"$custom_dir\";", "name");
       $sql = "
    UPDATE  civicrm_setting s SET s.value = %1 WHERE s.name='customTemplateDir'; 
     ";

        $params = array( 1 => array( "s:".strlen($custom_dir).":\"$custom_dir\";", 'String' ) );

        CRM_Core_DAO::executeQuery( $sql, $params );
}

