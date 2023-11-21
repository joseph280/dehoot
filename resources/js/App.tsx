import './bootstrap';
import React from 'react';
import { render } from 'react-dom';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { StateProvider } from './contexts/stateContext';

createInertiaApp({
  resolve: name => import(`./pages/${name}`),
  setup({ el, App, props }) {
    render(
      <StateProvider>
        <App {...props} />
      </StateProvider>,
      el,
    );
  },
});
