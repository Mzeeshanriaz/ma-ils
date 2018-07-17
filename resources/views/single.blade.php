<html>
<head>
    <link rel="stylesheet"
          href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="/js/jquery.js"></script>
    <style>
        #my_app_title{ color: #ffffff;}
        body{background-color: #0b2e13; }
        a:hover,a {color: #ffffff;text-decoration: none;}
        .col-md-2 a:hover,.col-md-2 a {color: #000000;text-decoration: none;
            width:100%;font-size:medium;}
        table td a:hover,table td a{color: #000000;text-decoration: none;}
        #newDiv{display: none;
            width:510px;
            right:3%;
            float: right;
            bottom:0;
            position: fixed;
            height:392px;}
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
    <title>Gmail - {{$message->getSubject()}}</title>
</head>
<body>
<div class="text-center">
    @if(LaravelGmail::check())
        <a href="{{ route('logout') }}">logout</a>
    @else
        <a href="{{ route('login') }}">login</a>
    @endif
</div>
<h1 id="my_app_title">{{$message->getSubject()}}
({{date('d/m/Y',strtotime($dated))}})
</h1>
<div class="container-fluid">
    <div class="row ">
        <div class="col-md-2" style="background-color: white;">
            @include('layouts.menu')
        </div>
        @if($message->hasAttachments())
            <div class=" col-md-8" style="background-color: whitesmoke">
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
                {!!html_entity_decode($message_body)!!}
            </div>
        </div>
    </div>
</div>
@include('layouts.send')
</body>
</html>