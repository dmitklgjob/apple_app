(function ($) {
    $(".alert").animate({opacity: 1}, 7000).fadeOut("slow");

    $(document).on('click', '[data-toggle="modal"]', function (event) {
        event.preventDefault();
        const url = $(this).attr('href');
        const dialog = $($(this).data('target'));
        dialog.find('#modalContent').load(url);
        dialog.modal('show');
        return false;
    });


    $(document).on('beforeSubmit', 'form[data-ajax-form="ajax"]', function (event) {
        event.stopPropagation();
        event.preventDefault();
        const form = $(this);

        if (form.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url: form.attr('action'),
            type: form.attr("method"),
            data: new FormData(this),
            dataType: "json",
            processData: false,
            contentType: false,
            success: function () {
                $('#modal-dialog')
                    .modal('hide')
                    .on("hidden.bs.modal", function () {
                        $(this).find("input,textarea,select").val('').end();
                    });
                setTimeout('location.reload()', 500);
            }
        });

        return false;
    });
})(jQuery);
