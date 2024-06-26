<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{$data['url']}}" method="post">
        <input type="text" name="newPass">
        <input type="submit" value="Send">
    </form>
</body>
</html> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data['title']}}</title>
</head>
<body>
    <p>{{$data['body']}}</p>
    <p>{{$data['code']}}</p>
    <p>Thank you.</p>
</body>
</html>