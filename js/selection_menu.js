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

  $('#select_all').click(function() {
  $('.checkbox').prop('checked', true);
  });

  $('#deselect_all').click(function() {
  $('.checkbox').prop('checked', false);
  });

  $(document).on("input", function() {
    update_search($("search"), $(".results"));
  });
 });