<div id="newDiv">
    <div id="inner">
        <div id="heading">
            New Message
            <a id="close" class="m-link"> X </a>
        </div>
        <form enctype="multipart/form-data" name="pop-form" method="post" action="{{route('send.email')}}">
            @csrf
            <input type="email" name="email" class="to" placeholder="To" />
            <input type="text" name="subject" class="to" placeholder="Subject" />
            <textarea name="message" class="message" placeholder="Message" ></textarea>
            <input type="file" name="file" class="to" placeholder="Subject" />
            <div>
                <button type="submit" class="btn btn-info">  Send  </button>
            </div>
        </form>
    </div>
</div>
