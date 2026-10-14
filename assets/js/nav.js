(() => {
  'use strict';

  const nav = document.querySelector('.side-nav');
  const app = document.querySelector('.app');
  if (!nav || !app) return;

  nav.id = nav.id || 'primary-nav';

  /* Build a mobile top bar + toggle from the existing logo link so it
     keeps working on every page regardless of its relative links. */
  const logo = nav.querySelector('.logo');
  const brandHref = logo?.getAttribute('href') || 'main_page.php';
  const brandName = logo?.querySelector('b')?.textContent?.trim() || 'The Chess Room';

  const bar = document.createElement('header');
  bar.className = 'mobile-bar';

  const toggleBtn = document.createElement('button');
  toggleBtn.type = 'button';
  toggleBtn.className = 'nav-toggle-btn';
  toggleBtn.setAttribute('aria-label', 'Open navigation');
  toggleBtn.setAttribute('aria-expanded', 'false');
  toggleBtn.setAttribute('aria-controls', nav.id);
  toggleBtn.innerHTML =
    '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="18" y2="18"/></svg>';

  const brand = document.createElement('a');
  brand.className = 'mobile-brand';
  brand.href = brandHref;
  brand.innerHTML = '<i>&#9822;</i>';
  const brandText = document.createElement('span');
  brandText.textContent = brandName;
  brand.appendChild(brandText);

  bar.appendChild(toggleBtn);
  bar.appendChild(brand);
  app.insertBefore(bar, app.firstChild);

  const scrim = document.createElement('div');
  scrim.className = 'nav-scrim';
  document.body.appendChild(scrim);

  const mq = window.matchMedia('(max-width: 860px)');

  function isOpen() {
    return nav.classList.contains('is-open');
  }

  function openNav() {
    nav.classList.add('is-open');
    scrim.classList.add('is-open');
    document.body.classList.add('nav-open');
    toggleBtn.setAttribute('aria-expanded', 'true');
    const firstLink = nav.querySelector('a');
    if (firstLink) firstLink.focus({ preventScroll: true });
  }

  function closeNav() {
    if (!isOpen()) return;
    nav.classList.remove('is-open');
    scrim.classList.remove('is-open');
    document.body.classList.remove('nav-open');
    toggleBtn.setAttribute('aria-expanded', 'false');
    toggleBtn.focus({ preventScroll: true });
  }

  toggleBtn.addEventListener('click', () => (isOpen() ? closeNav() : openNav()));
  scrim.addEventListener('click', closeNav);

  nav.addEventListener('click', (event) => {
    if (mq.matches && event.target.closest('a')) closeNav();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeNav();
  });

  const onChange = (event) => {
    if (!event.matches) closeNav();
  };
  if (mq.addEventListener) mq.addEventListener('change', onChange);
  else mq.addListener(onChange);
})();
