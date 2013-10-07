$(document).ready(function() {
    $('a.editable').popover({}).on('shown.bs.popover', function () {
        var popover = $(this);
        $('a.editable-close').click(function(event){
            event.preventDefault();
            popover.popover('hide');
        });
    });
    $('a.editable.has-error').popover('show');
});