/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import 'aos/dist/aos.css';
import AOS from 'aos';

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all');

const bootstrap = require('bootstrap');

const toastList = document.getElementsByClassName('toast');
if (!sessionStorage.noToast) {
    toastList.forEach((toast) => (new bootstrap.Toast(toast)).show());
}

const dismissButtons = document.getElementsByClassName('btn-dismiss');
dismissButtons.forEach((dismiss) => {
    dismiss.addEventListener('click', () => {
        sessionStorage.setItem('noToast', true);
    });
});

AOS.init();
