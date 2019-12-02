<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-danger" v-if="error">
                <p>Произошла ошибка. Не удалось войти с этими учетными данными.</p>
            </div>
            <form autocomplete="off" @submit.prevent="login" Метод = «пост»>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" class="form-control" v-model="password" required>
                </div>
                <button type="submit" class="btn btn-default">Войти в систему</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                email: null,
                password: null,
                error: false
            }
        },
        methods: {
            login(){
                var app = this
                this.$auth.login({
                    params: {
                        email: app.email,
                        password: app.password
                    },
                    success: function () {},
                    error: function () {},
                    rememberMe: true,
                    redirect: { name: 'dashboard'},
                    fetchUser: true,
                });
            },
        }
    }
</script>
