<template>
    <div class="card p-5">
        <div class="card-header text-center">
            <h2>Rate Student</h2>
        </div>
        <div class="card-body">
            <form @submit.prevent="addRating">
                <div class="star-rating">
                    <span v-for="index in totalStars" :key="index" class="star"
                        :class="{ filled: index <= (hover || form.rating) }" @click="setRating(index)"
                        @mouseover="setHover(index)" @mouseleave="clearHover">
                        ★
                    </span>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <input type="hidden" v-model="form.rating" id="first_name" class="form-control" />
                        <span v-if="ratingError" class="invalid-feedback d-block">{{ ratingError }}</span>
                    </div>
                    <div class="form-group">
                        <label for="middle_name" class="form-label">Comment:</label>
                        <textarea v-model="form.comment" id="comment" class="form-control" rows="5"></textarea>
                        <span v-if="commentError" class="invalid-feedback d-block">{{ commentError }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    <p v-if="errorMessage">{{ errorMessage }}</p>
                </div>
            </form>
        </div>

    </div>
</template>
<script>
import axios from 'axios';

export default {
    props: {
        value: {
            type: Number,
            default: 0
        },
        totalStars: {
            type: Number,
            default: 5
        },
        id: {
            type: String,
            default: '' // Provide a default value or set it to an empty string
        }
    },
    data() {
        return {
            form: {
                rating: '',
                comment: '',
                user_id: this.id
            },
            hover: 0,
            errorMessage: '',
            ratingError: '',
            commentError: '',
        };
    },
    methods: {
        setRating(index) {
            this.form.rating = index;
            this.$emit('input', index);
        },
        setHover(index) {
            this.hover = index;
        },
        clearHover() {
            this.hover = 0;
        },
        async addRating() {
            try {
                const token = localStorage.getItem('token');
                const response = await axios.post('/api/rating', this.form, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });
                if (response?.data?.success) {
                    this.$router.push({ name: 'home' });
                } else {
                    this.errorMessage = '';
                }
            } catch (error) {
                this.ratingError = error?.response?.data?.data?.rating ? error?.response?.data?.data?.rating[0] : '';
                this.commentError = error?.response?.data?.data?.comment ? error?.response?.data?.data?.comment[0] : '';
                this.errorMessage = error?.response?.data?.message;
            }
        },
    },
};
</script>
<style scoped>
.star {
    font-size: 2rem !important;
}
</style>
