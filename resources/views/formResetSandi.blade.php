<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
</head>

<body>
    <form method="POST" action="{{ route('api.user.reset-password') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password" required>
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
        <button type="submit">Reset Password</button>
    </form>
</body>

</html>