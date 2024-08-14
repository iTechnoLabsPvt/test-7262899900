<!-- resources/views/reports/user_report.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>User Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table,
        .th,
        .td {
            border: 1px solid black;
        }

        .th,
        .td {
            padding: 8px;
            text-align: left;
        }

        .th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>User Report</h1>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5>Student improvement report card</h5>
                <p><strong>Student name:</strong> <a href="#">{{ $user->name }}</a></p>
                <p><strong>Session date:</strong> <a href="#">{{ date('Y-m-d')}}</a></p>
                <p><strong>Report card for period:</strong> <a href="#">{{ $from_date }}</a> to <a
                        href="#">{{ $to_date }}</a></p>
                <p><strong>Target:</strong> <a href="#">{{ $user->getAvgTarget()}}</a></p>
                <p><strong>Achieved:</strong> <a href="#">{{ $user->getAvgRating() }}</a></p><br />
                <p>Lorem Ipsum is simply <a href="#">{{ $user->name }}</a> text of the printing and typesetting
                    industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since <a
                        href="#">{{ date('Y-m-d')}}</a>,
                    when an unknown printer took a galley of type and scrambled it to make a type
                    specimen book. It has survived not only five centuries, but also the leap into electronic
                    typesetting, remaining essentially unchanged. It was popularized in the 1960s with the
                    release of Letraset sheets containing the achievement level <a
                        href="#">{{ $user->getAvgRating() }}</a>
                    out of
                    <a href="#">{{ $user->getAvgTarget()}}</a>, and more recently with desktop publishing software like Aldus
                    PageMaker
                    including versions of Lorem Ipsum.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
