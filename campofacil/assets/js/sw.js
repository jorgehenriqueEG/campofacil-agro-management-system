const CACHE_NAME = "campofacil-cache-v1";

// Ficheiros estáticos fundamentais para guardar em cache imediatamente
const ASSETS = [
  "index.php",
  "auth/login.php",
  "pages/dashboard.php",
  "pages/produtos.php",
  "pages/clientes.php",
  "pages/vendas.php",
  "pages/alertas.php",
  "css/style.css"
];

self.addEventListener("install", e => {
  e.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log("PWA: Guardando ficheiros estáticos em cache...");
      return cache.addAll(ASSETS);
    }).then(() => self.skipWaiting())
  );
});

self.addEventListener("activate", e => {
  e.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.map(key => {
          if (key !== CACHE_NAME) {
            console.log("PWA: Limpando cache antiga...", key);
            return caches.delete(key);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener("fetch", e => {
  if (e.request.method !== "GET" || e.request.url.includes("/api/")) {
    return;
  }

  e.respondWith(
    caches.match(e.request).then(cachedResponse => {
      if (cachedResponse) {
        fetch(e.request).then(networkResponse => {
          if (networkResponse.status === 200) {
            caches.open(CACHE_NAME).then(cache => cache.put(e.request, networkResponse));
          }
        }).catch(() => console.log("PWA: Modo offline detetado para este recurso."));
        
        return cachedResponse;
      }

      return fetch(e.request).then(networkResponse => {
        if (networkResponse.status === 200) {
          const responseClone = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(e.request, responseClone));
        }
        return networkResponse;
      });
    }).catch(() => {
      if (e.request.headers.get("accept").includes("text/html")) {
        return caches.match("pages/dashboard.php");
      }
    })
  );
});