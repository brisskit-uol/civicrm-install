{literal}

<!-- NHS standard banner for patient details -->

<style>
  #bk_contact_name_block {
    width: 100%;
    background-color: #ccf;
  }
</style>


<script>
  var bk_contact_name_block = ` 
    <!-- CRM/Contact/Page/View/Summary.extra.tpl -->
    <div id="bk_contact_name_block">
      This is some content from CRM/Contact/Page/View/Summary.extra.tpl
    </div>
  `;

  jQuery( document ).ready(function() {
    if (jQuery('#bk_contact_name_block').length == 0) {
      jQuery('#contactname-block').prepend(bk_contact_name_block);
    }
  });
</script>

{/literal}
