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
    });
  </script>
@endsection