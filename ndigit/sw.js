self.addEventListener('install', event => {
  console.log('✅ SW installé');
});

self.addEventListener('fetch', event => {
  // Logique de cache si besoin
});
