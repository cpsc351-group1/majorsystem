$(document).ready(function() {
    // grey out report button automatically
   $('#report').prop('disabled', true);

   // report button only greyed out when no boxes checked
   $('.checkbox').click(function() {
     if ($(this).is(":checked")) {
       $('#report').prop('disabled', false);
     } else if ($('.checkbox').filter(':checked').length == 0) {
       $('#report').prop('disabled', true);
     }
   });
 });