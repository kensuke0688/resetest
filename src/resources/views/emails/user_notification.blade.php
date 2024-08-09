<!DOCTYPE html>
<html>
<head>
    <title>お知らせ</title>
</head>
<body>
    <h1>{{ $subject }}</h1>
    <p>{!! nl2br(e($message)) !!}</p> <!-- メッセージの中に改行が含まれている場合、適切に表示するために nl2br を使用 -->
</body>
</html>