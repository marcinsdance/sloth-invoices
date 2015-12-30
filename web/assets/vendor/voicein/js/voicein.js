var fnDatepicker = function() {
    $('.datepicker').datepicker();
};

var fnEditable = function() {
    $(".editable").each( function() {
        var postUrl = $(this).closest('tr').attr('data-url');
        var update = $(this).attr('data-update');
        var qty = $(this).closest('tr').find('td[data-update=qty]').text();
        var value = $(this).closest('tr').find('td[data-update=value]').text();
        var values = {};
        values['qty'] = qty;
        values['value'] = value;
        $(this).editable("dblclick", function(e){
            values[update] = e.value;
            $.ajax({
                    method: "POST",
                    url: postUrl,
                    data: values
                })
                .done(function( msg ) {
//                    alert( "Data Saved: " + msg );
                })
                .fail(function( msg ) {
                    alert( "Error: " + msg );
                });
        });
    });
};

(function ($) {
    fnDatepicker();
    fnEditable();
})(jQuery);
