<section class="max-w-3xl mx-auto px-4 py-24 text-center">
  <svg class="mx-auto mb-6 opacity-30" width="88" height="88" viewBox="0 0 24 24" fill="none" stroke="#0F2C4C" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M12 3l8 3v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6z"/><path d="M6 15l3.5-5 2.5 3 2-2.5L18 15z"/>
  </svg>
  <h1 class="font-heading font-bold text-3xl text-navy">Page not found</h1>
  <p class="text-ink-500 mt-3 max-w-md mx-auto">The page you're looking for doesn't exist or has been moved. Check the address, search the site, or start again from the homepage.</p>

  <form action="<?= url('/search') ?>" method="get" class="mt-8 flex gap-2 max-w-md mx-auto">
    <label for="notfound-q" class="sr-only">Search the website</label>
    <input id="notfound-q" name="q" type="search" placeholder="Search the website…" class="field-input bg-white">
    <button type="submit" class="btn btn-primary shrink-0">Search</button>
  </form>

  <div class="mt-6 flex justify-center gap-3 flex-wrap">
    <a href="<?= url('/') ?>" class="btn btn-primary">Go to Homepage</a>
    <a href="<?= url('/contact') ?>" class="btn btn-outline">Contact the Office</a>
  </div>
</section>
