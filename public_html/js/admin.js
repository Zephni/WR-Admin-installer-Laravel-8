jQuery(function ($) {
    /* COPIED FOR SIDEBAR */
    $(".sidebar-dropdown > .drop-button").click(function() {
    $(".sidebar-submenu").slideUp(200);
    if (
        $(this)
        .parent()
        .hasClass("active")
    ) {
        $(".sidebar-dropdown").removeClass("active");
        $(this)
        .parent()
        .removeClass("active");
    } else {
        $(".sidebar-dropdown").removeClass("active");
        $(this)
        .next(".sidebar-submenu")
        .slideDown(200);
        $(this)
        .parent()
        .addClass("active");
    }
    });

    $("#close-sidebar").click(function() {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
        $(".page-wrapper").addClass("toggled");
    });
    /* /COPIED FOR SIDEBAR */

    /* COPIED FOR IMAGE PREVIEW */
    $('.browse').on("click", function() {
        var file = $(this).parent().parent().parent().find(".file-upload-input");
        file.trigger("click");
    });

    
    $('input[type="file"].file-upload-input').change(function(e) {
        var fileNameDisplay = $(this).next('.input-group').find('.file-name-display');
        var imagePreview = $(this).parent().parent().find('.image-preview');

        var fileName = e.target.files[0].name;
        $(fileNameDisplay).val(fileName);

        var reader = new FileReader();
        reader.onload = function(e) {
            // get loaded data and render thumbnail.
            $(imagePreview).attr('src', e.target.result);
        };

        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });
    /* COPIED FOR IMAGE PREVIEW */
});