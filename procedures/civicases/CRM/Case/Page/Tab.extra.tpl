{literal}
<!-- NHS standard banner for patient details -->
<style>
  #bk_contact_name_block {
    width: 100%;
    background-color: #ccf;
  }
</style>
 

<script>


    function GetUrlValue(VarSearch){
    var SearchString = window.location.search.substring(1);
    var VariableArray = SearchString.split('&');
    for(var i = 0; i < VariableArray.length; i++){
        var KeyValuePair = VariableArray[i].split('=');
        if(KeyValuePair[0] == VarSearch){
            return KeyValuePair[1];
        }
    }
    }


  var bk_contact_name_block = `
    <!-- CRM/Case/Page/Tab.extra.tpl -->
    <div width="100%" id="bk_contact_name_block" style="background-color: green; padding-top: 10px; padding-bottom: 10px;">
      <!--This is some content from CRM/Contact/Page/View/Summary.extra.tpl-->

<table style="width:100%">
  <tr>
    <td width="40%">
	<label id="p_display_name" style="font-style: normal; font-family: Arial, Helvetica, sans-serif; font-size: 20px;"></label>
	<!--<label id="p_prefix_id" style="font-style: normal; font-family: Arial, Helvetica, sans-serif; font-size: 20px;"></label>-->
     </td>
    <td width="20%">
	<label id="p_birth_date" style="font-style: normal; font-family: Arial, Helvetica, sans-serif; font-size: 20px;"></label>
     </td>
    <td width="20%">
	<label id="p_gender" style="font-style: normal; font-family: Arial, Helvetica, sans-serif; font-size: 20px;"></label>
    </td>
    <td width="20%">
	<label id="p_custom_12" style="font-style: normal; font-family: Arial, Helvetica, sans-serif; font-size: 20px;"></label>
    </td>
  </tr>

</table>

      
    </div>
  `;

  jQuery( document ).ready(function() {
    if (jQuery('#bk_contact_name_block').length == 0) {


      var contact_id = GetUrlValue('cid')
      //alert("*" + contact_id + "*");

      
      jQuery('#page-title').html(bk_contact_name_block);


      

/*    var customfieldID_Family_ID

      CRM.api3('CustomField', 'get', {
      "sequential": 1,
      "name": "Family_ID"
      }).done(function(result) {
        customfieldID_Family_ID = "custom_" + result.id;
      	alert(result.id);
    	});
*/

    CRM.api3('Contact', 'get', {
      "sequential": 1,
      "return": "custom_10,custom_12,custom_11,,custom_13,display_name, is_deceased, gender, prefix_id, birth_date",
      "contact_id": contact_id
    }).done(function(result) {
        //alert(result.values[0].custom_10);
        //jQuery('#page-title').append('[Family ID : ' + result.values[0].custom_10 + '] ');
        //alert(result.values[0].custom_11);
        //jQuery('#page-title').append('[S Number : ' + result.values[0].custom_11 + '] ');
        //alert(result.values[0].custom_12);
        //jQuery('#page-title').append('[NHS Number : ' + result.values[0].custom_12 + '] ');

        //alert(result.values[0].custom_13);
        //jQuery('#page-title').append('[Gel Participant ID : ' + result.values[0].custom_13 + '] ');
        
        //alert(result.values[0].custom_13); 
        //jQuery('#page-title').append('[prefix_id : ' + result.values[0].prefix_id + '] '); // 1- Mrs 2 - Ms 3 - Mr 4 - Dr
	//alert(result.values[0].custom_13);
        //jQuery('#page-title').append('[display_name : ' + result.values[0].display_name + '] ');
	//alert(result.values[0].custom_13);
        //jQuery('#page-title').append('[gender : ' + result.values[0].gender + '] ');
	//alert(result.values[0].custom_13);
        //jQuery('#page-title').append('[is_deceased : ' + result.values[0].is_deceased + '] ');
        //alert(result.values[0].custom_13);
        //jQuery('#page-title').append('[birth_date : ' + result.values[0].birth_date + '] ');

if (result.values[0].custom_12 != null) { jQuery('#p_custom_12').text('NHS No. ' + result.values[0].custom_12); }
if (result.values[0].birth_date != null) { jQuery('#p_birth_date').text('Born. ' + result.values[0].birth_date); }
if (result.values[0].prefix_id != null) { jQuery('#p_prefix_id').text(result.values[0].prefix_id); }
if (result.values[0].display_name != null) { jQuery('#p_display_name').text(result.values[0].display_name); }
if (result.values[0].gender != null) { jQuery('#p_gender').text('Gender. ' + result.values[0].gender); }


if (result.values[0].is_deceased != null && result.values[0].is_deceased == '1') { jQuery('#bk_contact_name_block').css('background-color','red'); jQuery('#p_display_name').append(' (Deceased)'); }


/*
	var bk_contact_name_block = `
    	<!-- CRM/Case/Page/Tab.extra.tpl -->
    	<div id="bk_contact_name_block">
      		This is some content from CRM/Contact/Page/View/Summary.extra.tpl
    	</div>
  	`;
*/


    });
       

    }
  });
</script>


{/literal}

