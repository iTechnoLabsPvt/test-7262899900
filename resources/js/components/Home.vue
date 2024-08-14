<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Students List</h1>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <td>Email</td>
                            <th>Date of birth</th>
                            <td>Rating</td>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in list" :key="user.id">
                            <td>{{ user?.id }}</td>
                            <td>{{ user?.name }}</td>
                            <td>{{ user?.email }}</td>
                            <td>{{ user?.date_of_birth }}</td>
                            <td><div class="star-rating">
                    <span v-for="index in 5" :key="index" class="star"
                        :class="{ filled: index <= (user?.rating) }">
                        ★
                    </span>
                </div></td>
                            <td> <router-link :to="{ name: 'view', params: { id: user?.id } }">View</router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            list: [],
            error: null,
        };
    },
    created() {
        this.getList();
    },
    methods: {
        async getList() {
            try {
                const token = localStorage.getItem('token');
                const response = await axios.get('/api/list');
                this.list = response?.data?.data;
            } catch (err) {
                this.error = 'Failed to fetch data';
            }
        }
    }
};
</script>
