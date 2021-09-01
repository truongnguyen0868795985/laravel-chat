<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Chat app</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
            integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body>

@auth
    <div>{{ auth()->user()->name }}</div>
    <div><a href="/logout">Logout</a></div>
@else
    @foreach(\App\Models\User::all() as $user)
        <div><a href="/login/{{$user->id}}}">{{$user->name}}</a></div>
    @endforeach
@endauth


<div id="chat">
    <h1 class="chat-header">Phòng chat xàm</h1>
    <div class="chat-input">
        <input id="chat-input" type="text" name="messsage">
    </div>

    <div class="chat-body">
        <div class="message">
            {{--                    <span class="message-user-name">Trưởng: </span>--}}
            {{--                    <span class="message-user-message">Chat hahah</span>--}}
        </div>
    </div>
</div>

<script>
    window.laravel_echo_port = '{{env("LARAVEL_ECHO_PORT")}}';
</script>
<script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>
<script src="{{ asset('js/laravel-echo-setup.js') }}"></script>
<script>
    function sendMessage(message) {
        axios.post('message', {message})
    }

    $('#chat-input').on('keypress', function (e) {
        if (e.which == 13) {
            sendMessage(this.value)

            this.value = ''
        }
    });

    const myId = '{{ auth()->id() }}';

    window.Echo.private('ChatChannel')
        .listen('.ChatEvent', (data) => {
            console.log(data)
            console.log('id' + myId)

            let message = ''
            if (data.userId === +myId) {
                message = `<div class="message" style="color: dodgerblue; margin-left: 300px">`
            } else {
                message = `<div class="message" style="color: red;">`
            }

            message += `<span class="message-user-name">${data.userName}: </span>`
            message += `<span class="message-user-name">${data.message}</span>`
            message += `</div>`

            $('.chat-body').append(message);
        });
</script>
</body>
</html>
