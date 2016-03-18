<?php

require_once 'brisskit.civix.php';
require_once 'CRM/Brisskit/BK_Constants.php';
require_once 'CRM/Brisskit/BK_Utils.php';
require_once 'CRM/Brisskit/BK_Temp.php';
require_once 'CRM/Brisskit/BK_Custom_Data.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function brisskit_civicrm_config(&$config) {
  BK_Utils::audit("_brisskit_civix_civicrm_config" . print_r($config, TRUE));

  $our_hooks_dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'our_hooks/';
  $include_path = $our_hooks_dir . PATH_SEPARATOR . get_include_path( );
  set_include_path( $include_path );

  _brisskit_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * This loads the xml that provides routing for our recruitment 
 * (& optionally study) "components". Hook is called either by a 
 * menu/rebuild&reset=1 url call or at extension enable time  in 
 * response to a System flush API call.
 *
 * If the file does not exist, the (partial) install will succeed but a warning message displayed in the backend
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function brisskit_civicrm_xmlMenu(&$files) {
  _brisskit_civix_civicrm_xmlMenu($files);
  $files[] = dirname(__FILE__) . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "Case.xml";
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function brisskit_civicrm_install() {
  _brisskit_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function brisskit_civicrm_uninstall() {
  _brisskit_civix_civicrm_uninstall();

  if ($upgrader = _brisskit_civix_upgrader()) {
    return $upgrader->onUninstall();
  }
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function brisskit_civicrm_enable() {
  _brisskit_civix_civicrm_enable();
//  civicrm_initialize();
  try {
//    init_required_fields();

    BK_Utils::set_status("BRISSkit extension for CiviCRM was setup successfully");

  }
  catch(Exception $ex) {
    BK_Utils::set_status("An unexpected error occured during the BRISSkit extension setup: ".$ex->getMessage(), "error");
  }

}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function brisskit_civicrm_disable() {
  _brisskit_civix_civicrm_disable();
	CRM_Core_Session::setStatus(ts(''));
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function brisskit_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _brisskit_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function brisskit_civicrm_managed(&$entities) {
  _brisskit_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function brisskit_civicrm_caseTypes(&$caseTypes) {
  _brisskit_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function brisskit_civicrm_angularModules(&$angularModules) {
_brisskit_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function brisskit_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _brisskit_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
*/
function brisskit_civicrm_preProcess($formName, &$form) {

  BK_Utils::audit ('Preprocess: ' . $formName);
  BK_Utils::audit (print_r($form, TRUE));

}

function brisskit_civicrm_links( $op, $objectName, $objectId, &$links, &$mask, &$values ) {
  //http://br-civi-recruitment.cloudapp.net/civicrm/index.php?q=civicrm/recruitment

  BK_Utils::audit('@@@@@@@');
  BK_Utils::audit(print_r($links, TRUE));
  $current_q = $_REQUEST['q'];  // civicrm/recruitment
  $new_links = array();
  foreach ($links as $link) {
    if (isset($link['qs'])) {
      $old_qs = $link['qs'];
    }
    else {
      $old_qs = '';
    }
    $link['qs'] = $old_qs . '&bkref=' . $current_q;
    $new_links[] = $link;
  }
  
  $links = $new_links;
  
  return $new_links;
}


/* 
 *
 * Start of brisskit functionality proper
 *
 */ 


/**
 * Module containing core BRISSkit logic by implementing CiviCRM hook pre and post database writing.
 * within this logic it invokes BRISSkit specific hooks for particular stages of the study enrolment process
 * these include:
 *  
 *  - participant_available - once participant has passed the automatic/manual check that they are not deceased (ie. the Check participant is available activity is complete) and their status is Available
 *    - in this module a set of 'contact participant' activities are added including 'Positive reply received'
 *  
 *  - letter_response - once participant has responded positively to the letter (ie. the Positive reply received activity is complete)
 *    - in this module the participant stub is sent to the i2b2 web service
 *    - for the 2 consent modules (brisskit_tissue and brisskit_datacol) each creates a Consent to.. activity (provided the case implements that ActivityType) with status pending
 * 
 *  - consent_success - once participant has given 'Accepted' as response to particular Consent to.. activity
 *    - in the brisskit_tissue module this triggers the participant stub to be sent to the caTissue web service and the recording of this with a 'Data transfer' activity
 *    - in the brisskit_datacol module this triggers the creation of an 'Phone Call' activity to 'Organise data collection appointment with participant' 
 */

 include_once "CRM/Brisskit/BK_Core.php";

//function brisskit_civicrm_alterContent(  &$content, $context, $tplName, &$object ) {
function brisskit_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName) {
  BK_Utils::audit ('TPL name: ' . $tplName); 

// get all assigned template vars
/*
  global $smarty;
$all_tpl_vars = $smarty->get_template_vars();

// take a look at them
BK_Utils::audit (print_r($all_tpl_vars, TRUE));
*/

   BK_Utils::audit (print_r($form, TRUE));
}

/**
 * #implement civi's civicrm_buildForm hook
 * #prevent users from adding participants to cases unless they have permission to contact participant
 */
function brisskit_civicrm_buildForm($formName, &$form) {
  BK_Utils::audit ('Form name: ' . $formName); 

/*
  if ($formName == 'CRM_Contact_Form_Contact') {
  }
*/

  // The code from here is based on original drupal module
  if ($formName == 'CRM_Case_Form_Case') {
    if ($form->getAction() == CRM_Core_Action::ADD) {
    	if ($form->controller->_actionName) {
	    	$contact_id = $form->_currentlyViewedContactId;
	    	if (!$contact_id) return;
	        $contact = BK_Utils::get_contact_with_custom_values($contact_id);
	        
	        if (!isset($contact['permission_given']) || $contact['permission_given']==0) {
	        	BK_Utils::set_status("Sorry, a participant cannot be enrolled in any studies until 'Permission to contact participant' has been set to 'Yes'","error");
	        	drupal_goto("civicrm/contact/view",array("reset"=>1,"cid"=>$contact_id));
	        }
    	}
    }
  }
}

function _copy_family_id($contact_id_a, $contact_id_b, $contact_id) {
  //
  // Work out which way the relationship is so we know which direction to copy the family_id
  //
  if ($contact_id_a == $contact_id) {
    $contact_id_from = $contact_id_a;
    $contact_id_to = $contact_id_b;
  }
  else if ($contact_id_b == $contact_id) {
    $contact_id_to = $contact_id_a;
    $contact_id_from = $contact_id_b;
  }
  else {
     throw new Exception('Error copying family id');
  }

  try {
    $contact_from = BK_Utils::get_contact_with_custom_values($contact_id_from);
    $contact_to = BK_Utils::get_contact_with_custom_values($contact_id_to);
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::genomics_fields(), $contact, "Genomics Data");

    $custom_field_id = BK_Custom_Data::get_custom_field_id('family_id');
    $family_id = $contact_from['custom_' . $custom_field_id .'_1'];   # _1 is OK 

    BK_Utils::audit("$custom_field_id 'Contact', $contact_id_to, 'family_id', $family_id");
    BK_Custom_Data::create_custom_value('Contact', $contact_id_to, 'family_id', $family_id);
  }
  catch(Exception $ex) {
    BK_Utils::set_status($ex->getMessage(),"error");	
  }
}

function _create_family_id($contact_id, $params) {
  try {
    $contact = BK_Utils::get_contact_with_custom_values($contact_id);
    BK_Custom_Data::populate_custom_fields(BK_Custom_Data::genomics_fields(), $contact, "Genomics Data");
    $family_id = BK_Core::pseudo_family($params);
    BK_Custom_Data::create_custom_value('Contact', $contact_id, 'family_id', $family_id);
  }
  catch(Exception $ex) {
    BK_Utils::set_status($ex->getMessage(),"error");	
  }
}

/**
 * implement civi's civicrm_pre db write hook
 * 
 * 1) If a contact is being added to a case, increment the contact's recruitment count by 1
 * 2) Run old drupal code, for CiviRecruitment only
 */
function brisskit_civicrm_pre($op, $objectName, $id, &$params) {
  BK_Utils::audit("brisskit_civicrm_pre: $op, $objectName, $id, " .  print_r($params, TRUE) );

  /*
   *
   * Case
   * ====
   *
   * If a recruitment is being created update our read-only custom recruitment count field.
   *
  */
	if ($objectName=='Case') {
    $case_id = $id;
    
    if ($op==BK_Constants::ACTION_CREATE) {

      BK_Utils::audit("Is recruitment and creating - true");
      BK_Utils::audit("Parames are " . print_r($params, TRUE));

      $contactId = $params['client_id'][0];
      $case_type_id = $params['case_type_id'];

      // Currently we just use the built-in caseCount function.  Future versions may check participant status/activities completed before
      // setting this count.
      BK_Utils::audit("Before setting recruitment count");
      $result = BK_Custom_Data::set_recruitment_count ($contactId, CRM_Case_BAO_Case::caseCount($contactId, TRUE)+1);
      BK_Utils::audit("After setting recruitment count");

      BK_Temp::add_patient_to_group ($contactId, $case_type_id);
    }
    else if ($op==BK_Constants::ACTION_DELETE) {
      $contactId = BK_Utils::single_value_query("SELECT contact_id FROM civicrm_case_contact WHERE case_id = ?", $case_id);
      $case_type_id = BK_Utils::single_value_query("SELECT case_type_id FROM civicrm_case WHERE id = ?", $case_id);
      BK_Temp::remove_patient_from_group ($contactId, $case_type_id);
    }
  }
  /*
   *
   * CaseType
   * ========
   *
   */
  
	else if ($objectName=='CaseType') {
    if ($op == 'delete') {
			_case_type_delete($objectId);
    }
  }

  /*
   *
   * Individual
   * ==========
   *
   * Prevent Individuals being  added from anywhere other than the main Contacts->New screen.
   *
   */
  else if ($objectName=='Individual') {
    if ($op==BK_Constants::ACTION_CREATE) {
      BK_Utils::audit ("Indiv being created from URL".$params['entryURL']);
      $pos = strpos($params['entryURL'], 'contact/add');
      if ($pos === false) {
        $options = array();
        $options['expires']=0;
        $message = ts('Individuals can only be added via the Contact screen');
        CRM_Core_Session::setStatus($message, 'Add contact error', 'error', $options);
        CRM_Utils_JSON::output(array('status' => ($message) ? $oper : $message));
      }
    }

  }

	
  /*
   *
   * Individual or GroupContact
   * ==========================
   *
   */
	else if ($objectName=="GroupContact" || $objectName=='Individual') {
    // The code from here is based on original drupal module
    BK_Utils::audit ("pre hook op $op name $objectName");
    BK_Utils::audit ('params:'.print_r($params, TRUE));
    global $prev_stat_id;
    
    #if only viewing or deleting don't do anything
    if ($op=="view" || $op=="delete") return;

    if ($op=="create") {
      // _create_family_id($id, $params); Not done in post
    }

		#try/catch will produce a nice drupal style message if there is a problem
		try {
			#check whether contact has permission or not
			$permission = BK_Core::is_permission_given_to_contact($params);
			
			if ($permission) {
				# 5. If the permission flag has been set properly the individual is pseudonymised
				#if so then pseudonymise the individual
				$bkid = BK_Core::pseudo_individual($params);
				BK_Utils::set_status("Permission to contact the individual was given by GP/clinician - participant has now been pseudonymised (ID:".$bkid.")");
			}
			if ($op=="edit") {
				 ###axa20151207 fix wsod caused by calling get_contact_with_custom_values() without class prefix BK_Utils::
         $contact = BK_Utils::get_contact_with_custom_values($params['contact_id']);
				 $prev_stat_id = isset($contact['status']) ? $contact['status'] : null;
			}
			
		}
		catch(Exception $ex) {
			BK_Utils::set_status($ex->getMessage(),"error");	
		}
	}

  /*
   *
   * Activity
   * ========
   *
   */
	else if ($objectName=='Activity') {
	  global $triggered;

		#try/catch will produce a nice drupal style message if there is a problem

		try {
      //
			// check if activity has already had workflow triggered
      //
			if (BK_Custom_Data::is_triggered($params)) return;
			
      //
			// check if contact has been added to case type previously (result of 'Open Case' activity)
      //
      $case_type_name = BK_Core::is_added_to_duplicate_case($op, $_POST['case_type_id'], $params['activity_type_id'], $params['target_contact_id'], $params['case_id']);
			
			if ($case_type_name) {
				BK_Utils::set_status("Sorry you can only add a contact to a study once. This contact has already been added to the '$case_type_name' Study", "error");
				drupal_goto("civicrm/contact/view",array("reset"=>1, "cid"=>$params['target_contact_id']));
			}
			
      //
			// check if participant available has just been set and if so, invoke the BRISSkit 'participant_available' hook
      //
			if (BK_Core::is_participant_available($params)) {
				$results = module_invoke_all("participant_available",$params,$id);
				$triggered = BK_Utils::check_results($results);
			}
      //
			// if participant has just replied invoke the BRISSkit 'letter_response' hook
      //
			if (BK_Core::is_participant_reply_positive($params)) {
				BK_Utils::set_status("Potential participant replied");
				$results = module_invoke_all("letter_response",$params);
				$triggered = BK_Utils::check_results($results);
			}
			
      //
			// if consent was given for this Activity (ie. status is Accepted)
      //
			if (BK_Core::is_consent_level_accepted($params)) {
        //
				// check that the ActivityType is part of the case definition if not exit hook
        //
				$activity_type = BK_Utils::get_activity_type_name($params['activity_type_id']);

        //
        // Tell the user whats happened
        //
				if (!BK_Core::case_allows_activity($params['case_id'], $activity_type)) {
          BK_Utils::set_status("Case does not allow this activity");
          return;
        }
				BK_Utils::set_status("'$activity_type' was Accepted");
				
        //
				// invoke the BRISSkit 'consent_success' hook
        //
				$results = module_invoke_all("consent_success", $activity_type, $params);
				$triggered = BK_Utils::check_results($results);
				
			}
		}
		catch(Exception $ex) {
			BK_Utils::set_status($ex->getMessage(),"error");	
		}
	}
}

function _case_type_created($objectId, &$objectRef) {
  $new_case_type_id = $objectId;  
  BK_Temp::create_contact_groups_for_study($new_case_type_id);
}

function _case_type_deleted($objectId) {
}

function _case_type_delete($objectId) {
}

function brisskit_civicrm_post( $op, $objectName, $objectId, &$objectRef ) {
  global $study_created_flag; // Set when we create a study

  BK_Utils::audit("brisskit_civicrm_post: $op, $objectName, $objectId");
  if ($objectName=="CaseType") {
    if ($op == 'create') {
			BK_Utils::audit(__FUNCTION__ . print_r($objectRef, TRUE));
			_case_type_created($objectId, $objectRef);
    }

    else if ($op == 'delete') {
			_case_type_deleted($objectId);
    }
  }

  // The code from here is based on original drupal module
	global $prev_stat_id;
	if ($objectName=='Individual') {
    if (!empty($prev_stat_id)) {
      BK_Core::log_status_if_required($objectId,$op,$prev_stat_id);
    }
	}

	if ($objectName=="GroupContact" || $objectName=='Individual') {
    if ($op=="create") {
      _create_family_id($objectId, array());
    }
  }

	if ($objectName=="Relationship") {
    if ($op=="create") {

      BK_Utils::audit(print_r($_POST, TRUE));
      BK_Utils::audit(print_r($_GET, TRUE));
      BK_Utils::audit(print_r($objectRef, TRUE));

      //
      // We use the contact_id from the GET request, the contact we're copying from
      // The contact(s) we're copying to is in the $_POST, a comma-separated list of ids
      //
      # _copy_family_id($objectRef->contact_id_a, $objectRef->contact_id_b, $_POST['related_contact_id']);
      _copy_family_id($objectRef->contact_id_a, $objectRef->contact_id_b, $_GET['cid']);
    }
  }
	
	if ($objectName=="GroupContact") {
	}
	
	#when work flow has been triggered need to set the wf_trigger flag to 1
	global $triggered;
	if ($triggered) {
		set_activity_triggered($objectId);
		$triggered=false;
	}
}

#implement BRISSkit participant available to be contacted hook
# 3.1. Automatically creates activities defined in the contact_participant ActivitySet of the civicase XML 
function brisskit_participant_available($params, $activity_id) {

  // The code from here is based on original drupal module
	#add activities related to contacting participant to case
	
	# 3.2. Changes participant status to ÔIn studyÕ
	if (BK_Core::add_activity_set_to_case($params['case_id'],"contact_participant",$params['source_contact_id'])) {
		$case_id = $params['case_id'];
		$case_type = BK_Utils::get_case_type($case_id);
		BK_Utils::set_contact_status_via_case($case_id, "In study","Status changed to 'In study' ($case_type) when availability confirmed.");

		BK_Utils::set_status("Activities now scheduled to contact potential participant re. study enrolment");
		BK_Utils::set_status("Participant status changed to 'In study'");
		return true;   
	}
}

#implement civi's civicrm_import hook (called following import of each individual)
function brisskit_civicrm_import( $object, $usage, &$objectRef, &$params ) {
  // The code from here is based on original drupal module
	require_once "api/v3/utils.php";
	require_once "api/v3/Case.php";
	
	#determine if an initial study has been supplied in the import fields and if so add the participant to that initial study
	if (BK_Core::is_participant_in_initial_study($params)) {
		BK_Custom_Data::set_custom_field("permission_given",1,$params);
		BK_Core::add_participant_to_initial_study($params);
	}
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @param $params array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 * We are going to add Recruitment to the navigation menu.  Note that routing is taken
 * care of by the xmlMenu hook.  This hook only adds items in memory - it does not write 
 * to the database.  Consequently, these items cannot be edited via the Administer GUI.
 */
function brisskit_civicrm_navigationMenu(&$params) {
  // We'll add two sub menus, one for recruitments and one for studies
  // Passing name (unique), label, plural label
  //
  // These will appear after the normal position of the 'Cases' menu, in reverse order in which they are added
  // Note that the name affects the url so must match the values in Case.xml
  //
  // _add_menu($params, 'recruitment', 'Recruitment', 'Recruitments');
}

function _add_menu(&$params, $name, $label, $plural_label) {

  // Have we already added menu with this name?
  $menu_item_search = array('url' => "civicrm/$name");
  $menu_items = array();
  CRM_Core_BAO_Navigation::retrieve($menu_item_search, $menu_items);
 
  if ( ! empty($menu_items) ) { 
    return;  //already added, return
  }

  /*
    Build our own compact array for the new item(s).
    This allows us to define only the elements we're going to change &
    makes it easy to add other nav menu entries in the future (e.g. Study
    if we don't want to hijack Case).
  */
  $bk_menu_items[]= array 
    (
      'label' => $label,
      'name' => $label,
      'url' =>null, 
      'permission' => _getNavigationPermission ($name),
      'child' => array 
       (
        'label' => 'Dashboard',
        'name' => 'Dashboard',
        'url' => "civicrm/$name",
        'permission' => _getNavigationPermission ($name),
       ),
      'child2' => array 
       (
        'label' => "New $label",
        'name' => "New $label",
        'url' => "civicrm/$name/add?reset=1&action=add&context=standalone",
        'permission' => _getNavigationPermission ($name),
       ),
      'child3' => array 
       (
        'label' => "Find $plural_label",
        'name' => "Find $plural_label",
        'url' => "civicrm/$name/search?reset=1",
        'permission' => _getNavigationPermission ($name),
       )
  );

  // Call our helper function to do the actual add
  #$params=_build_menu_items ($bk_menu_items, $params);
  _build_menu_items ($bk_menu_items, $params);
}

/**
 * Helper function to build actual data structure used by navigationMenu.
 *
 * @param $bk_menu_items (array), $params array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function _build_menu_items ($bk_menu_items, &$params) {
  BK_Utils::audit ('params:'.print_r($params, TRUE));

  //  Get the maximum key of $params
  $maxKey = _getMenuKeyMax($params);

  if (!is_integer($maxKey)) {
    return $params;
  }

  $new_params = array();

  // These are tyhe elements we will add top the original params
  // Loop through items our caller wants added & build up the data structure.
  foreach ($bk_menu_items as $bk_menu) {
    $maxKey++;
    $new_params[$maxKey] = array (
      'attributes' => array (
          'label' => $bk_menu['label'],
          'name' => $bk_menu['name'],
          'url' =>$bk_menu['url'], 
          'permission' => $bk_menu['permission'],
          'operator' => 'OR',
          'separator' => 0,
          'parentID' =>null,
          'navID' => $maxKey,
          'active' => 1
        ),
      'child' => array
        (
          '1' => array
            (
              'attributes' => array
                (
                  'label' => $bk_menu['child']['label'],
                  'name' => $bk_menu['child']['name'],
                  'url' => $bk_menu['child']['url'],
                  'permission' => $bk_menu['child']['permission'],
                  'operator' => 'OR',
                  'separator' => 0,
                  'parentID' => $maxKey,
                  'navID' => 1,
                  'active' => 1
                ),

              'child' =>null 
            ),
          '2' => array
            (
              'attributes' => array
                (
                  'label' => $bk_menu['child2']['label'],
                  'name' => $bk_menu['child2']['name'],
                  'url' => $bk_menu['child2']['url'],
                  'permission' => $bk_menu['child2']['permission'],
                  'operator' => 'OR',
                  'separator' => 0,
                  'parentID' => $maxKey,
                  'navID' => 1,
                  'active' => 1
                ),

              'child' =>null 
            ),
          '3' => array
            (
              'attributes' => array
                (
                  'label' => $bk_menu['child3']['label'],
                  'name' => $bk_menu['child3']['name'],
                  'url' => $bk_menu['child3']['url'],
                  'permission' => $bk_menu['child3']['permission'],
                  'operator' => 'OR',
                  'separator' => 0,
                  'parentID' => $maxKey,
                  'navID' => 1,
                  'active' => 1
                ),

              'child' =>null 
            )
        )
    );
  }

  // Find the position of Cases item 
  // Params[]['attributes']['name'] == 'Cases'

  $idx = 0;
  foreach ($params as $key=>$value) {
    if ($value['attributes']['name'] == 'Cases') {
      break;
    }
    $idx++;
  }

  $idx++;

  $params = array_merge ( array_slice($params, 0, $idx, true), 
                          $new_params,
                          array_slice($params, $idx, count($params)-$idx, true));

  BK_Utils::audit (print_r($params, TRUE));
  # return $params;
}

/**
 * Helper function to find next available key in navigation menu array.
 *
 * @param $menuArray array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function _getMenuKeyMax($menuArray) {
  $max = array(max(array_keys($menuArray)));
  foreach($menuArray as $v) { 
    if (!empty($v['child'])) {
      $max[] = _getMenuKeyMax($v['child']); 
    }
  }
  return max($max);
}

/**
 * Helper function to return permission strings used in navigation menu array.
 *
 * @param $research_type (string) either recruitment or study
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function _getNavigationPermission ($research_type) {
  return 'access my cases and activities,access all cases and activities';
/* TODO
  if ($research_type=='recruitment') {
    return 'access my recruitments and activities,access all recruitments and activities';
  }
  else {
    return 'access my cases and activities,access all cases and activities';
  }
*/
}

function brisskit_civicrm_queryObjects(&$queryObjects, $type) {
  BK_Utils::audit ("queryObj hook");
  BK_Utils::audit ('qo:'.print_r($queryObjects, TRUE));
  BK_Utils::audit ('type:'.print_r($type, TRUE));

}

/**
 * Implements hook_civicrm_permission().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_permission
 * 
 * Note: $permissions array changed for versions >=4.6.  Here we handle 
 * <4.6 one way and >4.6 the new way. Note: will not deal with <4.3.
 */
function brisskit_civicrm_permission(&$permissions) {
  // Firstly call the API to get our version number
  $version='';
  $result = civicrm_api3('Domain', 'get', array(
    'sequential' => 1,
    'return' => "version",
    'id' => 1,
  ));

  if ($result['count']==1) {
    $version=$result['values'][0]['version'];
  }

  // If we didn't get a version, do it the <4.6 way.
  if (empty($version)) {
    $permissions= _pre46_permissions($permissions);
    return;
  }

  // If major version is < 4 or minor version < 6 do it the old way
  $versioncomponents = explode('.', $version);
  if (($versioncomponents[0] < 4)
  || ($versioncomponents[1] < 6)) {
    $permissions= _pre46_permissions($permissions);
    return;
  }

  // Here major version is >= 4 and minor version >=6
  $permissions = array(
    'delete in custom_CiviRecruitment' => array(
      ts('delete in custom_CiviRecruitment'),
      ts('Delete custom_recruitments'),
    ),
    'administer custom_CiviRecruitment' => array(
      ts('administer custom_ CiviRecruitment'),
    ),
    'access my custom_recruitments and activities' => array(
      ts('access my custom_recruitments and activities'),
    ),
    'access all custom_recruitments and activities' => array(
      ts('access all custom_recruitments and activities'),
    ),
    'add custom_recruitments' => array(
      ts('add custom_recruitments'),
    ),
  );
}

/* this is the < 4.6 way of setting permissions */
function _pre46_permissions ($permissions) {
  $prefix = ts('CiviCRM Recruitment') . ': '; // name of extension or module
  $permissions['add Recruitments']        = $prefix . ts('add recruitments');
  $permissions['administer Recruitment']  = $prefix . ts('administer recruitment');
  $permissions['access my recruitments and activities'] = $prefix . ts('access my recruitments and activities');
  $permissions['access all recruitments and activities'] = $prefix . ts('access all recruitments and activities');
  $permissions['delete in Recruitment']   = $prefix . ts('delete recruitment');
  return $permissions;
}

function brisskit_civicrm_getCaseActivity ($caseID, &$params, $contactID, $context, $userID) {
  BK_Utils::audit ("getCaseActivity hook case $caseID, contact $contactID, user $userID context $context |");
}

/*
  TODO rewrite using roles and proper ACLs for now check they're in the 
  'View Case Activities Group' before granting access to activities.
*/
function brisskit_civicrm_control_access ($contactID, $userID, $access) {
  global $user;

  $contact = civicrm_api3('UFMatch', 'get', array(
    'sequential' => 1,
    'return' => "contact_id",
    'uf_id' => $user->uid,
  ));

  $groups = civicrm_api3('GroupContact', 'get', array(
    'sequential' => 1,
    'contact_id' => $contact['values'][0]['contact_id'],
  ));


  if ($groups['count'] > 0) {
    foreach ($groups['values'] as $group_value_set) {
      if (check_access ($group_value_set['title']) ) {
        return TRUE;
      }
    }
    #self::activityForm($this, $aTypes);
  }
  return FALSE;
}

function check_access ($group) {
  if ($group=='View Case Activities Group') {
    return TRUE;
  }
  else {
    return FALSE;
  }
}


# Adapted from https://civicrm.org/blogs/colemanw/create-your-own-tokens-fun-and-profit
function  brisskit_civicrm_tokens(&$tokens) {
  $tokens['date'] = array(
    'date.date_short' => 'dd/mm/yyyy',
    'date.date_med' => 'd Mon yyyy',
    'date.date_long' => 'dth Month yyyy',
  );
}

function brisskit_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  if (!empty($tokens['date'])) {
    $date = array(
      'date.date_short' => date('d/m/Y'),
      'date.date_med' => date('j M Y'),
      'date.date_long' => date('jS F Y'),
    );
    foreach ($cids as $cid) {
      $values[$cid] = empty($values[$cid]) ? $date : $values[$cid] + $date;
    }
  }
}
