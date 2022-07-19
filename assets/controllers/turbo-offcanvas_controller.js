import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.watchClick()
    }

    watchClick() {
        this.element.addEventListener('click', function (event) {
            const path = event.target.getAttribute('data-path'),
                offcanva = document.querySelector('#project-show')

            offcanva.setAttribute('src', path)
        })
    }
}