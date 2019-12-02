<template>
    <div>
        <nav class="navbar navbar-light bg-light">
            <a class="navbar-brand" href="/">
                o.Vision
            </a>
        </nav>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6-md">
                    <fieldset>
                        <div class="input-file-row-1 photo-box">
                            <div class="upload-file-container">
                                <img id="image" src="#" alt="" />
                                <div class="upload-file-container-text">
                                    <input data-token="" type="file" name="image" class="photo" id="imgInput" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-6-md">
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
    </div>
</template>

<script>

    export default {
        name: "Users"
    }
</script>

<style>
    body {
        background-color: #ffffff !important;
    }
</style>

<style scoped>
    .photo-box {
        width: 100%
    }
    .input-file-row-1:after {
        content: ".";
        display: block;
        clear: both;
        visibility: hidden;
        line-height: 0;
        height: 0;
    }
    .input-file-row-1 {
        display: inline-block;
        margin-top: 25px;
        position: relative;
    }
    html[xmlns] .input-file-row-1 {
        display: block;
    }
    * html .input-file-row-1 {
        height: 1%;
    }
    .upload-file-container {
        position: relative;
        width: 400px;
        height: 300px;
        overflow: hidden;
        background-color: #dddddd;
        border: 2px dotted #aaaaaa;
        border-radius: 3px;
        /*background: url(http://i.imgur.com/AeUEdJb.png) center center no-repeat;*/
        float: left;
        margin-left: 23px;
    }
    .upload-file-container:first-child {
        margin-left: 0;
    }
    .upload-file-container > img {
        width: 100%;
        border-radius: 2px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
    }
    .upload-file-container-text {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #719d2b;
        line-height: 17px;
        text-align: center;
        display: block;
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
    }
    .upload-file-container-text > span {
        border-bottom: 1px solid #719d2b;
        cursor: pointer;
    }
    .upload-file-container input {
        position: absolute;
        left: 0;
        bottom: 0;
        font-size: 1px;
        opacity: 0;
        filter: alpha(opacity=0);
        margin: 0;
        padding: 0;
        border: none;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .findUser {
        position: absolute;
        border: 2px solid red;
        background: transparent;
        z-index: 1000;
    }
</style>
