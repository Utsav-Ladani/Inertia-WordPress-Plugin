import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import { createInertiaApp } from '@inertiajs/react'
import './styles.scss';

console.log('JS is loaded');

domReady(() => {
  console.log('Initializing Inertia...');
  createInertiaApp({
    id: 'inertia',
    resolve: name => {
      console.log('Resolving page:', name);
      return require(`./Pages/${name}`);
    },
    setup({ el, App, props }) {
      console.log('Setting up page:', el, App, props);
      createRoot(el).render(<App {...props} />)
    },
  })
});
