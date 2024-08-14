<template>
    <div id="app">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>Set Availability</h2>
                </div>
                <form @submit.prevent="setAvailability">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h5>Monday</h5>
                        </div>
                        <div class="col-4"><input type="checkbox" v-model="day_id" value="1" checked></div>
                        <div class="col-8">
                            <h5>Tuesday</h5>
                        </div>
                        <div class="col-4"><input type="checkbox" v-model="day_id" value="2" checked></div>
                        <div class="col-8">
                            <h5>Wednesday</h5>
                        </div>
                        <div class="col-4"><input type="checkbox" v-model="day_id" value="3" checked></div>
                        <div class="col-8">
                            <h5>Thursday</h5>
                        </div>
                        <div class="col-4"><input type="checkbox" v-model="day_id" value="4" checked></div>
                        <div class="col-8">
                            <h5>Friday</h5>
                        </div>
                        <div class="col-4"><input type="checkbox" v-model="day_id" value="5" checked></div>
                        <div class="col-8">
                            <h5>Saturday</h5>
                        </div>
                        <div class="col-4"><input type="checkbox" v-model="day_id" value="6" checked></div>
                        <div class="col-8">
                            <h5>Sunday</h5>
                        </div>
                        <div class="col-4"><input type="checkbox" v-model="day_id" value="7" checked></div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit"  class="btn btn-primary">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: ['id'],
    data() {
        return {
            day_id: [1, 2, 3, 4, 5, 6, 7],
            error: null,
        };
    },
    methods: {
        async setAvailability() {
            try {
                const response = await axios.post('/api/set-availability', {
                    day_id: this.day_id,
                    user_id: this.id
                });
                if (response.data.success) {
                    this.$router.push({ name: 'home' });
                } else {
                    this.errorMessage = 'Error Occurred';
                }
            } catch (error) {
                this.errorMessage = error?.response?.data?.message;
            }
        },
    },
};
</script>
