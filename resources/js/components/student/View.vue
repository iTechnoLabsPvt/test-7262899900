<template>
    <div id="app">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>{{ detail?.name }}<span v-for="index in 5" :key="index" class="star"
                            :class="{ filled: index <= (detail?.rating) }">
                            ★
                        </span></h2>

                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ detail?.name }} </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ detail?.email }} </td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ detail?.date_of_birth }}</td>
                        </tr>
                    </table>
                    <h5>Availability</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Monday</th>
                            <td><input type="checkbox" v-model="day_id" value="1" disabled
                                    :checked="availability?.includes(1)"></td>
                        </tr>
                        <tr>
                            <th>Tuesday</th>
                            <td><input type="checkbox" disabled v-model="day_id" value="2"
                                    :checked="availability?.includes(2)"></td>
                        </tr>
                        <tr>
                            <th>Wednesday</th>
                            <td><input type="checkbox" disabled v-model="day_id" value="3"
                                    :checked="availability?.includes(3)"></td>
                        </tr>
                        <tr>
                            <th>Thursday</th>
                            <td><input type="checkbox" disabled v-model="day_id" value="4"
                                    :checked="availability?.includes(4)"></td>
                        </tr>
                        <tr>
                            <th>Friday</th>
                            <td><input type="checkbox" disabled v-model="day_id" value="5"
                                    :checked="availability?.includes(5)"></td>
                        </tr>
                        <tr>
                            <th>Saturday</th>
                            <td><input type="checkbox" disabled v-model="day_id" value="6"
                                    :checked="availability?.includes(6)"></td>
                        </tr>
                        <tr>
                            <th>Sunday</th>
                            <td><input type="checkbox" disabled v-model="day_id" value="7"
                                    :checked="availability?.includes(7)"></td>
                        </tr>

                    </table>
                </div>
                <div class="card-footer">
                    <router-link :to="{ name: 'rating', params: { id: detail?.id } }" class="btn btn-primary">Add
                        Rating</router-link>
                    <router-link :to="{ name: 'template', params: { id: detail?.id, name: detail?.name } }"
                        class="btn btn-primary ms-2">Report</router-link>
                    <router-link :to="{ name: 'schedule', params: { id: detail?.id } }"
                        class="btn btn-primary ms-2">Schedule</router-link>
                    <router-link :to="{ name: 'upload', params: { id: detail?.id } }" class="btn btn-primary ms-2">Upload
                        File</router-link>
                </div>
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
            detail: [],
            availability: [],
            error: null,
        };
    },
    created() {
        this.getDetail();
    },
    methods: {
        async getDetail() {
            try {
                const response = await axios.get('/api/detail/' + this.id);
                this.detail = response?.data?.data;
                this.availability = this.getAvailability();
            } catch (err) {
                this.error = 'Failed to fetch data';
            }
        },
        getAvailability() {
            return this.detail?.availabilty?.map(element => element.day_id);
        }
    }
};
</script>
