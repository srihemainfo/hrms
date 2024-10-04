<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SVCET</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            width: 100%;
        }

        .section {
            position: relative;
        }

        .header {
            margin-top: 20px;
            /* padding: 5px; */
            position: absolute;
            left: 55%;
            transform: translateX(-45%);
            /* border: 1px solid black; */
            /* width: 100%; */
        }

        .logo_section {
            position: absolute;
            margin-top: 20px;
            padding-left: 20px;
        }

        .qr_section {
            width: 100%;
            margin: auto;
            text-align: center;
            margin-top: 150px;
        }

        .image_div {
            margin-top: 70px;
            height: 110px;
            width: 18%;
            text-align: center;
            /* margin-bottom: 20px; */
            display: inline-block;
        }

        .img {
            height: 100%;
            width: 80%;
        }

        .logo_div {
            width: 200px;
            height: 70px;
        }

        .logo_div img {
            width: 100%;
            height: 100%;
        }

        .collage-title {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="section">
        <div class="logo_section">
            <div class="logo_div">
                <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="">
            </div>
        </div>
        <div class="header">
            <span class="collage-title">Demo College OF ENGINNERING AND TECHNOLOGY</span>
            <h5 style="padding-left: 30%; font-weight: normal; padding-top: 20px">BOOK QR CODES</h5>
        </div>
    </div>

    <div class="qr_section">
        <div class="book_detail">
            <span><b>Book Name :</b> <em>{{ $book_name }}</em></span>
            <span><b>Book ISBN :</b> <em>{{ $isbn }}</em></span>
        </div>
        @foreach ($qrcode as $item)
            <div class="image_div">
                <img class="img" src="data:image/png;base64,{{ base64_encode($item['qrcode']) }}" alt="QR Code">
                <span style="">{{ $item['book_code'] }}</span>
            </div>
        @endforeach
    </div>


    {{-- <div>
        <h1 style="text-align: center;">SVCET Book QR Code</h1>
        <span style="text-align: center;"><b>Book Name : </b><em>{{ $response[0]['books']->name }}</em> <b>Book ISBN
                :</b><em>{{ $response[0]['books']->isbn }}</em></span>
        <div class="row" style="margin-top: 50px">
            @foreach ($response as $key => $item)
                <div class="col">
                    <img src="{{ public_path('qrcodes/' . $item->qrcode_image) }}" alt="Image {{ $key }}"
                        width="100px" height="100px">
                    <p>{{ $item->book_code }}</p>
                </div>
            @endforeach
        </div>
    </div> --}}
</body>

</html>
