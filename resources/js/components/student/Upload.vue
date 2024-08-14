<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Upload Docx File
            </div>
            <div class="card-body">
                <input type="file" @change="handleUpload" class="form-control" />
                <span v-if="uploadError" class="invalid-feedback d-block">{{ uploadError }}</span>
                <button @click="upload" class="btn btn-primary mt-3">Upload</button>
                <small v-if="uploading">Uploading: {{ uploadProgress }}%</small>

            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    props : ['id'],
    data() {
        return {
            file: '',
            user_id : this.id,
            uploading: false,
            uploadProgress: 0,
            uploadError: null


        };
    },
    methods: {
        handleUpload(event) {
            this.file = event.target.files[0];
        },
        async upload() {
            if (!this.file) {
                this.uploadError = 'file is required';
                return;
            }
            const formData = new FormData();
            formData.append('file', this.file);
            formData.append('user_id',this.id);
            this.uploading = true;
            this.uploadSuccess = false;
            this.uploadError = null;
            try {
                const response = await axios.post('/api/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (progressEvent) => {
                        this.uploadProgress = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                    }
                });
                if (response?.data?.success) {
                    this.$router.push({ name: 'home' });
                } else {
                    this.errorMessage = '';
                }
            } catch (error) {
                console.log()
                this.uploadError = error?.response?.data?.data?.file ? error?.response?.data?.data?.file[0] : '';
                this.errorMessage = error?.response?.data?.message;
            }
        },
    },
};
</script>
