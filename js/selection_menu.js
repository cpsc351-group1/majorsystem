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
    parent_styles();
  });

  $('#deselect_all').click(function() {
    $('.checkbox').prop('checked', false);
    parent_styles();
  });

  function parent_styles() {
    $('.data').removeClass('checked');
    $('.data:has(input:checked)').addClass('checked');
  }

  $(document).on("input", function() {
    parent_styles();
  });
 });