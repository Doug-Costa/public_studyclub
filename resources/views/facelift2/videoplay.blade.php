<?php
$offset = (Request()->segment(1) == 'facelift25') ? 1 : 0;
$id = Request()->segment(2 + $offset);
// Try to get hash from segment 3 or query parameter 'h'
$hash = Request()->segment(3 + $offset) ?? Request()->input('h');
?>

<body style="background: #000; margin: 0; padding: 0; overflow: hidden;">
    @if($id)
        <div id="vimeo-player" 
             data-vimeo-id="{{ $id }}" 
             data-vimeo-url="https://vimeo.com/{{ $id }}{{ $hash ? '/' . $hash : '' }}"
             data-vimeo-watch-later="false" 
             data-vimeo-responsive="true" 
             data-vimeo-autoplay="true"
             style="width: 100%; height: 100vh;"></div>
    @else
        <div style="color: #fff; text-align: center; padding-top: 50px; font-family: sans-serif;">
            Vídeo não encontrado.
        </div>
    @endif
</body>

<script src="https://player.vimeo.com/api/player.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const playerEl = document.getElementById('vimeo-player');
        if (playerEl) {
            const options = {
                autoplay: true,
                loop: false,
                responsive: true,
                watch_later: false
            };
            
            try {
                const player = new Vimeo.Player(playerEl, options);
                player.on('play', function() {
                    console.log('Video playing');
                });
                player.on('error', function(error) {
                    console.error('Vimeo Error:', error);
                });
            } catch (e) {
                console.error('Error initializing Vimeo player:', e);
            }
        }
    });
</script>