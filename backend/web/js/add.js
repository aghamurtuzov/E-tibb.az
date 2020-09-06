// Tooltip



var site_url = '';
$('.smsSendAjax').click(function (e) {
    var form = $('#smsSend');
    formData = form.serialize();
    if ($('textarea[name="Sms[text]"]').val() !== '' && $('input[name="Sms[phone]"]').val() !== ''){
        e.preventDefault();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (result) {
                if (result.data === 'success') {
                    $(".smsMessage").load(location.href + " .smsMessage");
                } else {
                    alert('Xəta!')
                }
            }
        });
    }
});




//$('#datepicker_experience').datepicker({
//    format:'yyyy',
//    dateFormat: 'yy',
//    viewMode: "years",
//    minViewMode: "years"
//});


$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip({

        container: 'body',

        html:true

    });


    $('.smsSendAjax').click(function (e) {
        var form = $('#smsSend');
        formData = form.serialize();
        if ($('textarea[name="Sms[text]"]').val() !== '' && $('input[name="Sms[phone]"]').val() !== ''){
            e.preventDefault();
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                success: function (result) {
                    if (result.data === 'success') {
                        $(".smsMessage").load(location.href + " .smsMessage");
                    } else {
                        alert('Xəta!')
                    }
                }
            });
        }
    });

    $('.emailSendAjax').click(function (e) {
        var form = $('#emailSend');
        formData = form.serialize();

        if ($('textarea[name="Email[text]"]').val() !== '' && $('input[name="Email[email]"]').val() !== ''){
            e.preventDefault();
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                success: function (result) {
                    if (result.data === 'success') {
                        $(".emailMessage").load(location.href + " .emailMessage");
                    } else {
                        alert('Xəta!')
                    }
                }
            });
        }
    });




});

var count_i = 0;

// /Tooltip



//Promotion



function fetch_select(val)
{
    $.ajax({
        type: 'post',
        url: '/admin/promotions/list',
        data: {
            get_option:val
        },
        success: function (response) {
            item = '';
            //var obj = jQuery.parseJSON( response);
            ///console.log(response);
            $.each( response, function( t,n ) {
                item += '<option value="' + t + '">' + n.name + '</option>';
            });
            $("#new_select").html(item);
            //$("#new_select").select2('refresh');
            //document.getElementById('new_select').innerHTML=response;
        }
    });
}



// form image , sorting , dragging operation



function preview_image() 

{

    var files       = document.getElementById("files").files;

    var total_file  = files.length;

    for(var i=0;i<total_file;i++)

    {

        var appent_data = '<div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 item" data-pos="image_row_'+(i+count_i)+'"><img class="preview_img" id="image_row_'+(i+count_i)+'" src="'+URL.createObjectURL(event.target.files[i])+'" onclick="mark(this);"><button type="button" id="images_row_'+(i+count_i)+'" onclick="remove_preview('+(i+count_i)+');" class="btn btn-danger btn-xs">Sil</button></div>';

            $('.galleryBox').append(appent_data);

    }

    count_i  += total_file;

} 

function remove_preview(i) {

    var image_name = $("#image_row_"+i).data("realname");

	console.log(image_name);

    $.ajax({

        url: site_url+'news/delimage',

        dataType: 'text',

        type:"post",

        data : {"image_name" : image_name},

        processData: true, 

        error: function(data) {

            console.log("");

        },

        success: function(data) {

            console.log(data);

            images_str= $('.galleryBox.ui-sortable').find('input#uploadedImages').val();

            new_datas = images_str.replace(data, "");

            console.log(new_datas);

            new_datas = $('.galleryBox.ui-sortable').find('input#uploadedImages').val(new_datas);

          $("#images_row_"+i).parent().remove();

          console.log(new_datas);

        },

    });

}



function mark(i) {

	$('.galleryBox .item').click(function(){

		$('.galleryBox .item').find('img').removeClass('addborder');

		$('.galleryBox .item').removeClass('main');

        var image_name = $(i).data("realname");

        var main_img   = $('.galleryBox.ui-sortable').find('input#mainImage').val(image_name);

		$(this).find('img').addClass('addborder');

	});

}





//ajax function

 $('#files').change(function(e) {

    e.preventDefault();

    var images_str 		= $('.galleryBox.ui-sortable').find('input#uploadedImages').val();

    var files       	= document.getElementById("files").files;

    var directory_val	= document.getElementById("directory_val").value;

    var total_file  	= files.length;

    var formData 		= new FormData($(this)[0]);

    for (var i=0; i < total_file; i++) {

    	file = files[i];

    	if (window.FileReader) {

            reader = new FileReader();

            reader.onloadend = function(e) {

                /*showUploadedItem(e.target.result, file.fileName);*/

            };

            reader.readAsDataURL(file);

        }

        if (formData) {

            formData.append("files[]", file);

        }

    }

    if(directory_val==0){

    	directory_val = 10;

    	formData.append("directory_val", directory_val);

    }

    //console.log(formData);

    var msg_error = 'Error';

    var msg_timeout = 'server not respond';

    var message = '';

    $.ajax({

	    data: formData,

	    async: true,

	    cache: false,

	    processData: false,

	    contentType: false,

		url: site_url+'news/upload',

		type: 'POST',

		error: function(data) {

	       	console.log();

	    },

	    success: function(data) {

	      var parse_data= JSON.parse(data);

          console.log(count_i);

          console.log(parse_data);

          for(i=0; i<parse_data.length; i++){

                $("#image_row_"+(count_i - parse_data.length + i)).attr("data-realname", parse_data[i]);

          }

          new_datas = images_str;

          for(i=0;i<parse_data.length;i++){

            new_datas = new_datas + " " + parse_data[i];

          }

	      // var new_datas = images_str+' '+parse_data;

	      var datas = $('.galleryBox.ui-sortable').find('input#uploadedImages').val(new_datas);

		  console.log(new_datas);

			

	    },

	    timeout: 7000

	});    

});



