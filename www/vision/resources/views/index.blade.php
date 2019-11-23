<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/common.css">
    <title>Сервис для определения людей, их расы и пола на изображениях</title>
</head>
<body>
<nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="/docs/4.3.1/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="">
        Загрузи свое фото и узнай кто ты есть!
    </a>
</nav>
<div class="container">
    <div class="row">
        <div class="col-4">
                <fieldset>
                    <div class="input-file-row-1">
                        <div class="upload-file-container">
                            <img id="image" src="#" alt="" />
                            <div class="upload-file-container-text">
                                <input data-token="{{ csrf_token()}}" type="file" name="image" class="photo" id="imgInput" />
                            </div>
                        </div>
                    </div>
                </fieldset>
        </div>
        <div class="col-8">
            <div class="btn-group m-4">
                <button type="button" id="send-photo" class="btn btn-primary" disabled="true">Отправить на проверку</button>
                <button type="button" id="clear-photo" class="btn btn-success" disabled="true">Очистить</button>
            </div>
            <div class="alert alert-danger" role="alert" style="display: none;">
                This is a danger alert—check it out!
            </div>
            <div id="info-block" style="display: none;">
            <blockquote class="blockquote"></blockquote>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="assets/js/common.js"></script>

</body>
</html>
