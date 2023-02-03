$(function() {
  $('.sortTable').DataTable({'pageLength': 50});

  $('#toggleSellBuy').on('click', function() {
    $('#resourceSell').toggle();
    $('#resourceBuy').toggle();
  });

  $('#myModal').on('shown.bs.modal', function() {
    $('#myInput').trigger('focus');
  });

});