<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feedback Survey</title>
    {{-- EXTERNAL CSS --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Varela+Round&amp;display=swap">

    {{-- EXTERNAL JAVASCRIPT --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.3.4/gsap.min.js"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/MorphSVGPlugin3.min.js"></script>
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-size: 12px;
        }

        body {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }


        .main {
            width: 100%;
            max-width: 600px;
            height: 80%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            width: 800px;
            max-width: 90%;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            font-size: 12px;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #d9534f;
            font-weight: 600;
        }

        .card h3 {
            text-align: center;
            margin: 20px 0;
            color: #333;
            font-weight: 400;
        }

        .card label {
            display: block;
            margin-top: 10px;
            color: #333;
            font-weight: 600;
            font-size: 12px;
        }

        .card input[type="text"] {
            width: 200px;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-bottom: 1px solid black;
            outline: none;
        }

        .card input[type="text"]:focus {
            border-bottom: 1px solid #4f29f0;
        }

        .ques {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .question-item {
            margin-bottom: 20px;
        }

        .rate {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;

        }

        .rating {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 10px;
        }

        /* CSS */
        .button-48 {
            appearance: none;
            background-color: #FFFFFF;
            border-width: 0;
            box-sizing: border-box;
            color: #e72d27;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0;
            line-height: 1em;
            margin: 0;
            outline: 0;
            padding: 1.5em 2.2em;
            text-align: right;
            text-decoration: none;
            text-transform: uppercase;
            touch-action: manipulation;
            vertical-align: baseline;
            white-space: nowrap;
            border-radius: 10px;
        }

        .button-48:hover {
            color: #d9534f;
        }

        .button-48:focus {
            color: #d9534f;
        }

        .toast {
            background-color: #333;
            color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.success {
            background-color: #28a745;
        }

        .toast.error {
            position: absolute;
            right: 25px;
            background-color: #dc3545;
        }

        .toast .close {
            margin-left: 15px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        /* Simple fade-out animation */
        .toast.fade-out {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="card expired">
            <div id="toast-container">
                @if (session('message'))
                    <div class="toast success show">
                        {{ session('message') }}
                        <span class="close" onclick="closeToast(this)">×</span>
                    </div>
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="toast error show">
                            {{ $error }}
                            <span class="close" onclick="closeToast(this)">×</span>
                        </div>
                    @endforeach
                @endif
            </div>
            @php
                $decode = json_decode($datas);
            @endphp
            <form id="form-id" action="{{ route('admin.staff-feedback-form.store') }}" method="POST"
                onsubmit="return handleValidate(event)">
                @csrf
                <h2>Feedback Survey</h2>
                <hr>
                <h3>{{ $decode->feedback->name }}</h3>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ auth()->user()->name }}">
                </div>
                <div class="ques">
                    @php
                        $question = json_decode($decode->feedback->question);
                        $rate = ['Poor', 'Fair', 'Good', 'Very Good', 'Excelent'];
                    @endphp
                    <input type="hidden" name="ques_count" id="ques_count" value="{{ count($question) }}">
                    <input type="hidden" name="feedback_id" id="feedback_id" value="{{ $decode->feedback_id }}">
                    <input type="hidden" name="feed_id" id="feed_id" value="{{ $decode->id }}">
                    <input type="hidden" name="datas" id="datas" value="{{ $datas }}">
                    <table style="text-align: center;">
                        <thead>
                            <tr>
                                <th>Questions</th>
                                <th>Rating</th>
                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <div class="rate">
                                        @if ($rating)
                                            @for ($i = 0; $i < $rating; $i++)
                                                <div class="rating">
                                                    <label for="name">{{ $rate[$i] }}</label>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @foreach ($question as $key => $item)
                                <tr>
                                    <td
                                        style="text-transform: capitalize; text-align: left; max-width:100px; word-wrap: break-word;">
                                        {{ $item }}</td>
                                    <td>
                                        <div class="rate">
                                            @if ($rating)
                                                @for ($i = 0; $i < $rating; $i++)
                                                    <div class="rating">
                                                        <input style="margin-left: 17px;" type="radio"
                                                            name="ques{{ $key + 1 }}"
                                                            id="ques{{ $key + 1 }}" value="{{ $i + 1 }}"
                                                            title="{{ $rate[$i] }}">
                                                    </div>
                                                @endfor
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" style="text-align: right;">
                    <button class="button-48" type="submit" id="btn-submit" class="button">
                        <i class="fas fa-paper-plane"></i><span class="text"> Send</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    gsap.registerPlugin(MorphSVGPlugin);

    function handleValidate(event) {
        event.preventDefault(); // Prevent default form submission

        // Get the form element
        const form = document.getElementById('form-id');
        const name = document.getElementById('name').value;
        if (name == '') {
            alert('Enter Your Name');
            return false;
        }

        const ques_count = parseInt(document.getElementById('ques_count').value, 10);
        if (isNaN(ques_count) || ques_count <= 0) {
            console.error('Invalid question count.');
            alert('Invalid question count.');
            return false;
        }

        for (let i = 1; i <= ques_count; i++) {
            let rating = document.querySelector(`input[name="ques${i}"]:checked`);
            console.log(rating);
            if (!rating) {
                alert('Please rate all the questions.');
                return false;
            }
        }

        if (form) {
            form.submit();
        } else {
            console.error('Form is null during submission.');
        }
    }

    function closeToast(element) {
        const toast = element.parentElement;
        toast.classList.add('fade-out');
        setTimeout(() => {
            toast.remove();
        }, 500); // Time to match CSS transition
    }

    document.addEventListener('DOMContentLoaded', () => {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => {
                    toast.remove();
                }, 500); // Time to match CSS transition
            }, 3000); // Toast visibility time
        });
    });
</script>


</html>
