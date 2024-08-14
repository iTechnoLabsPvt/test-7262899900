<!DOCTYPE html>
<html>
<head>
    <title>Laravel & Vue.js</title>
    @vite('resources/js/app.js')
</head>
<body>
    <style>
        .star-rating {
    display: inline-flex;
}

.star {
    font-size: 1rem;
    cursor: pointer;
    transition: color 0.3s;
}

.star.filled {
    color: gold;
}
    </style>
    <div id="app">
    </div>
</body>
</html>
