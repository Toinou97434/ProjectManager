import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static target = ['sidebar']

    connect() {
        let sidebarElId = this.element.getAttribute('data-sidebar-target'),
            sidebarEl = document.getElementById(sidebarElId)

        this.initialeSidebar(sidebarEl)
        this.sidebarToggler(sidebarEl)
    }

    initialeSidebar(element) {
        let deviceWidth = window.innerWidth,
            mobileWidth = 768

        this.element.addEventListener('click', function (event) {
            event.preventDefault()

            if (element.classList.contains('hidden')) {
                if (deviceWidth <= mobileWidth) {
                    element.classList.add('show')
                    element.classList.remove('hidden')
                    document.querySelector('body').classList.remove('sidebar-hidden')
                } else {
                    element.classList.remove('hidden')
                    document.querySelector('body').classList.remove('sidebar-hidden')
                }
            } else {
                if (deviceWidth <= mobileWidth) {
                    element.classList.add('show')
                } else {
                    element.classList.add('hidden')
                    document.querySelector('body').classList.add('sidebar-hidden')
                }
            }
        })
    }

    sidebarToggler(element) {
        let closeBtn = element.querySelector('.btn-close')

        closeBtn.addEventListener('click', function (event) {
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden')
                document.querySelector('body').classList.remove('sidebar-hidden')
            } else {
                element.classList.remove('show')
                element.classList.add('hidden')
                document.querySelector('body').classList.add('sidebar-hidden')
            }
        })
    }
}