$(function() {
  let sellOrder = true;

  $('.sortTable').DataTable({'pageLength': 1000});

  $('#toggleSellBuy').on('click', function() {
    $('#resourceSell').toggle();
    $('#resourceBuy').toggle();
    sellOrder = !sellOrder;
  });

  $('#myModal').on('shown.bs.modal', function() {
    $('#myInput').trigger('focus');
  });

  $('.tierSelect').on('change', function(e) {
    let tier = $(this).val();
    if (sellOrder) {
      $('.sell' + tier).toggle();
      console.log('1');
    } else {
      $('.buy' + tier).toggle();
      console.log('2');
    }
    console.log('.sell' + tier);
  });

});