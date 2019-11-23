function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#image').attr('src', e.target.result);
            $('.findUser').remove();
            $('.alert').hide();
            btnDisabled(!e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function btnDisabled($status)
{
    $('#send-photo').attr('disabled', $status);
    $("#clear-photo").attr('disabled', $status);

}

var init = function() {
    $("#imgInput").change(function() {
        readURL(this);
    });
}
init();

$("#send-photo").on("click", function(e) {
    var formData = new FormData(),
        $input = $('#imgInput'),
        url = '/image/upload';

    formData.append('image', $input.prop('files')[0]);
    formData.append('_token', $input.data('token'));
    var alert = $('.alert');
    alert.hide();
    $.ajax({
        url: url,
        method:'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend:function(){
            $('#send-photo').attr('disabled', true);
            $('#info-block').show();
            $("blockquote.blockquote").text('Идет обработка изображения...');
        },
        success: function(response){
            $("blockquote.blockquote").html('');
            if (response) {
                var data = JSON.parse(response);
                if (data.status == 'success') {
                    data.result.forEach(function (item) {
                        addUserInfo(item);
                        var box = item.box;
                        rectangle(box.x1, box.y1, (box.x2 - box.x1), (box.y2 - box.y1));
                    })
                } else {
                    alert.show();
                    alert.text(data.error);
                }
            }
        }
    });

    return false;
});

$("#clear-photo").on("click", function () {
    $('.findUser').remove();
    $('#image').attr('src', '#');
    btnDisabled(true);
    $('#info-block').hide();
    var cont = $('.upload-file-container-text')[0].innerHTML;
    console.log(cont);
    $('.upload-file-container-text')[0].innerHTML = cont;
    $('.alert').hide();
    init();

})

function rectangle(x, y, w, h) {
    // Получим реальный размер картинки
    var image = $('#image'),
        container = $(".upload-file-container"),
        k = (container.width() / image[0].naturalWidth);

    var obj = $("<span class='findUser'></span>");
    obj.css('left', Math.round(x * k) + 'px');
    obj.css('top', Math.round(y * k) + 'px');
    obj.css('width', Math.round(w * k) + 'px');
    obj.css('height', Math.round(h * k) +'px');
    obj.appendTo(container);
}

function addUserInfo(item)
{
    var block = $("blockquote.blockquote");
    var obj = $('<div class="row"><div class="col-6"><label class="title">Расса:&nbsp; </label><span class="value">'+item.race + ' '+item.race_confidence+'%</span></div>'+
        '<div class="col-6"><label class="title">Пол:&nbsp;</label><span class="value">'+item.sex + ' ' + item.sex_confidence+'%</span></div></div>');
    obj.appendTo(block);
}
