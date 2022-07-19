import { Controller } from '@hotwired/stimulus';
import { Tooltip } from 'bootstrap/dist/js/bootstrap.bundle';

export default class extends Controller {
    connect() {
        return new Tooltip(this.element)
        // const toastContainer = document.getElementById('toast-container');
        // if (this.element.parentNode !== toastContainer) {
        //     toastContainer.appendChild(this.element);
        //     return;
        // }
        // const toast = new Toast(this.element);
        // toast.show();
    }
}