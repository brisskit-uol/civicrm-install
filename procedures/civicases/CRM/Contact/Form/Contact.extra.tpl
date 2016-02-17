{literal}

//TODO Need to disable phone fields in a "nice" way. Perhaps do the whole section??

<style>
  input[readonly] {
    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, color-stop(1%, #ddd), color-stop(15%, #ccf)) !important;
    background-image: -webkit-linear-gradient(top, #ddd 1%, #ccf 15%) !important;
    background-image: -moz-linear-gradient(top, #ddd 1%, #ccf 15%) !important;
    background-image: linear-gradient(top, #ddd 1%, #ccf 15%) !important;
  }
</style>


<script>
  var jsonFieldIds = [
    'first_name',
    'last_name',
    'prefix_id',
    'select2-choice',
    'is_deceased',
    'job_title',
    'phone_1_phone',
    'phone_2_phone',
    'address_1_street_address',
    'address_1_supplemental_address_1',
    'address_1_city',
    'address_1_postal_code',
    'civicrm_gender_Female_1',
    'civicrm_gender_Male_2',
    'civicrm_gender_Transgender_3',
    'birth_date',
    'custom_11_-1',
    'custom_12_-1'
  ];

  var bk_search_widget = ` 
    <div id="bk_search_widget">
      <label for="bk_search_name">Enter SNumber : </label>
      <input type="text" name="bk_search_name" id="bk_search_name" />
      <button type="button" id="bk_search_button">Populate name</button>
      <a href="https://patientdemographicservicedev.xuhl-tr.nhs.uk/login.aspx" target="_blank">Register Patient</a>
    </div>
  `;

  jQuery( document ).ready(function() {
    // Insert our search widget
    if (jQuery('#bk_search_widget').length == 0) {
      jQuery('#contactDetails').prepend(bk_search_widget);

      // Copy the patient details to the contact
      jQuery('#bk_search_button').click(function () {
        jQuery.ajax({
            url:"http://www.h2ss.co.uk/h2ss/pmi?callback=jsonCallback&snumber=" + jQuery( "#bk_search_name" ).val() + "&surname=hgf",
            dataType: 'jsonp' // Notice! JSONP <-- P (lowercase)  
        });
      });
      displayAdditionalPhoneFields(1); // Create one extra phone input block so total = 2
    }
    disableFormFields ();
  });
</script>
 


<script>

  function displayAdditionalPhoneFields (phone_count) {
    for (i=0; i<phone_count; i++) {
      buildAdditionalBlocks( 'Phone', 'CRM_Contact_Form_Contact');
    }
  }

function disableFormFields () {
  // Fields we don't want the user to change
  // Can still be changed by our json callback
  // jQuery('#first_name').attr('readonly', 'readonly'); 

  for (var i in jsonFieldIds) {
    var fieldId = jsonFieldIds[i];
    jQuery('#' + fieldId).attr('readonly', 'readonly'); 
  }
}

function jsonCallback(data)
{
  if (data.is_patient_found == 'N') { alert("patient not found")}

  // clear fields
  jQuery('#first_name').val('');
  jQuery('#last_name').val('');
  jQuery('#prefix_id option[value=0]').attr('selected','selected');
  jQuery('#select2-choice').html('');
  jQuery('#select2-chosen-3').text('');
  jQuery('#is_deceased').attr('checked', false);
  jQuery('#job_title').val('');
  jQuery('#phone_1_phone').val('');
  jQuery('#phone_2_phone').val('');
  jQuery('#address_1_street_address').val('');
  jQuery('#address_1_supplemental_address_1').val('');
  jQuery('#address_1_city').val('');
  jQuery('#address_1_postal_code').val('');
  jQuery('#civicrm_gender_Female_1').attr('checked', false);
  jQuery('#civicrm_gender_Male_2').attr('checked', false);  
  jQuery('#civicrm_gender_Transgender_3').attr('checked', false);
  jQuery('#birth_date').val('');
  jQuery('#custom_11_-1').val('');
  jQuery('#custom_12_-1').val('');
    
  // populate fields
  jQuery('#first_name').val(data.forenames);
  jQuery('#last_name').val(data.surname);
  
  if (data.title == 'MRS')
  {
    jQuery('#prefix_id option[value=1]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Mrs.');
  }
  if (data.title == 'MISS')
  {
    jQuery('#prefix_id option[value=2]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Ms.');
  }
  if (data.title == 'MR')
  {
    jQuery('#prefix_id option[value=3]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Mr.');
  }
  if (data.title == 'MSTR')
  {
    jQuery('#prefix_id option[value=3]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Mr.');
  } 
  if (data.title == 'DR')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Dr.');
  }
  
  if (data.deceased == '0')
  {
    jQuery('#is_deceased').attr('checked', false);  
  }
  
  if (data.deceased == '1')
  {
    jQuery('#is_deceased').attr('checked', true); 
  }
  
  jQuery('#job_title').val(data.patient_occupation);
  
  jQuery('#phone_1_phone').val(data.telephone_number1);
  jQuery('#phone_2_phone').val(data.telephone_number2);
  
  jQuery('#address_1_street_address').val(data.address_line_1);
  jQuery('#address_1_supplemental_address_1').val(data.address_line_2);
  jQuery('#address_1_city').val(data.address_line_3);
  jQuery('#address_1_postal_code').val(data.postcode);
  
  if (data.sex == 'Female')
  {
  jQuery('#civicrm_gender_Female_1').attr('checked', true);
  }
  
  if (data.sex == 'Male')
  {
  jQuery('#civicrm_gender_Male_2').attr('checked', true);
  }
  
  if (data.sex == 'Transgender')
  {
  jQuery('#civicrm_gender_Transgender_3').attr('checked', true);
  }
  
  if (data.day != '' && data.month != '' && data.year != '')
  {
    jQuery('#birth_date_display').val(data.day + '/' + data.month + '/' + data.year);   
  }
  
  jQuery('#custom_11_-1').val(data.system_number);
  jQuery('#custom_12_-1').val(data.nhs_number);
  
}

</script>


{/literal}
