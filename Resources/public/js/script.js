$(document).ready(function() {
    $('.cell').hover(
        function(event) {
            var self = $(this),
                width = self.width(),
                button = self.find('.cell-edit');

            self.css({"width" : width + "px"});

            if (button.length) {
                if (button.is(":visible")) {
                    button.hide();
                } else {
                    button.show();
                }
            }
        }
    );

    $('.cell-edit').click(function(event) {
        event.preventDefault();

        var self = $(this),
            target = $("#" + self.data('target'));

        if (target.length) {
            target.toggle();
        }
    });
});