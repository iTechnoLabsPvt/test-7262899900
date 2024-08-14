<template>
    <div class="card p-5">
        <div class="card-header text-center">
            <h2>Schedule</h2>
        </div>
        <div class="card-body">
            <form @submit.prevent="schedule">
                <div class="form-group">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" v-model="formData.date" id="date" class="form-control" />
                    <span v-if="dateError" class="invalid-feedback d-block">{{ dateError }}</span>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="start_time" class="form-label">Start Time:</label>
                        <input type="time" v-model="formData.start_time" id="start_time" class="form-control" />
                        <span v-if="startTimeError" class="invalid-feedback d-block">{{ startTimeError }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_time" class="form-label">End Time:</label>
                        <input type="time" v-model="formData.end_time" id="end_time" class="form-control" />
                        <span v-if="endTimeError" class="invalid-feedback d-block">{{ endTimeError }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="is_repeated" class="form-label">Is Repeated:</label>
                    <input type="checkbox" v-model="formData.is_repeated" id="is_repeated" class="form-check" />
                    <span v-if="isRepeatedError" class="invalid-feedback d-block">{{ isRepeatedError }}</span>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Save</button>
                <p v-if="errorMessage" class="text-danger">{{ errorMessage }}</p>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['id'],
    data() {
        return {
            formData: {
                date: '',
                start_time: '',
                end_time: '',
                is_repeated: false,
                user_id : this.id
            },
            errorMessage: '',
            dateError: '',
            startTimeError: '',
            endTimeError: '',
            isRepeatedError: '',
            intervalError: ''
        };
    },
    methods: {
        async schedule() {
            const token = localStorage.getItem('token');
            try {
                const response = await axios.post(`/api/schedule/${this.id}`, this.formData, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });
                if (response?.data?.success) {
                    this.$router.push({ name: 'home' });
                } else {
                    this.errorMessage = response?.data?.message;
                }
            } catch (error) {
                this.dateError = error?.response?.data?.errors?.date?.[0] || '';
                this.startTimeError = error?.response?.data?.errors?.start_time?.[0] || '';
                this.endTimeError = error?.response?.data?.errors?.end_time?.[0] || '';
                this.isRepeatedError = error?.response?.data?.errors?.is_repeated?.[0] || '';
                this.errorMessage = error?.response?.data?.message || 'An error occurred.';
            }
        }
    }
};
</script>
