const CACHE_NAME = 'kms-online-v2';
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

// ===== PUSH NOTIFICATION =====
self.addEventListener('push', (event) => {
    if (!event.data) return;

    let payload;
    try {
        payload = event.data.json();
    } catch (e) {
        payload = { title: 'KMS Online', body: event.data.text() };
    }

    const title   = payload.title || 'KMS Online';
    const options = {
        body:     payload.body  || '',
        icon:     payload.icon  || '/assets/logoapk2.png',
        badge:    payload.badge || '/assets/logoapk2.png',
        data:     payload.data  || {},
        vibrate:  [200, 100, 200],
        tag:      'kms-notification',
        renotify: true,
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Klik notifikasi — buka halaman tujuan, fokus tab yang sudah ada kalau bisa
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url) || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (const client of windowClients) {
                if ('focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});