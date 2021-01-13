/*
 Copyright 2014 Google Inc. All Rights Reserved.
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 http://www.apache.org/licenses/LICENSE-2.0
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
*/

importScripts('cache-polyfill.js');

var CACHE_VERSION = 1;
var CURRENT_CACHES = {
    prefetch: 'prefetch-cache-v' + CACHE_VERSION
};

self.addEventListener('install', function(event) {
    var now = Date.now();

    var urlsToPrefetch = [
			'/favicon.png',
			'/img/logo-small.png',
			'/js/vendor/bootstrap.bundle.min.js',
			'/js/vendor/jquery-3.2.1.min.js',
			'/js/vendor/switchery.js',
			'/js/vendor/toastr.min.js',
			'/js/vendor/autosize.js',
			'/css/bootstrap.min.css',
			'/css/toastr.css',
			'/css/switchery.css',
			'/'
    ];

    console.log('Handling install event. Resources to prefetch:', urlsToPrefetch);

    event.waitUntil(
        caches.open(CURRENT_CACHES.prefetch).then(function(cache) {
            var cachePromises = urlsToPrefetch.map(function(urlToPrefetch) {
                var url = new URL(urlToPrefetch, location.href);
                url.search += (url.search ? '&' : '?') + 'cache-bust=' + now;

                var request = new Request(url, {mode: 'no-cors'});
                return fetch(request).then(function(response) {
                    if (response.status >= 400) {
                        throw new Error('request for ' + urlToPrefetch +
                            ' failed with status ' + response.statusText);
                    }

                    return cache.put(urlToPrefetch, response);
                }).catch(function(error) {
                    console.error('Not caching ' + urlToPrefetch + ' due to ' + error);
                });
            });

            return Promise.all(cachePromises).then(function() {
                console.log('Pre-fetching complete.');
            });
        }).catch(function(error) {
            console.error('Pre-fetching failed:', error);
        })
    );
});

self.addEventListener('activate', function(event) {
    var expectedCacheNames = Object.keys(CURRENT_CACHES).map(function(key) {
        return CURRENT_CACHES[key];
    });

    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (expectedCacheNames.indexOf(cacheName) === -1) {
                        console.log('Deleting out of date cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', function(event) {
    if (!navigator.onLine) {

        event.respondWith(
            caches.match(event.request).then(function (response) {
                if (response) {

                    return response;
                }

                console.log('No response found in cache. About to fetch from network...');

                return fetch(event.request).then(function (response) {
                    console.log('Response from network is:', response);

                    return response;
                }).catch(function (error) {
                    console.error('Fetching failed:', error);
                    throw error;
                });
            })
        );
    }
});