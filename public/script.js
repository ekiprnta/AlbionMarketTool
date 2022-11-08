$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
});

$(function () {
    $('.dropdown-toggle').dropdown();
    $('#sortTable').DataTable();
});