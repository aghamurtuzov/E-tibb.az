$("form").on("change", ".file-upload-field", function(){
    $(this).parent(".file-upload-wrapper").attr("data-text",$(this).val().replace(/.*(\/|\\)/, '') );
});

/*if ($('body .main').hasClass('clinic-inner')) {*/
    !function (a) {
        a.fn.datepicker.dates.az = {
            days: ["Bazar", "Bazar ertəsi", "Çərşənbə axşamı", "Çərşənbə", "Cümə axşamı", "Cümə", "Şənbə"],
            daysShort: ["B.", "B.e", "Ç.a", "Ç.", "C.a", "C.", "Ş."],
            daysMin: ["B.", "B.e", "Ç.a", "Ç.", "C.a", "C.", "Ş."],
            months: ["Yanvar", "Fevral", "Mart", "Aprel", "May", "İyun", "İyul", "Avqust", "Sentyabr", "Oktyabr", "Noyabr", "Dekabr"],
            monthsShort: ["Yan", "Fev", "Mar", "Apr", "May", "İyun", "İyul", "Avq", "Sen", "Okt", "Noy", "Dek"],
            today: "Bu gün",
            weekStart: 1,
            clear: "Təmizlə",
            monthsTitle: "Aylar"
        }
    }(jQuery);

    $('#datepicker').datepicker({
        language: 'az',
        datesDisabled: ["07/20/2019", "07/29/2019"]
    });

    $('#datepicker').on('changeDate', function () {
        $('#my_hidden_input').val(
            $('#datepicker').datepicker('getFormattedDate')
        );
    });

    $('#datepicker .prev').empty().append("<span><i class=\"fa fa-angle-left\" aria-hidden=\"true\"></i></span>");
    $('#datepicker .next').empty().append("<span><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>");
/*
}
*/

/*
$("#fileinput").change(function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = imageIsLoaded;
        reader.readAsDataURL(this.files[0]);
    }
});

function imageIsLoaded(e) {
    var picture = '<img src="' + e.target.result + '"  class="img-responsive">';
    $(".preview").empty().append(picture);
}*/
