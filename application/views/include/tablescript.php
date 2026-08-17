<script>
$(function () {

$("#notaris").DataTable({
  "responsive": true, "lengthChange": false, "autoWidth": false,
  "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#tarja_min_wrapper .col-md-6:eq(0)');

});

function ChangeCellBackground(value, row, index) {
var classes = [];
var warna = '';
var backColor = '';

if (value < 11) {
    warna = '#28a745';
}
else {
    warna = 'red';
}           

return {
    css: {
        "background-color": warna
    }
};
}

$('.counter-value').each(function(){
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    },{
        duration: 3500,
        easing: 'swing',
        step: function (now){
            $(this).text(Math.ceil(now));
        }
    });
});
</script>
