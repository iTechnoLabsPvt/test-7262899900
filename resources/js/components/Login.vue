<template>
    <div class="card p-5">
        <div class="card-header text-center">
            <h2>Login</h2>
        </div>
        <div class="card-body">
            <form @submit.prevent="login">
                <div class="form-group">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" v-model="email" id="email" class="form-control" />
                    <span v-if="emailError" class="invalid-feedback d-block">{{ emailError }}</span>

                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" v-model="password" id="password" class="form-control" />
                    <span v-if="passwordError" class="invalid-feedback d-block">{{ passwordError }}</span>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Login</button>
                <p v-if="errorMessage">{{ errorMessage }}</p>
            </form>
        </div>

    </div>
</template>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            errorMessage: '',
            emailError: '',
            passwordError: ''
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                });
                if (response.data.success) {
                    localStorage.setItem('token', response?.data?.data?.token);
                    this.$router.push({ name: 'home' });
                } else {
                    this.emailError = 'Invalid credentials.';
                }
            } catch (error) {
                this.emailError = error?.response?.data?.data?.email ? error?.response?.data?.data?.email[0] : '';
                this.passwordError = error?.response?.data?.data?.password ? error?.response?.data?.data?.password[0] : '';
                this.errorMessage = error?.response?.data?.message;
            }
        },
    },
};
</script>
