<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feedback Submitted</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            /* background: url('{{ asset('upload/expiry_img.png') }}') no-repeat center center fixed;
            filter: grayscale(100%);
            background-size: cover; */
        }

        .main {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            width: 400px;
            max-width: 90%;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 10px 20px;
            font-size: 16px;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #d9534f;
            /* Bootstrap danger color for expiry */
            font-weight: 600;
        }

        .card p {
            margin-top: 10px;
            color: #06660e;
            font-weight: 300;
        }

        .card hr {
            margin: 20px 0;
            border: 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="card expired">
            <h2>Feedback Survey</h2>
            <hr>
            <p>{{ $data }}</p>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</html>
