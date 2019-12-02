<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <router-link :to="{ name: 'dashboard'}">Назад к списку</router-link>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <fieldset>
                        <div class="input-file-row-1 photo-box">
                            <div class="upload-file-container">
                                <span v-if="errorfile" class="label-loading"><p>Щелкните мышкой в это поле для загрузки фото</p></span>
                                <img ref="imageSrc" class="image" :src="face.imagePrev" alt="" />
                                <div class="upload-file-container-text">
                                    <input v-if="!id" ref="photo"
                                           type="file"
                                           @change="readURL($event)"
                                           accept="image/*"
                                           name="image"
                                           class="photo" />
                                </div>
                                <span v-for="b in face.result" class='findUser' :style="rectangleStyle(b.box)"></span>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Наименование фото</label>
                        <input type="text"
                               class="form-control"
                               name="name"
                               v-model.trim="name"
                               @input="$v.name.$touch()"
                               required>
                        <span class="help-block" v-if="$v.name.$error">Поле имени не должно быть пустым!</span>
                    </div>
                    <div v-if="face.result" class="form-group">
                        <label>Биометрические данные</label>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                            <th >№</th>
                            <th >Расса</th>
                            <th >Пол</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(fit, i) in face.result">
                                <td>{{ i + 1}}</td>
                                <td>{{ fit.race }} {{ fit.race_confidence}}%</td>
                                <td>{{ fit.sex }} {{ fit.sex_confidence}}%</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-danger" v-if="error.text">
                        <p>{{ error.text }}</p>
                    </div>
                    <div class="alert alert-info" v-if="info.text">
                        <p>{{ info.text }}</p>
                    </div>
                    <div class="alert alert-success" v-if="info.success">
                        <p>{{ info.success }}</p>
                    </div>
                    <div id="info-block" style="display: none;">
                        <blockquote class="blockquote"></blockquote>
                    </div>

                    <div class="btn-group m-4">
                        <button v-if="!id" type="button" @click="sendNewFace" class="btn btn-primary" :disabled="disableSend || $v.$invalid || errorfile">Отправить на проверку</button>
                        <button v-if="id" type="button" @click="sendFace" class="btn btn-primary" :disabled="$v.$invalid">Сохранить изменения</button>
                        <button v-if="!id" type="button" @click="clearFace" class="btn btn-success" :disabled="disableSend">Очистить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import { required } from 'vuelidate/lib/validators'

    export default {
        data() {
          return {
              error: {
                  text: ''
              },
              info: {
                  text: '',
                  success: ''
              },
              name: '',
              errorfile: true,
              face: {
                  name: '',
                  imagePrev: '#',
                  box: []
              },
              k: 1,
              disableSend: false,
              id: this.$router.currentRoute.params['id'] || 0

          }
        },
        methods: {
            readURL(input) {
                var _this = this
                console.log(this.$refs.img)
                if (input.target.files && input.target.files[0]) {
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        _this.$refs.imageSrc.src = e.target.result
                        _this.errorfile = false
                        //$('.findUser').remove();
                        //$('.alert').hide();
                        //btnDisabled(!e.target.result);
                    }

                    reader.readAsDataURL(input.target.files[0]);
                }
            },
            faceLoad(id) {
                this.info.success = ''
                let userId = this.$auth.user().id
                if (userId) {
                    console.log(id)
                    axios.get('/user/' + userId + '/face/' + id )
                        .then(response => {
                            if (response.data.status == 'success') {
                                this.face = response.data.result
                                this.name = this.face.name
                                this.errorfile = false
                                if (this.face.result) {
                                    this.info.text = ''
                                } else
                                    this.info.text = 'Идет обработка данных, результат будет известен через несколько секунд...'
                            } else {
                                this.error.text = response.data.error
                            }
                        })
                }
            },
            sendNewFace() {
                this.info.text = 'Идет процевв выгрузки данных...'
                this.disableSend = true
                let userId = this.$auth.user().id
                if (userId) {
                    let ev = this.$refs.photo
                    let formData = new FormData()
                    formData.append('name', this.name);
                    formData.append('image', ev.files[0]);
                    let config = {
                        header : {
                            'Content-Type' : 'image/png'
                        }
                    }
                    axios.post('/user/' + userId + '/face/new',
                        formData,
                        config)
                        .then(response => {
                            if (response.data.status=='success') {
                                this.face = response.data.result
                                this.name = this.face.name
                                this.$router.push({ name: 'show', params: { id: this.face.id } })
                                this.errorfile = false
                                this.info.text = 'Данные отправлены, результат будет известен через несколько секунд...'
                                this.watchReload()
                            } else {
                                this.disableSend = false
                                this.error.text = response.data.error
                                // Сообщаем о проблеме
                            }
                        })
                }
            },
            sendFace() {
                let userId = this.$auth.user().id
                if (userId) {
                    let data = { name: this.name }
                    axios.post('/user/' + userId + '/face/' + this.face.id, data)
                        .then(response => {
                            if (response.data.status=='success') {
                                this.face = response.data.result
                                this.info.success = 'Данные успешно сохранены...'
                            } else {
                                this.error.text = response.data.error
                                // Сообщаем о проблеме
                            }
                        })
                }
            },
            clearFace() {
                this.$refs.imageSrc.src = ''
                this.face.name = ''
                this.name = this.face.name
                this.face.result = []
                this.errorfile = true
                this.error.text = ''
            },
            rectangleStyle(box) {
                let k = this.face.k
                return 'left:' + Math.round(box.x1 * k) + 'px;' +
                'top:' + Math.round(box.y1 * k) + 'px;' +
                'width:' + Math.round((box.x2-box.x1) * k) + 'px;' +
                'height:' + Math.round((box.y2-box.y1) * k) + 'px;'
            },
            watchReload() {
                let _this = this
                if (!this.face.result) {
                    setTimeout(function() {
                        _this.watchReload()
                    }, 2000);
                    this.faceLoad(this.id)
                }
            }
        },
        validations: {
            name: {
                required
            }
        },
        watch: {
            $route(toR, fromR) {
                this.id = toR.params['id'] || 0
                if (this.id) this.faceLoad(this.id);
            }
        },
        mounted() {
            if (this.id) this.faceLoad(this.id);
        }
    }
</script>

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
    .label-loading {
        position: absolute;
        display: block;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        text-align: center;
        padding-top: 142px;
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
