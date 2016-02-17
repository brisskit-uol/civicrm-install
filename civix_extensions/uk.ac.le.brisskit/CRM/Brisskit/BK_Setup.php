<?php
require_once 'CRM/Brisskit/BK_Core.php';
require_once 'CRM/Brisskit/BK_Custom_Data.php';

class BK_Setup {
  static function init_required_fields() {

    // BK_Custom_Data::init_custom_data(); TODO
    BK_Utils::update_template_dir(BK_Constants::CASE_LOCATION);
  }
}
