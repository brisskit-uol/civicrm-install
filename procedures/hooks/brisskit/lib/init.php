<?php

#init_drupal();
require_once "core.php";
require_once "make_brisskit_id.php";

function init_drupal() {
	include_once 'drupal_setup.php';
	civicrm_initialize();
	error_log("Initialised successfully");
}

function init_required_fields() {
	$og = create_civi_option_group(array("name" => "current_status_12345", "label" => "Current status", "is_active"=>1,"title" => "Current status","default"=>3));
	create_civi_option_value("current_status_12345", array("label" => CONTACT_STATUS_NOTAVAILABLE, "name" => CONTACT_STATUS_NOTAVAILABLE, "is_active"=>1,"value"=>1));
	create_civi_option_value("current_status_12345", array("label" => CONTACT_STATUS_DECEASED, "name" => CONTACT_STATUS_DECEASED, "is_active"=>1,"value"=>2));
	create_civi_option_value("current_status_12345", array("label" => CONTACT_STATUS_AVAILABLE, "name" => CONTACT_STATUS_AVAILABLE, "is_active"=>1,"value"=>3));
	create_civi_option_value("current_status_12345", array("label" => CONTACT_STATUS_INSTUDY, "name" => CONTACT_STATUS_INSTUDY, "is_active"=>1,"value"=>4));
	
	$cg = create_civi_custom_group(array("title" => "Permission", "extends" => "Contact","is_active"=>1,"style"=>"Inline"));

	
	#set is active and is view for some and weight (order) of fields
	create_civi_custom_field($cg, array("weight"=>1, "label" => "Permission to contact", "data_type" => "Boolean", "html_type" => "Radio","is_active"=>1));
	create_civi_custom_field($cg, array("weight"=>2, "label" => "BRISSkit ID", "data_type" => "String", "html_type" => "Text","is_active"=>1));
	create_civi_custom_field($cg, array("weight"=>3, "label" => "Date Permission Given", "data_type" => "Date", "html_type" => "Text","is_active"=>1));
	create_civi_custom_field($cg, array("weight"=>4, "label" => "Comments", "data_type" => "Memo", "html_type" => "TextArea","is_active"=>1,"note_columns"=>80, "note_rows"=>4));
	
	
	$cg = create_civi_custom_group(array("title" => "Participant Status", "extends" => "Contact","is_active"=>1,"style"=>"Inline"));
	create_civi_custom_field($cg, array("label" => "Initial study", "data_type" => "String", "html_type" => "Text","is_active"=>1));
	create_civi_custom_field($cg, array("name" => "Status log", "label" => "Status log", "default_value"=>null, "data_type" => "String", "html_type" => "Textarea","is_active"=>1,"column_name"=>"current_status","option_group_id"=>$og['id']));
	create_civi_custom_field($cg, array("name" => "Current status", "label" => "Current status", "default_value"=>3, "data_type" => "String", "html_type" => "Select","is_active"=>1,"column_name"=>"current_status","option_group_id"=>$og['id']));
	
	$cg = create_civi_custom_group(array("title" => "Workflow", "extends" => "Activity","is_active"=>1,"style"=>"Inline","collapse_display"=>1));
	create_civi_custom_field($cg, array("name" => "workflow_triggered", "label" => "Workflow triggered", "data_type" => "Boolean", "html_type" => "Radio","is_active"=>1, "is_view"=>1));

	create_civi_option_value("activity_type", array("name" => ACTIVITY_CHECK_STATUS, "label" => ACTIVITY_CHECK_STATUS, "is_active"=>1));
	create_civi_option_value("activity_type", array("name" => ACTIVITY_POSITIVE_REPLY, "label" => ACTIVITY_POSITIVE_REPLY, "is_active"=>1));
	create_civi_option_value("activity_type", array("name" => ACTIVITY_DATA_TRANSFER, "label" => ACTIVITY_DATA_TRANSFER, "is_active"=>1));
	
	create_civi_option_value("activity_status", array("name" => ACT_STATUS_PENDING,"label" => ACT_STATUS_PENDING, "is_active"=>1));
	create_civi_option_value("activity_status", array("name" => ACT_STATUS_ACCEPTED, "label" => ACT_STATUS_ACCEPTED, "is_active"=>1));
	create_civi_option_value("activity_status", array("name" => ACT_STATUS_REJECTED, "label" => ACT_STATUS_REJECTED, "is_active"=>1));
	create_civi_option_value("activity_status", array("name" => ACT_STATUS_FAILED, "label" => ACT_STATUS_FAILED, "is_active"=>1));
	
	create_civi_option_value("case_type", array("name" => DEMO_CASE_TYPE, "label" => DEMO_CASE_TYPE, "is_active"=>1));
	
	update_template_dir(CASE_LOCATION);
}

