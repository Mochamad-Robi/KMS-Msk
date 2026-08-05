const CACHE_NAME = 'kms-online-v1';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
    '/',
    '/offline.html',
    '/assets/logoapk2.png',
];

// Install — cache asset penting
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
    self.skipWaiting();
});

// Activate — bersihkan cache lama
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

// Fetch — network first, fallback ke offline page kalau gagal
self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(OFFLINE_URL);
            })
        );
        return;
    }

    // Untuk asset statis (CSS/JS/gambar), coba cache dulu baru network
    event.respondWith(
        caches.match(event.request).then((cached) => {
            return cached || fetch(event.request).catch(() => {
                // Kalau gambar gagal load & tidak ada di cache, biarkan gagal natural
                return new Response('', { status: 408 });
            });
        })
    );
});