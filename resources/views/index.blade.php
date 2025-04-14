@extends('layouts.public.master')
@section('content')
 
       <!--  Header Start -->
       @include('layouts.public.header')
       <div class="container-fluid">
       <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
  <a href="/" class="position-relative">
    🔔 Notifications
    <span id="notif-count" class="badge badge-danger" style="display: none; position:absolute; top:0; right:0;">
        0
    </span>
</a>
</div>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>
 
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('3abca8d045835fc7ea6d', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('blood-requests');
    channel.bind('new-blood-request', function(data) {
      alert(JSON.stringify(data));
      updateNotificationBadge();
    });

    function updateNotificationBadge() {
        let badge = document.getElementById('notif-count');
        if (badge) {
            badge.innerText = parseInt(badge.innerText || 0) + 1;
            badge.style.display = 'inline-block';
        }
      }
  </script>
@endsection