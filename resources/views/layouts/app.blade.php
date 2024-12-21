@if(session('success'))
    <div id="welcome-message" style="position: fixed; bottom: 10px; left: 10px; background: #28a745; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 9999;">
        {{ session('success') }}
    </div>
@endif

<script>
    setTimeout(() => {
        const message = document.getElementById('welcome-message');
        if (message) {
            message.style.transition = 'opacity 0.5s ease';
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 500);
        }
    }, 3000);
</script>

@yield('content')

