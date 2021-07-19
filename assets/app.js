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

const bootstrap = require('bootstrap');

const toastList = [].slice.call(document.querySelectorAll('.toast'));
if (!sessionStorage.noToast) {
    toastList.map((toast) => (new bootstrap.Toast(toast)).show());
} else {
    toastList.forEach((toast) => {
        toast.style.display = 'none';
    });
}

const dismissButtons = document.getElementsByClassName('btn-dismiss');
dismissButtons.forEach((dismiss) => {
    dismiss.addEventListener('click', () => {
        sessionStorage.setItem('noToast', true);
    });
});
