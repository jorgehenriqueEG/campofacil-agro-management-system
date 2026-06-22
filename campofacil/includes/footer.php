<script src="../js/toast.js"></script>

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const rastro = window.location.pathname.includes('/pages/') || window.location.pathname.includes('/auth/') ? '../' : './';
        
        navigator.serviceWorker.register(rastro + 'sw.js')
        .then(reg => console.log('PWA: Service Worker registado com sucesso!', reg.scope))
        .catch(err => console.error('PWA: Falha ao registar o Service Worker:', err));
    });
}
</script>

</body>
</html>