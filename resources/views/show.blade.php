<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        h1{ color: #ffffff;}
        body{background-color: #0b2e13; }
        .table-striped>tbody>tr:nth-of-type(even){
            background-color: #f9f9f9;
        }
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
    <script src="/js/jquery.js"></script>
</head>
<body>
<div class="text-center">
    @if(LaravelGmail::check())
        <a href="{{ url('oauth/gmail/logout') }}">logout</a>
    @else
        <a href="{{ url('oauth/gmail') }}">login</a>
    @endif
</div>
<h1>All Email messages</h1>
<div class="container-fluid">
    <div class="row ">
        <div class="col-md-2"  style="background-color: white;">
            @include('layouts.menu')
        </div>
        <div class=" col-md-10">
            <form action="/trash" method="post">@csrf
            @if($box!='trash')
            Move to:<button type="submit" class="btn btn-danger">Trash</button>
            @endif
            <br/>
            <div class="row">
                <h2 style="background-color: white;color: #ffffff;">
                    <div class="col-md-4 text-center"><a href="/gmail/inbox/1">Primay</a></div>
                    <div class="col-md-4 text-center"><a href="/gmail/inbox/2">Social</a></div>
                    <div class="col-md-4 text-center"><a href="/gmail/inbox/3">Promotions</a></div>
                </h2>
            </div>
            <table class="table table-striped" >
                @foreach( $messages as $message )
                    <tr>
                        <td>
                            <input type="checkbox" name="email[]" value="{{$message->email_id}}" />
                            <a href="{{route('message',['id'=>$message->email_id])}}" >
                            <b>{{$message->name}}</b>
                            </a>
                        </td>
                        <td>
                            <a href="{{route('message',['id'=>$message->email_id])}}" >
                                @if($message->is_unread)
                                    <b>{{$message->subject}}</b>
                                @else
                                    {{$message->subject}}
                                    @endif
                            </a>
                            <sup class="text-right">
                                @if($message->attachments()->exists())
                                    <img height="20px" src="/mail_attachment.png" align="right"
                                    @endif
                            </sup>
                        </td>
                        <td style="min-width: 80px;">

                            <a href="{{route('message',['id'=>$message->email_id])}}" >
                                <b>{{date('M d', strtotime($message->dated))}}</b>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{$messages->links()}}
            </form>
        </div>
    </div>
</div>
@include('layouts.send')
</body>
</html>