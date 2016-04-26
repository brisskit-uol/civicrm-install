{literal}

<style>
  input[readonly] {
    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, color-stop(1%, #ddd), color-stop(15%, #ccf)) !important;
    background-image: -webkit-linear-gradient(top, #ddd 1%, #ccf 15%) !important;
    background-image: -moz-linear-gradient(top, #ddd 1%, #ccf 15%) !important;
    background-image: linear-gradient(top, #ddd 1%, #ccf 15%) !important;
  }

  /* The Modal (background) */
  .modalsaj {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }

  /* modalsaj Content */
  .modalsaj-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
  }

  /* The Close Button */
  .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
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
    'birth_date'
  ];

  var jsonFieldsCustomIds = [
    'Genomics_Data:Family_ID',
    'Genomics_Data:Gel_Participant_ID',
    'Genomics_Data:S_Number',
    'Genomics_Data:NHS_Number'
  ];
    
  var bk_search_widget = ` 

    <label id="mySaveMessage" style="font-family: 'Arial Bold'; font-size: 18px; color: red;"></label>

    <div id="bk_search_widget">
      <!--<label for="bk_search_name">Enter SNumber : </label>
      <input type="text" name="bk_search_name" id="bk_search_name" />
      <button type="button" id="bk_search_button">Populate name</button> -->     
    </div>

    <button type="button" id="myBtn">Search PMI</button> 
    <a href="https://patientdemographicservicedev.xuhl-tr.nhs.uk/login.aspx" target="_blank">Register Patient</a>
    <div id="myModal" class="modalsaj">
      <!-- Modal content -->
      <div class="modalsaj-content">
        <span id="myModalClose" class="close" style="font-family: 'Arial Bold';	font-size: 24px;">close</span>

        <table style="width:80%">
          <tr>
	    <td colspan="9">
            PMI Search. Enter any values and click on search.
            </td> 
          </tr>      
          <tr>
            <td align="center" width="10%" style="text-align:center; border-left: medium solid; border-top: medium solid; border-bottom: medium solid; border-right: medium solid;">
              <label for="bk_snumber">SNumber</label>
              <input type="text" name="bk_snumber" id="bk_snumber" maxlength="12" size="12"   />
            </td>
            <td width="5%" style="text-align:center; background-color:black;">
	    <FONT COLOR="white">OR</FONT>
            </td>
            <td width="10%" style="text-align:center; border-left: medium solid; border-top: medium solid; border-bottom: medium solid; border-right: medium solid;">
              <label for="bk_nhsnumber">NHS Number</label>
              <input type="text" name="bk_nhsnumber" id="bk_nhsnumber" maxlength="12" size="12" />
            </td>
            <td width="5%" style="text-align:center; background-color:black;">
            <FONT COLOR="white">OR</FONT>
            </td>
            <td width="10%" style="text-align:center; border-left: medium solid; border-top: medium solid; border-bottom: medium solid;">
              <label for="bk_surname">Surname</label>
              <input type="text" name="bk_surname" id="bk_surname" maxlength="25" size="12" />
            </td>
            <td width="10%" style="text-align:center; border-top: medium solid; border-bottom: medium solid;">
              <label for="bk_forenames">Forenames</label>
              <input type="text" name="bk_forenames" id="bk_forenames" maxlength="25" size="12" />
            </td>
            <td width="10%" style="text-align:center; border-top: medium solid; border-bottom: medium solid;">
              <label for="bk_dob">DOB dd/mm/yyyy</label>
              <input type="text" name="bk_dob" id="bk_dob" maxlength="10" size="13" />
            </td>
            <td width="10%" style="text-align:center; border-top: medium solid; border-bottom: medium solid;">
              <label for="bk_postcode">Postcode</label>
              <input type="text" name="bk_postcode" id="bk_postcode" maxlength="8" size="8" />
            </td>
            <td width="10%" style="text-align:center; border-top: medium solid; border-bottom: medium solid; border-right: medium solid;">
              <label for="bk_address1">First line of address</label>
              <input type="text" name="bk_address1" id="bk_address1" maxlength="30" size="30" />
            </td>
<!--
            <td width="10%" style="text-align:center; border-top: medium solid; border-bottom: medium solid; border-right: medium solid;">
              <label for="bk_telephone1">Home telephone</label>
              <input type="text" name="bk_telephone1" id="bk_telephone1" maxlength="20" size="12" />
            </td> 
-->
            <td width="10%">
               <button type="button" id="myClearAll" style="height:100%">Clear All</button>
            </td>                  
           </tr>
           <tr>
            <td colspan="4">
              <button type="button" id="mySearch">Search</button>      
            </td>
           </tr>
        </table>

      <div id="mySearchResultsDiv" style='height:390px; overflow:scroll;'>


       <label id="mySearchResultsMessage"></label>










        <table style="width:100%" id="mySearchResults"></table>
      </div>           
      </div>
    </div>
  `;

  jQuery( document ).ready(function() {

/*
  jQuery("#Contact").submit(function(e){
   alert('submit intercepted');
   var val = jQuery("input[type=submit][clicked=true]").val();
   var btn = jQuery(this).find("input[type=submit]:focus" ).val();
   alert(btn + 'submit intercepted ' + val);
   e.preventDefault(e);
  });
*/

  jQuery('#_qf_Contact_upload_view-top').click(function (e) {
    //alert("Submit - Save");
    //event.preventDefault();


    if ( jQuery("input[data-crm-custom=Genomics_Data:S_Number]").val() == '')
    {
      jQuery('#mySaveMessage').html("&nbsp;&nbsp;  SNumber cannot be blank." + jQuery("input[data-crm-custom=Genomics_Data:S_Number]").val());
      e.preventDefault(e);
    }
  });

  jQuery('#_qf_Contact_upload_new-top').click(function (e) {
    //alert("Submit - Save and New");
    //event.preventDefault();

    if ( jQuery("input[data-crm-custom=Genomics_Data:S_Number]").val() == '')
    {
      jQuery('#mySaveMessage').html("&nbsp;&nbsp;  SNumber cannot be blank." + jQuery("input[data-crm-custom=Genomics_Data:S_Number]").val());
      e.preventDefault(e);
    }
  });




    // Insert our search widget
    if (jQuery('#bk_search_widget').length == 0) {
      jQuery('#contactDetails').prepend(bk_search_widget);
      

      // Copy the patient details to the contact
      jQuery('#bk_search_button').click(function () {

	/*
        jQuery.ajax({
            url:"http://www.h2ss.co.uk/h2ss/pmi?callback=jsonCallback&snumber=" + jQuery( "#bk_search_name" ).val() + "&surname=hgf",
            dataType: 'jsonp' // Notice! JSONP <-- P (lowercase)  
        });
        */
/*
        jQuery.ajax({
            url:"http://uhlgenomes-crm-test.xuhl-tr.nhs.uk:8080/genomics/pmi_dev?callback=jsonCallback&snumber=" + jQuery( "#bk_search_name" ).val() + "&surname=hgf",
            dataType: 'jsonp' // Notice! JSONP <-- P (lowercase)  
        });
*/

      });
      displayAdditionalPhoneFields(1); // Create one extra phone input block so total = 2
    }
    disableFormFields ();

    jQuery('#myBtn').click(function () {

        jQuery( "#bk_snumber" ).val("");
        jQuery( "#bk_nhsnumber" ).val("");
        jQuery( "#bk_forenames" ).val("");
        jQuery( "#bk_surname" ).val("");
	jQuery( "#bk_dob" ).val(""); 
	jQuery( "#bk_postcode" ).val("");
	jQuery( "#bk_address1" ).val(""); 
	jQuery( "#bk_telephone1" ).val("");

            jQuery('#mySearchResults').empty();
            jQuery("#mySearchResults > tbody").html("");
            jQuery('#mySearchResultsDiv').scrollTop(0);           
            jQuery('#myModal').css('display','block');
    });

    jQuery('#myModalClose').click(function () {
            jQuery('#myModal').css('display','none');
    });

    jQuery('#myClearAll').click(function () {
        jQuery( "#bk_snumber" ).val("");
        jQuery( "#bk_nhsnumber" ).val("");
        jQuery( "#bk_forenames" ).val("");
        jQuery( "#bk_surname" ).val("");
	jQuery( "#bk_dob" ).val(""); 
	jQuery( "#bk_postcode" ).val("");
	jQuery( "#bk_address1" ).val(""); 
	jQuery( "#bk_telephone1" ).val("");
    });

    jQuery('#mySearch').click(function () {

	    //alert("1" + jQuery( "#bk_snumber" ).val());
            jQuery('#mySearchResults').empty();
            jQuery("#mySearchResults > tbody").html("");
            jQuery('#mySearchResultsDiv').scrollTop(0);
                 
            /* 
            jQuery.ajax({
                url:"http://www.h2ss.co.uk/h2ss/pmi?callback=jsonCallbackAll&snumber=all&surname=hgf",
                dataType: 'jsonp' // Notice! JSONP <-- P (lowercase) 
               }); 
            */  

	    // if bk_snumber or bk_nhsnumer - 2

	    if (jQuery( "#bk_snumber" ).val() != "" && jQuery( "#bk_nhsnumber" ).val() == "")
	    {
	            jQuery.ajax({
        	        url:"http://uhlgenomes-crm-test.xuhl-tr.nhs.uk:8080/genomics/pmi_dev?reqtype=2&snumber=" + jQuery( "#bk_snumber" ).val() + "",
                	dataType: 'jsonp' // Notice! JSONP <-- P (lowercase) 
            	    }); 
	    }

	    if (jQuery( "#bk_snumber" ).val() == "" && jQuery( "#bk_nhsnumber" ).val() != "")
	    {
	            jQuery.ajax({
        	        url:"http://uhlgenomes-crm-test.xuhl-tr.nhs.uk:8080/genomics/pmi_dev?reqtype=2&nhsnumber=" + jQuery( "#bk_nhsnumber" ).val() + "",
                	dataType: 'jsonp' // Notice! JSONP <-- P (lowercase) 
            	    }); 
	    }

	    // if demographics - 1

	    if (jQuery( "#bk_snumber" ).val() == "" && jQuery( "#bk_nhsnumber" ).val() == "")
	    {
	            jQuery.ajax({
        	        url:"http://uhlgenomes-crm-test.xuhl-tr.nhs.uk:8080/genomics/pmi_dev?reqtype=1&demographics=" + jQuery( "#bk_surname" ).val() 
														      + " " + jQuery( "#bk_forenames" ).val() 
														      + " " + jQuery( "#bk_dob" ).val() 
														      + " " + jQuery( "#bk_postcode" ).val()
														      + " " + jQuery( "#bk_address1" ).val() 
														      + " " + jQuery( "#bk_telephone1" ).val(),
                	dataType: 'jsonp' // Notice! JSONP <-- P (lowercase) 
            	    }); 
	    }



	    


           
     });

     jQuery('#myPatient').live('click', function (e) {


	    /*
            jQuery.ajax({
                url:"http://www.h2ss.co.uk/h2ss/pmi?callback=jsonCallback&snumber=" + jQuery(this).val() + "&surname=hgf",
                dataType: 'jsonp' // Notice! JSONP <-- P (lowercase) 
            });
	    */

	    jQuery.ajax({
                url:"http://uhlgenomes-crm-test.xuhl-tr.nhs.uk:8080/genomics/pmi_dev?reqtype=3&snumber=" + jQuery(this).val() + "",
                dataType: 'jsonp' // Notice! JSONP <-- P (lowercase) 
            });

            jQuery('#myModal').css('display','none');          
            e.stopPropagation();               
     });

     
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
  
  jQuery("input[data-crm-custom='Genomics_Data:Family_ID']").attr('readonly', 'readonly'); 
  jQuery("input[data-crm-custom='Genomics_Data:NHS_Number']").attr('readonly', 'readonly'); 
  jQuery("input[data-crm-custom='Genomics_Data:S_Number']").attr('readonly', 'readonly'); 
}



function jsonCallbackAll(data)
{

    if (data.error != "error")
    {
    //alert('jsonCallbackAll');
    jQuery('#mySearchResults').empty();
    jQuery("#mySearchResults > tbody").html("");
    jQuery("#mySearchResultsMessage").html("");
    jQuery('#mySearchResultsDiv').scrollTop(0);

    var tr;

    tr = jQuery('<tr/>');
    tr.append("<td>SCORE</td>");
    tr.append("<td>TITLE</td>");
    tr.append("<td>FORENAMES</td>");
    tr.append("<td>SURNAME</td>");
    tr.append("<td>SEX</td>");
    tr.append("<td>DATE OF BIRTH</td>");
    tr.append("<td>NHS NUMBER</td>");
    tr.append("<td>SYSTEM NUMBER</td>");           
    tr.append("<td>ADDRESS</td>");
    tr.append("<td>POSTCODE</td>");
    tr.append("<td>SELECT</td>");
    jQuery('#mySearchResults').append(tr);

    tr = "";
   
    jQuery.each(data, function(idx, obj) {      
            tr = jQuery('<tr/>');
            tr.append("<td>" + obj.matchscorepercentage + "</td>");
            tr.append("<td>" + obj.title + "</td>");
            tr.append("<td>" + obj.forenames + "</td>");
            tr.append("<td>" + obj.surname + "</td>");
            tr.append("<td>" + obj.sex + "</td>");
            tr.append("<td>" + obj.day + "/" + obj.month + "/" + obj.year + "</td>");
            tr.append("<td>" + obj.nhs_number + "</td>");
            tr.append("<td>" + obj.system_number + "</td>");           
            tr.append("<td>" + obj.address_line_1 + " " + obj.address_line_2 + " " + obj.address_line_3+ " " + obj.address_line_4 + "</td>");
            tr.append("<td>" + obj.postcode + "</td>");
            tr.append("<td><button type='button' id='myPatient' name='myPatient' value='"+ obj.system_number +"'>Select</button></td>");        
            jQuery('#mySearchResults').append(tr);   
    });

    jQuery('#mySearchResultsDiv').scrollTop(0);
    }
    else
    {
      jQuery('#mySearchResults').empty();
      jQuery("#mySearchResultsMessage").html("No Results Found");   
    }
   
}

function jsonCallback(data)
{
  //alert('jsonCallback');
    

  if (data.is_patient_found == 'N') { alert("patient not found")}

  // clear fields
  jQuery('#first_name').val('');
  jQuery('#last_name').val('');
  jQuery('#prefix_id option[value=0]').attr('selected','selected');
  jQuery('#select2-choice').html('');
  jQuery('#select2-chosen-3').text('');


  if (data.deceased == 'true')
  {
    jQuery('#is_deceased').attr('checked', true);
  }
  else
  {
    jQuery('#is_deceased').attr('checked', false);
  }

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
  for (var i in jsonFieldsCustomIds) {
    var fieldId = jsonFieldsCustomIds[i];
    jQuery("input[data-crm-custom=" + fieldId + "]").val('');
  }

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
  if (data.title == 'BR')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Br.');
  }
  if (data.title == 'CANON')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Canon.');
  }
  if (data.title == 'COL')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Col.');
  }
  if (data.title == 'DAME')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Dame.');
  }
  if (data.title == 'FR')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Fr.');
  }
  if (data.title == 'HON')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Hon.');
  }
  if (data.title == 'LADY')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Lady.');
  }
  if (data.title == 'LORD')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Lord.');
  }
  if (data.title == 'LT')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Lt.');
  }
  if (data.title == 'MAJOR')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Major.');
  }
  if (data.title == 'MSGR')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Msgr.');
  }
  if (data.title == 'MSTR')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Mstr.');
  }
  if (data.title == 'PROF')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Prof.');
  }
  if (data.title == 'RABBI')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Rabbi.');
  }
  if (data.title == 'REV')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Rev.');
  }
  if (data.title == 'RTHON')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Rthon.');
  }
  if (data.title == 'SGT')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Sgt.');
  }
  if (data.title == 'SIR')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Sir.');
  }
  if (data.title == 'SR.')
  {
    jQuery('#prefix_id option[value=4]').attr('selected','selected');
    jQuery('#select2-chosen-3').text('Sr.');
  }

  //alert(data.deceased);

  if (data.deceased == '0')
  {
    //alert("1" + data.deceased);

    jQuery('#is_deceased').attr('checked', false);  
  }
  
  if (data.deceased == '1')
  {
    //alert("2" + data.deceased);

    jQuery('#is_deceased').attr('checked', true); 
  }
  
  jQuery('#job_title').val(data.patient_occupation);
  
  jQuery('#phone_1_phone').val(data.telephone_number1);
  jQuery('#phone_2_phone').val(data.telephone_number2);
  
  jQuery('#address_1_street_address').val(data.address_line_1);
  jQuery('#address_1_supplemental_address_1').val(data.address_line_2);
  jQuery('#address_1_city').val(data.address_line_3);
  jQuery('#address_1_postal_code').val(data.postcode);
  
  if (data.sex == 'F')
  {
  jQuery('#civicrm_gender_Female_1').attr('checked', true);
  }
  
  if (data.sex == 'M')
  {
  jQuery('#civicrm_gender_Male_2').attr('checked', true);
  }
  
  if (data.sex == 'I')
  {
  jQuery('#civicrm_gender_Indeterminate_3').attr('checked', true);
  }

  if (data.sex == 'U' || data.sex == '')
  {
  jQuery('#civicrm_gender_Unrecorded_4').attr('checked', true);
  }
  
  if (data.day != '' && data.month != '' && data.year != '0')
  {
    jQuery('#birth_date_display').val(data.day + '/' + data.month + '/' + data.year);  
    jQuery('#birth_date').val(data.day + '/' + data.month + '/' + data.year);  
  }
  
  jQuery("input[data-crm-custom='Genomics_Data:Family_ID']").val('');
  jQuery("input[data-crm-custom='Genomics_Data:Gel_Participant_ID']").val('');
  jQuery("input[data-crm-custom='Genomics_Data:S_Number']").val(data.system_number);
  jQuery("input[data-crm-custom='Genomics_Data:NHS_Number']").val(data.nhs_number);

}

</script>


{/literal}
