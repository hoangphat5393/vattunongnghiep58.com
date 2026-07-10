import $ from 'jquery';
import AOS from 'aos';
import Swiper from 'swiper/bundle';
import http from './http';

class SimpleModal {
    constructor(element) {
        this.element = element;
    }

    show() {
        if (!this.element) return;
        this.element.style.display = 'block';
        this.element.classList.add('simple-modal-open');
        this.element.setAttribute('aria-hidden', 'false');
    }

    hide() {
        if (!this.element) return;
        this.element.style.display = 'none';
        this.element.classList.remove('simple-modal-open');
        this.element.setAttribute('aria-hidden', 'true');
        const hiddenEvent = new CustomEvent('hidden.bs.modal', {
            bubbles: true,
        });
        this.element.dispatchEvent(hiddenEvent);
    }

    static getOrCreateInstance(element) {
        if (!element) return new SimpleModal(element);
        if (!element.__simpleModalInstance) {
            element.__simpleModalInstance = new SimpleModal(element);
        }
        return element.__simpleModalInstance;
    }
}

class SimpleTooltip {
    constructor(element) {
        this.element = element;
    }
}

class SimplePopover {
    constructor(element) {
        this.element = element;
    }
}

window.bootstrap = {
    Modal: SimpleModal,
    Tooltip: SimpleTooltip,
    Popover: SimplePopover,
};

document.addEventListener('click', (event) => {
    const dismiss = event.target.closest('[data-bs-dismiss="modal"]');
    if (!dismiss) return;
    const modalElement = dismiss.closest('.modal');
    if (!modalElement) return;
    SimpleModal.getOrCreateInstance(modalElement).hide();
});

document.addEventListener('DOMContentLoaded', () => {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].map((popoverTriggerEl) => new window.bootstrap.Popover(popoverTriggerEl));

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map((tooltipTriggerEl) => new window.bootstrap.Tooltip(tooltipTriggerEl));

    document.addEventListener('click', (event) => {
        const addToCartButton = event.target.closest('.product-form__cart-add');
        if (addToCartButton) {
            const form = document.getElementById('product_form_addCart');
            if (!form) return;
            const fdnew = new FormData(form);
            const action = form.getAttribute('action') || window.location.href;
            http.postForm(action, fdnew)
                .then((res) => {
                    if (res.data.error === 0) {
                        setTimeout(() => {
                            const cartCount = document.getElementById('CartCount');
                            if (cartCount) {
                                cartCount.innerHTML = res.data.count_cart;
                            }
                            if (res.data.view) {
                                const siteCart = document.querySelector('.site-cart');
                                if (siteCart) {
                                    const headerCart = siteCart.querySelector('#header-cart');
                                    if (headerCart) {
                                        headerCart.remove();
                                    }
                                    siteCart.insertAdjacentHTML('beforeend', res.data.view);
                                }
                            }
                        }, 1000);
                        alertJs('success', res.data.msg);
                    } else {
                        alertJs('error', res.data.msg);
                    }
                })
                .catch((e) => console.log(e));
            return;
        }

        const quickBuyButton = event.target.closest('.quick-buy');
        if (quickBuyButton) {
            quickBuyButton.textContent = 'Loading...';
            const form = document.getElementById('product_form_addCart');
            if (!form) return;
            const fdnew = new FormData(form);
            const href = quickBuyButton.getAttribute('href') || window.location.href;
            http.postForm('buy-now', fdnew)
                .then((res) => {
                    if (res.data.error === 0) {
                        window.location.href = href;
                    } else {
                        alertJs('error', res.data.msg);
                    }
                })
                .catch((e) => console.log(e));
            event.preventDefault();
            return;
        }

        const miniCartRemove = event.target.closest('.mini-products-list .remove');
        if (miniCartRemove) {
            event.preventDefault();
            const widgetItem = miniCartRemove.closest('.widget-cart-item');
            if (widgetItem) {
                widgetItem.remove();
            }
            const rowId = miniCartRemove.getAttribute('data');
            http.postForm(window.AppRoutes?.cartRemove || '/cart/remove-item', { rowId: rowId })
                .then((res) => {
                    if (res.data.error === 0) {
                        setTimeout(() => {
                            const cartCount = document.getElementById('CartCount');
                            if (cartCount) {
                                cartCount.innerHTML = res.data.count_cart;
                            }
                            const headerCart = document.querySelector('#header-cart .money-total');
                            if (headerCart) {
                                headerCart.innerHTML = res.data.total;
                            }
                        }, 1000);
                        alertJs('success', res.data.msg);
                    } else {
                        alertJs('error', res.data.msg);
                    }
                })
                .catch((e) => console.log(e));
            return;
        }

        const addToWishlist = event.target.closest('.add-to-wishlist');
        if (addToWishlist) {
            const id = addToWishlist.getAttribute('data');
            http.postForm('/add-to-wishlist', { id: id })
                .then(() => {
                    const heart = addToWishlist.querySelector('.anm-heart');
                    const heartL = addToWishlist.querySelector('.anm-heart-l');
                    if (heart && heartL) {
                        if (heart.classList.contains('active')) {
                            heartL.classList.add('active');
                            heart.classList.remove('active');
                        } else {
                            heart.classList.add('active');
                            heartL.classList.remove('active');
                        }
                    }
                })
                .catch((e) => console.log(e));
            event.preventDefault();
            return;
        }

        const quickView = event.target.closest('.quick-view');
        if (quickView) {
            const idAttr = quickView.getAttribute('data-id');
            const id = idAttr ? idAttr : null;
            http.postForm('/quick-view', { id: id })
                .then((res) => {
                    if (res.data.error === 0) {
                        const existing = document.getElementById('content_quickview');
                        if (existing && existing.parentElement) {
                            existing.parentElement.removeChild(existing);
                        }
                        document.body.insertAdjacentHTML('beforeend', res.data.view);
                        window.bootstrap.Modal.getOrCreateInstance(document.getElementById('content_quickview')).show();
                    }
                })
                .catch((e) => console.log(e));
            event.preventDefault();
            return;
        }

        const descriptionViewMore = event.target.closest('.description-view-more');
        if (descriptionViewMore) {
            const parentTab = descriptionViewMore.closest('.tab-content');
            const maxHeight = parentTab ? parentTab.querySelector('.max-height-300') : null;
            const titleLess = descriptionViewMore.getAttribute('data-less') || '';
            if (maxHeight) {
                descriptionViewMore.textContent = titleLess;
                maxHeight.classList.remove('max-height-300');
                descriptionViewMore.classList.remove('description-view-more');
                descriptionViewMore.classList.add('description-view-less');
            }
            return;
        }

        const descriptionViewLess = event.target.closest('.description-view-less');
        if (descriptionViewLess) {
            const parentTab = descriptionViewLess.closest('.tab-content');
            const maxHeight = parentTab ? parentTab.querySelector('.max-height-300') : null;
            const titleShow = descriptionViewLess.getAttribute('data-more') || '';
            if (!maxHeight) {
                descriptionViewLess.textContent = titleShow;
                const productDescription = document.querySelector('.product-description');
                if (productDescription) {
                    productDescription.classList.add('max-height-300');
                }
                descriptionViewLess.classList.remove('description-view-less');
                descriptionViewLess.classList.add('description-view-more');
            }
        }
    });

    (function () {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        const closeBtn = document.getElementById('close-menu-btn');

        if (btn && menu && overlay && closeBtn) {
            const openMenu = () => {
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.remove('-translate-x-full');
                }, 10);
            };

            const closeMenu = () => {
                menu.classList.add('-translate-x-full');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
            };

            btn.addEventListener('click', openMenu);
            closeBtn.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);
        }

        const navDropdownRoots = document.querySelectorAll('[data-nav-dropdown]');
        const closeAllNavDropdowns = () => {
            navDropdownRoots.forEach((root) => {
                root.classList.remove('is-open');
                const toggle = root.querySelector('[data-nav-dropdown-toggle]');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        };

        navDropdownRoots.forEach((root) => {
            const toggle = root.querySelector('[data-nav-dropdown-toggle]');
            if (!toggle) {
                return;
            }

            toggle.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                const willOpen = !root.classList.contains('is-open');
                closeAllNavDropdowns();
                if (willOpen) {
                    root.classList.add('is-open');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('[data-nav-dropdown]')) {
                closeAllNavDropdowns();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAllNavDropdowns();
            }
        });

        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('keyword') || urlParams.get('q');

        if (query) {
            const searchInputs = document.querySelectorAll('input[name="keyword"], input[name="q"]');
            searchInputs.forEach((input) => {
                input.value = query;
            });
        }
    })();

    const loginPage = $('#signin-tab');
    loginPage.validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            email: 'required',
            password: 'required',
        },
        messages: {
            email: 'Nhập địa chỉ E-mail',
            password: 'Nhập mật khẩu',
        },
        errorElement: 'div',
        errorLabelContainer: '.errorTxt',
        invalidHandler: function () {
            $('html, body').animate(
                {
                    scrollTop: 0,
                },
                500,
            );
        },
    });

    $('.btn-login').on('click', function () {
        if (loginPage.valid()) {
            const form = document.getElementById('signin-tab');
            const fdnew = new FormData(form);
            loginPage.find('.list-content-loading').show();
            http.postForm(window.AppRoutes?.login || '/auth/login', fdnew)
                .then((res) => {
                    loginPage.find('.error-message').hide();
                    if (res.data.error === 0) {
                        $('#signin-tab').html(res.data.view);
                        $('#signin-modal').on('hidden.bs.modal', function () {
                            window.location.href = '/';
                        });
                    } else {
                        loginPage.find('.list-content-loading').hide();
                        loginPage.find('.error-message').show().html(res.data.msg);
                    }
                })
                .catch((e) => console.log(e));
        }
    });

    if ($('.counter').length) {
        $('.counter').counterUp({
            delay: 10,
            time: 2000,
        });
    }

    animateCounterUp();

    function animateCounterUp() {
        const elements = document.querySelectorAll('.animate-counter-up');
        elements.forEach((element) => {
            const update = () => {
                const target = Number(element.getAttribute('data-countTarget'));
                const step = Number(element.getAttribute('data-countStep'));
                let current = Number(element.innerText);
                const delay = target < 100 ? 100 : 1;
                if (current < target) {
                    element.innerText = current + step;
                    setTimeout(update, delay);
                } else {
                    element.innerText = target.toLocaleString();
                }
            };
            update();
        });
    }
});

function alertJs(type = 'error', msg = '') {
    const Toast = window.Swal.mixin({
        toast: true,
        icon: type,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
    });
    Toast.fire({
        icon: type,
        title: msg,
    });
}

function alertMsg(type = 'error', msg = '', note = '') {
    const swalWithBootstrapButtons = window.Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
        },
        buttonsStyling: true,
    });
    swalWithBootstrapButtons.fire(msg, note, type);
}

window.alertJs = alertJs;
window.alertMsg = alertMsg;

window.addEventListener('load', () => {
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false,
        disable: function () {
            const maxWidth = 800;
            return window.innerWidth < maxWidth;
        },
    });
});

$(function () {
    const mainSlider = new Swiper('.mainSlider', {
        loop: true,
        speed: 1000,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
        },
        navigation: {
            enabled: true,
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            576: {},
            1200: {},
        },
    });
    return mainSlider;
});

let prevScrollpos = window.scrollY;
window.onscroll = function () {
    const currentScrollPos = window.scrollY;
    const navbar = document.getElementById('navbar');
    if (!navbar) {
        prevScrollpos = currentScrollPos;
        return;
    }
    if (prevScrollpos > currentScrollPos) {
        navbar.style.top = '0';
    } else {
        navbar.style.top = '-123px';
    }
    prevScrollpos = currentScrollPos;
};
