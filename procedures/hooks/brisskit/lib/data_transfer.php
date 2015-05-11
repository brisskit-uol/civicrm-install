<?php
require_once "init.php";
require_once "core.php";
require_once "integration.php";
require_once "constants.php";

#am I already running?
$cmd    =    'ps aux | grep "data_transfer.php" | grep -v "grep" | wc -l';
$result   =   (integer)exec($cmd);
error_log($result);

if ($result>2) { 
	error_log("The scheduled data transfer script is already running!"); exit;
}

date_default_timezone_set('UTC');

init_drupal();
$acs = get_scheduled_dt_activity_info();
foreach ($acs as $ac) {
	error_log("data transfer target:".$ac['target']."\n");
	$details = "";
	$cancelled = false;

	if ($ac['target']=="undefined") {
		$details = isset($ac['activity']['details']) ? $ac['activity']['details']."\n" :"";
		$ac['activity']['details']=$details.date(DATE_ATOM)." - the type of transfer should be defined in the subject as 'Transfer to X'";
		$ac['activity']['status']="Cancelled";
		$cancelled=true;
	}
	else {
		$details = isset($ac['activity']['details']) ? $ac['activity']['details']."\n" :"";
                $ac['activity']['details']=$details.date(DATE_ATOM)." - contact details sent to ". $ac['target'];
                $ac['activity']['status']="Pending";
	}
	#set date to null to prevent date resetting to 0 (no doesn't make sense to me either!)
	$ac['activity']['activity_date_time']=null;
 	# 3. Updates the activity status to Pending and adds a log to the activity ÔDetailsÕ
	update_activity($ac['activity']);
	
	if ($cancelled) continue;	
	
	# 5. Sends PDO and the activity_id to the appropriate web-service
	try {
		$method = "post_contact_to_".$ac['target'];
		$method($ac['contact'],$ac['activity']['id']);
	}
	catch(Exception $ex) {
		$new_ac = $ac['activity'];
		$ac['activity']['details']=$details.date(DATE_ATOM)." - problem contacting ".$ac['target']." (".$ex->getMessage().")";
		$ac['activity']['status']="Unreachable";
		update_activity($ac['activity']);
		$new_ac['case_id'] = $ac['case_id'];
		$new_ac['activity_type']=ACTIVITY_DATA_TRANSFER;
		$new_ac['activity_date_time']=date(DATE_FORMAT_ISO);
		$new_ac['status']="Scheduled";
		$new_ac['id']=null;
		$new_ac['ignore_dups']=true;

		add_activity_to_case($new_ac);
	}
}

function get_scheduled_dt_activity_info() {
	include "api/v3/Activity.php";
	include "api/v3/utils.php";
	include "CRM/Case/BAO/Case.php";

	$atid = get_option_group_value("activity_type",ACTIVITY_DATA_TRANSFER);
	$asid = get_option_group_value("activity_status",ACT_STATUS_SCHEDULED);
	error_log("atid:$atid, asid:$asid");
	$params = array('activity_type_id'=>$atid, 'status_id'=>$asid, 'is_deleted' => '0');
	$acts_json = civicrm_api3_activity_get($params);
	if ($acts_json['count']==0) {
		return array();
	}
	
	$acts = $acts_json['values'];
	$acs = array();
	foreach ($acts as $id => $act) {
		$case_id = CRM_Case_BAO_Case::getCaseIdByActivityId($id);
		# 1. Extracts the case details, and the associated participant
		$contact = get_case_contact_with_custom_values($case_id);
		
		# 2. Determines the target by using the text in the activity subject (e.g. Transfer to i2b2 or Transfer to caTissue)
		$subject = $act['subject'];
		preg_match('/[t|T]ransfer to (\w+)/',$subject,$matches);

		$target = strtolower(isset($matches[1]) ? $matches[1] : "undefined");

		$acs[]=array('activity'=>$act, 'contact'=>$contact, 'case_id'=>$case_id, 'target'=>$target);
	}
	return $acs; 
}
