<template>
    <div>
        <div class="form-group">
            <router-link :to="{ name: 'add'}">Добавить новое фото</router-link>
        </div>
        <h3>Список биометрических фото</h3>
        <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th style="width: 100px;">Фото</th>
            <th>Наименование</th>
            <th style="width: 10%;">Расса</th>
            <th style="width: 10%;">Пол</th>
            <th style="width: 1%;"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="face in faces" @dblclick="faceShow(face.id)" class="row-item">
            <td><img :src="face.imagePrev" style="width:100px;" alt=""></td>
            <td>{{ face.name }}</td>
            <td colspan="2">
                <table class="table">
                    <tr v-for="fit in face.result">
                        <td>{{ fit.race }} {{ fit.race_confidence}}%</td>
                        <td>{{ fit.sex }} {{ fit.sex_confidence}}%</td>
                    </tr>
                </table>
            </td>
            <td><button @click="faceDelete(face.id)">Удалить</button></td>
        </tr>
        </tbody>
    </table>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                faces: []
            }
        },
        methods: {
            faceShow(id) {
                this.$router.push({ name: 'show', params: { id } })
            },
            userId() {
                return this.$auth.user().id
            },
            loadList() {
                let id = this.$auth.user().id
                var list = []
                if (id) {
                    axios.get('/user/' + id + '/faces')
                    .then(response => {
                        this.faces = (response.data.result) ? response.data.result : []
                    })
                }
            },
            faceDelete(id) {
                console.log('delete:'+id)
                let userId = this.$auth.user().id
                var list = []
                if (userId) {
                    axios.delete('/user/' + userId + '/face/'+ id + '/delete')
                        .then(response => {
                            this.faces = (response.data.result) ? response.data.result : []
                        })
                }
            }

        },
        componentUpdated() {
            console.log('Update')
            this.faces = this.loadFaceList()
        },
        mounted() {
            this.loadList()
            console.log('watch')
        }
    }
</script>

<style scoped>
    .row-item {
        cursor: pointer;
    }
</style>
