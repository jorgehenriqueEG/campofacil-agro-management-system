self.addEventListener("install", e => {
  e.waitUntil(
    caches.open("campofacil").then(cache => {
      return cache.addAll([
        "login.php",
        "dashboard.php",
        "style.css"
      ]);
    })
  );
});