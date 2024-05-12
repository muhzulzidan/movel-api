<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Page</title>
</head>
<body>
    <h1>Test Page</h1>

    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        Echo.channel('test-channel')
            .listen('.TestEvent', (e) => {
                console.log(e.message);
            });
    </script>
</body>
</html>