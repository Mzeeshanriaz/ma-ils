<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="/js/jquery.js"></script>
    <style>
        h1{ color: #ffffff;}
        body{background-color: #0b2e13; }

        /*.table-striped>tbody>tr:nth-of-type(even){*/
            /*background-color: #f9f9f9;*/
        /*}*/
        a:hover,a {color: #ffffff;text-decoration: none;}
        table td a:hover,table td a{color: #000000;text-decoration: none;}
        #newDiv{display: none;
            width:510px;
            right:3%;
            float: right;
            bottom:0;
            position: fixed;
            height:362px;}
        #inner{
            position: absolute;      width:507px; height: 360px;
        }
        #heading{background-color: black;
            color: #ffffff;
            padding:10px 10px 10px 5px;}
        .m-link{float:right;
            right:0;
            color: #ffffff;}
        a.m-link {margin-left: 13px;}
        .to{
            width:100%;
            height:40px;
            border:1px solid gray;
            background-color: whitesmoke;}
        .message{
            width:100%;
            border:1px solid gray;
            background-color: whitesmoke;
            height:200px;}
    </style>
</head>
<body>
<div class="text-center">
    @if(LaravelGmail::check())
        <a href="{{ route('logout') }}">logout</a>
    @else
        <a href="{{ route('login') }}">login</a>
    @endif
</div>
<h1>{{$messages->getSubject()}}</h1>
<div class="container-fluid">
    <div class="row ">
        <div class="col-md-2">
            @include('layouts.menu')
        </div>
        @if($messages->hasAttachments())
            <div class=" col-md-7" style="background-color: whitesmoke">
                Attachments:
                @foreach($attachments  as $attachment)
                    <a style="color: #23d027;" href="/attachment/download/{{$attachment->id}}" download>
                        {{$attachment->name}}
                    </a>,
                @endforeach
            </div><br/>
        @endif
        <div class=" col-md-8" style="background-color: #ffffff !important;">
            <div style="overflow-y: scroll;max-height: 570px;">
{{--                {{dd($messages)}}--}}
                {!!html_entity_decode($messages->getHtmlBody())!!}
            </div>
        </div>
    </div>
</div>
<div id="newDiv">
    <div id="inner">
        <div id="heading">
            New Message
            <a id="close" class="m-link"> X </a>
        </div>
        <form name="pop-form" method="post" action="{{route('send.email')}}">
            @csrf
            <input type="email" name="email" class="to" placeholder="To" />
            <input type="text" name="subject" class="to" placeholder="Subject" />
            <textarea name="message" class="message" placeholder="Message" ></textarea>
            <div>
                <button type="submit" class="btn btn-info">  Send  </button>
            </div>
        </form>
    </div>
</div>
<div id="newDiv">
    <div id="inner">
        <div id="heading">
            New Message
            <a id="close" class="m-link"> X </a>
        </div>
        <form name="pop-form" method="post" action="{{route('send.email')}}">
            @csrf
            <input type="email" name="email" class="to" placeholder="To" />
            <input type="text" name="subject" class="to" placeholder="Subject" />
            <textarea name="message" class="message" placeholder="Message" ></textarea>
            <div>
                <button type="submit" class="btn btn-info">  Send  </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>