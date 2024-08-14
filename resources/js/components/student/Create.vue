<template>
    <div class="card p-5">
        <div class="card-header text-center">
            <h2>Create Student</h2>
        </div>
        <div class="card-body">
            <form @submit.prevent="create">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" v-model="form.first_name" id="first_name" class="form-control" />
                        <span v-if="form.firstNameError" class="invalid-feedback d-block">{{ form.firstNameError }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="middle_name" class="form-label">Middle Name:</label>
                        <input type="text" v-model="form.middle_name" id="middle_name" class="form-control" />
                        <span v-if="form.middleNameError" class="invalid-feedback d-block">{{ form.middleNameError }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" v-model="form.last_name" id="last_name" class="form-control" />
                        <span v-if="form.lastNameError" class="invalid-feedback d-block">{{ form.lastNameError }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_of_birth" class="form-label">Date of birth:</label>
                    <input type="date" v-model="form.date_of_birth" id="date_of_birth" class="form-control" />
                    <span v-if="form.dobError" class="invalid-feedback d-block">{{ form.dobError }}</span>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" v-model="form.email" id="email" class="form-control" />
                    <span v-if="form.emailError" class="invalid-feedback d-block">{{ form.emailError }}</span>
                </div>
                <div class="form-group">
                    <label for="puassword" class="form-label">Password:</label>
                    <input type="password" v-model="form.password" id="password" class="form-control" />
                    <span v-if="form.passwordError" class="invalid-feedback d-block">{{ form.passwordError }}</span>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                <p v-if="form.errorMessage">{{ form.errorMessage }}</p>
            </form>
        </div>

    </div>
</template>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                first_name : '',
                middle_name : '',
                last_name : '',
                email: '',
                password: '',
                errorMessage: '',
                firstNameError: '',
                lastNameError: '',
                middleNameError: '',
                dobError: '',
                emailError: '',
                passwordError: ''
            }

        };
    },
    methods: {
        async create() {
            try {
                const response = await axios.post('/api/create', this.form);
                if (response?.data?.success) {
                    const id = response?.data?.id;
                    this.$router.push({ name: 'availability',params: { id: id } });
                } else {
                    this.errorMessage = '';
                }
            } catch (error) {
                this.form.emailError = error?.response?.data?.data?.email ? error?.response?.data?.data?.email[0] : '';
                this.form.passwordError = error?.response?.data?.data?.password ? error?.response?.data?.data?.password[0] : '';
                this.form.firstNameError = error?.response?.data?.data?.first_name ? error?.response?.data?.data?.first_name[0] : '';
                this.form.lastNameError = error?.response?.data?.data?.last_name ? error?.response?.data?.data?.last_name[0] : '';
                this.form.middleNameError = error?.response?.data?.data?.middle_name ? error?.response?.data?.data?.middle_name[0] : '';
                this.form.dobError = error?.response?.data?.data?.date_of_birth ? error?.response?.data?.data?.date_of_birth[0] : '';
                this.form.errorMessage = error?.response?.data?.message;
            }
        },
    },
};
</script>
