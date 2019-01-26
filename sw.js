const CACHE_NAME = 'pwa-demo-edteam-cache-v1';
      
      
 const urlsToCache = [
    './',
    './?utm=homescreen',
    './index.html',
    './index.html?utm=homescreen', 
    './favicon.ico',
    './img/icon_192x192.png',
    './src/lector/js/jquery-3.3.1.min.js',
    './src/lector/js/popper.min.js',
    './src/lector/js/bootstrap.min.js',
    './src/lector/js/bootstrap-select.min.js',
    './src/lector/js/mdb.min.js',
    './vistas/lector/favoritos.php',
    './vistas/lector/footer.php',
    './vistas/lector/header.php',
    './vistas/lector/home.php',
    './vistas/lector/miPerfil.php',
    './app/miPerfil.js',
    './app/favoritos.js',
    './app/buscadorModerno.js',
    './app/lector.js',
    './src/lector/css/bootstrap-select.min.css',
    './src/lector/css/bootstrap.min.css',
    './src/lector/css/mdb.min.css',
    './src/lector/css/my.css',
    './src/lector/css/simplePagination.css',
    './src/inicial/css/estilosGlobales.css',
    './src/inicial/css/src/admin/vendor/bootstrap-tags/dist/bootstrap-tagsinput.css',
    './src/lector/plugins/flipbook/css/lightbox.css',
    './src/lector/plugins/flipbook/css/font-awesome.min.css'
  ]


self.addEventListener('install', e => {
  console.log('Evento: SW Instalado')
  e.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache)
        .then( () => self.skipWaiting() )
        //skipWaiting forza al SW a activarse
      })
      .catch(err => console.log('Falló registro de cache', err) )
  )
})

self.addEventListener('activate', e => {
  console.log('Evento: SW Activado')
  const cacheWhitelist = [CACHE_NAME]

  e.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all( 
        cacheNames.map(cacheName => {
          //Eliminamos lo que ya no se necesita en cache
          if ( cacheWhitelist.indexOf(cacheName) === -1 )
            return caches.delete(cacheName)
        })
      )
    })
    .then(() => {
      console.log('Cache actualizado')
      // Le indica al SW activar el cache actual
      return self.clients.claim()
    })
  )
})

self.addEventListener('fetch', e => {
  console.log('Evento: SW Recuperando')

  e.respondWith(
    //Miramos si la petición coincide con algún elemento del cache
    caches.match(e.request)
      .then(res => {
        console.log('Recuperando cache')
        if ( res ) {
          //Si coincide lo retornamos del cache
          return res 
        }
        //Sino, lo solicitamos a la red
        return fetch(e.request)
          
      })
    )
})

self.addEventListener('push', e => {
  console.log('Evento: Push')

  let title = 'Push Notificación Demo',
    options = {
      body: 'Click para regresar a la aplicación',
      icon: './img/icon_192x192.png',
      vibrate: [100, 50, 100],
      data: { id: 1 },
      actions: [
        { 'action': 'Si', 'title': 'Amo esta aplicación :)', icon: './img/icon_192x192.png' },
        { 'action': 'No', 'title': 'No me gusta esta aplicación :(', icon: './img/icon_192x192.png' }
      ]
    }

    e.waitUntil( self.registration.showNotification(title, options) )
})

self.addEventListener('notificationclick', e => {
  console.log(e)

  if ( e.action === 'Si' ) {
    console.log('AMO esta aplicación')
    clients.openWindow('https://ed.team')
  } else if ( e.action === 'No' ) {
    console.log('No me gusta esta aplicación')
  }

  e.notification.close()
})

self.addEventListener('sync', e => {
  console.log('Evento: Sincronización de Fondo', e)

  //Revisamos que la etiqueta de sincronización sea la que definimos o la que emulan las devtools
  if ( e.tag === 'github' || e.tag === 'test-tag-from-devtools' ) {
    e.waitUntil(
      //Comprobamos todas las pestañas abiertas y les enviamos postMessage
      self.clients.matchAll()
        .then(all => {
          return all.map(client => {
            return client.postMessage('online')
          })
        })
        .catch( err => console.log(err) )
    )
  }
})

/* self.addEventListener('message' e => {
  console.log('Desde la Sincronización de Fondo: ', e.data)
  fetchGitHubUser( localStorage.getItem('github'), true )
}) */
