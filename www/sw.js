const cache_ver = 'v2'

self.addEventListener('install', event => {
	event.waitUntil(caches.open(cache_ver).then(cache => {
		return cache.addAll([
			'script.js?v4',
			'style/style-comp.css?v4',
			'style/style-dark-comp.css?v4',
			'style/DroidNaskh-Regular.woff2',
			'style/Material-Icons.woff2',
		])
	}))
})

self.addEventListener('activate', event => {
	const cacheWhitelist = [cache_ver]
	event.waitUntil(caches.keys().then(keyList => {
		return Promise.all(keyList.map(key => {
			if(cacheWhitelist.indexOf(key) === -1)
				return caches.delete(key)
		}))
	}))
})

self.addEventListener('fetch', event => {
	event.respondWith(caches.match(event.request).then(resp => {
		return resp || fetch(event.request).then(r => r)
	}).catch(() => ''))
})
