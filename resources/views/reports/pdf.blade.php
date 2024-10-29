<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
</head>
<body>
    <h1>Charts Report</h1>
    @foreach ($chartPaths as $url)
        <img src="{{ $url }}" alt="Chart">
    @endforeach
</body>
</html>
