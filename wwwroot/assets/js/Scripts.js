function centerById(id) {
    var width = $(window).width();
    var input = "#" + id;
    var elementwidth = $(input).width();
    var margin = (width - elementwidth) / 4;
    $(input).css("margin-right",margin + "px");
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#placeholder')
                .attr('src', e.target.result)
                .width(100+"%")
                .height("auto");
        };

        reader.readAsDataURL(input.files[0]);
    }
}