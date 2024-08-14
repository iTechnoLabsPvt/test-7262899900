<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Template {{ name }}</h3>
            </div>
            <div class="card-body">
                <h5>Student improvement report card</h5>
                <p><strong>Student name:</strong> <a href="#">@student_full_name</a></p>
                <p><strong>Session date:</strong> <a href="#">@session_date</a></p>
                <p><strong>Report card for period:</strong> <a href="#">@target_start_date</a> to <a
                        href="#">@target_end_date</a></p>
                <p><strong>Target:</strong> <a href="#">@target</a></p>
                <p><strong>Achieved:</strong> <a href="#">@session_rating</a></p><br />
                <p>Lorem Ipsum is simply <a href="#">@student_full_name</a> text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since <a href="#">@session_date</a>,
                    when an unknown printer took a galley of type and scrambled it to make a type
                    specimen book. It has survived not only five centuries, but also the leap into electronic
                    typesetting, remaining essentially unchanged. It was popularized in the 1960s with the
                    release of Letraset sheets containing the achievement level <a href="#">@session_rating</a> out of
                    <a href="#">@target</a>, and more recently with desktop publishing software like Aldus PageMaker
                    including versions of Lorem Ipsum.
                </p>
            </div>
            <div class="card-footer text-end">
                <router-link :to="{ name: 'generateReport', params: { id: id, name:name } }" class="btn btn-primary">Generate Report</router-link>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    props : ['id','name'],
    data() {
        return {
            error: null,
        };
    }
};
</script>
