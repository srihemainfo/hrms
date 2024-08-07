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

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-size: 12px;
        }

        body {

            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
            /* Uncomment and fix the image path if needed */
            /* background: url('{{ asset('upload/expiry_img.png') }}') no-repeat center center fixed;
            filter: grayscale(100%);
            background-size: cover; */
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
            border-radius: 15px;
            padding: 20px;
            width: 800px;
            max-width: 90%;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 10px 20px;
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
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .ques {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .question-item {
            margin-bottom: 20px;
        }

        .rating {
            display: flex;
        }

        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
            /* margin-left: 50px; */
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 20px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }

        .button {
            --text: #C3C8DE;
            --plane: #4F29F0;
            --background: #fff;
            display: flex;
            float: right;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 0;
            padding: 0;
            width: 140px;
            height: 60px;
            /* line-height: 30px; */
            background: none;
            color: var(--text);
            cursor: pointer;
            outline: none;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 10px 20px;
        }

        .button svg.btn-layer {
            display: block;
            position: absolute;
            top: -20px;
            left: 0;
            width: 100%;
            height: 100px;
            z-index: 2;
            pointer-events: none;
            fill: var(--background);
        }

        .button svg.plane {
            z-index: 3;
            position: absolute;
            left: 32px;
            margin-top: 10px;
            display: block;
            width: 26px;
            height: 28px;
            fill: var(--plane);
            transform: translate3d(0, 0, 0);
            perspective: 500px;
        }

        .button>ul {
            list-style: none;
            margin-top: 10px;
            padding: 0 5px 0 0;
            position: relative;
            overflow: hidden;
        }

        .button>ul>li {
            display: inline-block;
            position: relative;
            z-index: 2;
            padding-left: 40px;
            font: 400 16px "Varela Round", sans-serif;
            transition: transform 0.3s ease 0s, opacity 0.3s ease 0s;
        }

        .button>ul>li:first-child {
            opacity: 1;
        }

        .button>ul>li:last-child {
            position: absolute;
            left: 0;
            top: 100%;
            opacity: 0;
        }

        .button.active svg.plane {
            -webkit-animation: 1.5s orbit alternate linear;
            animation: 1.5s orbit alternate linear;
        }

        .button.active>ul>li {
            transform: translateY(-100%);
            transition: transform 0.3s ease 1.2s, opacity 0.3s ease 1.2s;
        }

        .button.active>ul>li:first-child {
            opacity: 0;
        }

        .button.active>ul>li:last-child {
            opacity: 1;
        }

        @-webkit-keyframes orbit {
            0% {
                transform: rotate3d(1, 0, 0, 0deg) translateZ(60px) scale3d(1, 1, 1);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in;
            }

            10% {
                z-index: 3;
                transform: rotate3d(1, 0.6, 0, -10deg) translateZ(60px) scale3d(1, 1, 1);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
            }

            20% {
                z-index: 3;
                transform: rotate3d(1, 0.6, 0, 30deg) translateZ(60px) scale3d(1, 1, 1);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
            }

            30% {
                z-index: 3;
                transform: rotate3d(1, 0.6, 0, 35deg) translateZ(60px) scale3d(1, 1, 1);
            }

            40% {
                z-index: 1;
                transform: rotate3d(1, 0.6, 0, 120deg) translateZ(60px) scale3d(0.7, 0.7, 1);
            }

            70% {
                z-index: 1;
                transform: rotate3d(1, 0.6, 0, 240deg) translateZ(60px) scale3d(0.7, 0.7, 1);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
            }

            100% {
                z-index: 3;
                transform: rotate3d(1, 0, 0, 360deg) translateZ(60px) scale3d(1, 1, 1);
            }
        }

        @keyframes orbit {
            0% {
                transform: rotate3d(1, 0, 0, 0deg) translateZ(60px) scale3d(1, 1, 1);
                -webkit-animation-timing-function: ease-in;
                animation-timing-function: ease-in;
            }

            10% {
                z-index: 3;
                transform: rotate3d(1, 0.6, 0, -10deg) translateZ(60px) scale3d(1, 1, 1);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
            }

            20% {
                z-index: 3;
                transform: rotate3d(1, 0.6, 0, 30deg) translateZ(60px) scale3d(1, 1, 1);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
            }

            30% {
                z-index: 3;
                transform: rotate3d(1, 0.6, 0, 35deg) translateZ(60px) scale3d(1, 1, 1);
            }

            40% {
                z-index: 1;
                transform: rotate3d(1, 0.6, 0, 120deg) translateZ(60px) scale3d(0.7, 0.7, 1);
            }

            70% {
                z-index: 1;
                transform: rotate3d(1, 0.6, 0, 240deg) translateZ(60px) scale3d(0.7, 0.7, 1);
                -webkit-animation-timing-function: ease-out;
                animation-timing-function: ease-out;
            }

            100% {
                z-index: 3;
                transform: rotate3d(1, 0, 0, 360deg) translateZ(60px) scale3d(1, 1, 1);
            }
        }

        body .socials {
            position: fixed;
            display: block;
            left: 20px;
            bottom: 20px;
        }

        body .socials>a {
            display: block;
            width: 30px;
            opacity: 0.2;
            transform: scale(var(--scale, 0.8));
            transition: transform 0.3s cubic-bezier(0.38, -0.12, 0.24, 1.91);
        }

        body .socials>a:hover {
            --scale: 1;
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

        select {
            width: 350px;
            /* outline: none; */
            border: 1px solid #dddddd;
            padding: .5rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 4px rgb(146 161 176 / 15%);
            cursor: pointer;
        }

        select:focus,
        select:hover {
            outline: none;
            border: 1px solid rgba(0, 0, 0, 0.329);
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
            <form id="form-id" action="{{ route('feedback.store') }}" method="POST"
                onsubmit="return handleValidate(event)">
                @csrf
                <h2>Feedback Survey</h2>
                <hr>
                <h3>{{ $data->feedback->name }}</h3>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email">
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <label for="dept">Department</label>
                    <select class="form-control select2" name="dept" id="dept">
                        <option value="">Select Department</option>
                        @foreach ($dept as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ques">
                    <label>Questions</label>
                    @php
                        $question = json_decode($data->feedback->question);
                    @endphp
                    <input type="hidden" name="ques_count" id="ques_count" value="{{ count($question) }}">
                    <input type="hidden" name="feedback_id" id="feedback_id" value="{{ $data->feedback_id }}">
                    <input type="hidden" name="feed_id" id="feed_id" value="{{ $data->id }}">
                    @foreach ($question as $key => $item)
                        <div class="first">
                            <label style="text-transform: uppercase;">{{ $item }}</label>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 rating">
                            <div class="rate">
                                <input type="radio" id="star{{ $key + 1 }}_5" name="ques{{ $key + 1 }}"
                                    value="5" />
                                <label for="star{{ $key + 1 }}_5" title="Excellence/Best/Good/Fair/Poor">5
                                    stars</label>

                                <input type="radio" id="star{{ $key + 1 }}_4" name="ques{{ $key + 1 }}"
                                    value="4" />
                                <label for="star{{ $key + 1 }}_4" title="Best/Good/Fair/Poor">4 stars</label>

                                <input type="radio" id="star{{ $key + 1 }}_3" name="ques{{ $key + 1 }}"
                                    value="3" />
                                <label for="star{{ $key + 1 }}_3" title="Good/Fair/Poor">3 stars</label>

                                <input type="radio" id="star{{ $key + 1 }}_2" name="ques{{ $key + 1 }}"
                                    value="2" />
                                <label for="star{{ $key + 1 }}_2" title="Fair/Poor">2 stars</label>
                                <input type="radio" id="star{{ $key + 1 }}_1" name="ques{{ $key + 1 }}"
                                    value="1" />
                                <label for="star{{ $key + 1 }}_1" title="Poor">1 star</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="submit" id="btn-submit" class="button">
                    <svg class="btn-layer">
                        <path
                            d="M136,77.5c0,0-11.7,0-12,0c-90,0-94.2,0-94.2,0s-10.8,0-25.1,0c-0.2,0-0.8,0-0.8,0c-2.2,0-4-1.8-4-4v-47  c0-2.2,1.8-4,4-4c0,0,0.6,0,0.9,0c39.1,0,61.1,0,61.1,0s3,0,69.1,0c0.2,0,0.9,0,0.9,0c2.2,0,4,1.8,4,4v47  C140,75.7,138.2,77.5,136,77.5z" />
                    </svg>
                    <svg class="plane">
                        <use xlink:href="#plane" />
                    </svg>
                    <ul>
                        <li>Send</li>
                        <li>Done</li>
                    </ul>
                </button>


                <!-- SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 100" id="btn-layer"
                        preserveAspectRatio="none">
                        <path
                            d="M133,77.5H7c-3.9,0-7-3.1-7-7v-41c0-3.9,3.1-7,7-7h126c3.9,0,7,3.1,7,7v41C140,74.4,136.9,77.5,133,77.5z" />
                    </symbol>
                    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 26" id="plane"
                        preserveAspectRatio="none">
                        <path
                            d="M5.25,15.24,18.42,3.88,7.82,17l0,4.28a.77.77,0,0,0,1.36.49l3-3.68,5.65,2.25a.76.76,0,0,0,1-.58L22,.89A.77.77,0,0,0,20.85.1L.38,11.88a.76.76,0,0,0,.09,1.36Z" />
                    </symbol>
                </svg>
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
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const dept = document.getElementById('dept');
        if (name == null) {
            alert('Enter the name');
            return false;
        }
        if (email == null) {
            alert('Enter the email');
            return false;
        }
        if (dept == null) {
            alert('Enter the Department');
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
            if (!rating) {
                alert('Please rate all the questions.');
                return false;
            }
        }

        document.querySelectorAll('.button').forEach(element => {
            let path = element.querySelector('.btn-layer path'),
                tl = gsap.timeline();

            if (element.classList.contains('active')) {
                return;
            }
            element.classList.add('active');
            if (form) {
                form.submit();
            } else {
                console.error('Form is null during submission.');
            }

            tl.to(path, {
                morphSVG: 'M136,77.5h-1H4.8H4c-2.2,0-4-1.8-4-4v-47c0-2.2,1.8-4,4-4c0,0,0.6,0,0.9,0C44,22.5,66,10,66,10  s3,12.5,69.1,12.5c0.2,0,0.9,0,0.9,0c2.2,0,4,1.8,4,4v47C140,75.7,138.2,77.5,136,77.5z',
                duration: .3,
                delay: .3
            }).to(path, {
                morphSVG: 'M136,77.5c0,0-11.7,0-12,0c-90,0-94.2,0-94.2,0s-10.8,0-25.1,0c-0.2,0-0.8,0-0.8,0c-2.2,0-4-1.8-4-4v-47  c0-2.2,1.8-4,4-4c0,0,0.6,0,0.9,0c39.1,0,61.1,0,61.1,0s3,0,69.1,0c0.2,0,0.9,0,0.9,0c2.2,0,4,1.8,4,4v47  C140,75.7,138.2,77.5,136,77.5z',
                duration: 10.7,
                ease: 'elastic.out(5, 5.15)',
                onComplete() {
                    element.classList.remove('active');
                }
            });
        });

        return false;
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
