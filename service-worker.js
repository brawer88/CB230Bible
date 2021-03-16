"use strict";

const CACHE_NAME = 'static-cache-v1.2.0';

const FILES_TO_CACHE = [
  'https://brawer.dev/BrakeBible/index.php',
  'https://brawer.dev/BrakeBible/PartPortal.php'
  ];


importScripts('https://storage.googleapis.com/workbox-cdn/releases/3.5.0/workbox-sw.js');

if (workbox) {
  console.log(`Yay! Workbox is loaded 🎉`);

  workbox.precaching.precacheAndRoute([]);

} 

workbox.routing.registerRoute(
  /\.(?:js|css|html|gif)$/,
  workbox.strategies.networkFirst({
  	cacheName: CACHE_NAME
  })
  );

self.addEventListener('install', event => {
  console.log('V1 installing…');
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
     return cache.addAll(FILES_TO_CACHE);
     })
  );
});


self.addEventListener('activate', (evt) => {
  console.log('[ServiceWorker] Activate');
  evt.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(keyList.map((key) => {
        if (key !== CACHE_NAME) {
          console.log('[ServiceWorker] Removing old cache', key);
          return caches.delete(key);
        }
      }));
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', function (event) {
  //nothing for now
});



