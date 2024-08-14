<template>
    <div class="card p-5">
        <div class="card-header text-center">
            <h2>Create Student</h2>
        </div>
        <div class="card-body">
            <form @submit.prevent="generateReport">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="first_name" class="form-label">Student:</label>
                        <input type="text" v-model="form.student_name" id="student" class="form-control" disabled />
                        <span v-if="studentError" class="invalid-feedback d-block">{{ studentError }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="from_date" class="form-label">From Date:</label>
                        <input type="date" v-model="form.from_date" id="from_date" class="form-control" />
                        <span v-if="fromDateError" class="invalid-feedback d-block">{{ fromDateError }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name" class="form-label">To Date:</label>
                        <input type="date" v-model="form.to_date" id="to_date" class="form-control" />
                        <span v-if="toDateError" class="invalid-feedback d-block">{{ toDateError }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="split_interval" class="form-label">Split session in minutes</label>
                    <select v-model="form.split_interval" id="split_interval" class="form-control">
                        <option value="15"> 15 minutes</option>
                        <option value="10"> 10 minutes</option>
                        <option value="5"> 5 minutes</option>
                        <option value="2"> 2 minutes</option>
                    </select>
                    <span v-if="intervalError" class="invalid-feedback d-block">{{ intervalError }}</span>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                <p v-if="errorMessage">{{ errorMessage }}</p>
            </form>
        </div>

    </div>
</template>
<script>
import axios from 'axios';

export default {
    props: ['id', 'name'],
    data() {
        return {
            form: {
                student: this.id,
                student_name: this.name,
                from_date: '',
                to_date: '',
                split_interval: '15'
            },
            studentError: '',
            fromDateError: '',
            toDateError: '',
            intervalError: '',
            errorMessage: '',
            splitFiles: []

        };
    },
    methods: {
        async generateReport() {
            try {
                const response = await axios.post('/api/report', this.form);

                this.splitFiles = response.data.files;

                console.log(this.splitFiles);
                this.splitFiles.forEach(fileUrl => {

                    const link = document.createElement('a');
                    link.href = fileUrl;
                    link.download = fileUrl.split('/').pop();
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
                if (response?.data?.success) {
                    const id = response?.data?.id;
                    this.$router.push({ name: 'view', params: { id: id } });
                }
            } catch (error) {
                this.fromDateError = error?.response?.data?.data?.from_date ? error?.response?.data?.data?.from_date[0] : '';
                this.toDateError = error?.response?.data?.data?.to_date ? error?.response?.data?.data?.to_date[0] : '';
                this.intervalError = error?.response?.data?.data?.split_interval ? error?.response?.data?.data?.split_interval[0] : '';
                this.form.errorMessage = error?.response?.data?.message;
            }
        }
    },
};
</script>
