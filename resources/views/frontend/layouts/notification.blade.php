<link rel="stylesheet" href="{{ asset('public/css/notification.css') }}">

<script src="{{ asset('public/js/notification.js') }}"></script>

<!-- Notification Container -->
<div id="elegant-notification-container"></div>

<script>
    // Container remains in Blade to ensure presence on all pages
    // alert override now lives in notification.js
</script>

@if(Session::get('delete'))
<script>
    ElegantNotification && ElegantNotification.error(@json(Session::get('delete')));
    @php(session()->forget('delete'))
</script>
@endif

@if(Session::get('error'))
<script>
    ElegantNotification && ElegantNotification.error(@json(Session::get('error')));
    @php(session()->forget('error'))
</script>
@endif

@if($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            ElegantNotification && ElegantNotification.error(@json($error));
        </script>
    @endforeach
@endif

@if(session('success'))
<script>
    ElegantNotification && ElegantNotification.success(@json(session('success')));
    @php(session()->forget('success'))
</script>
@endif
@if(session('error'))
<script>
    ElegantNotification && ElegantNotification.error(@json(session('error')));
    @php(session()->forget('error'))
</script>
@endif
@if(session('warning'))
<script>
    ElegantNotification && ElegantNotification.warning(@json(session('warning')));
    @php(session()->forget('warning'))
</script>
@endif