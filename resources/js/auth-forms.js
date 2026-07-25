import http from './http';

function getJquery() {
    return window.jQuery || window.$;
}

function showFormError(form, message) {
    const swalInstance = window.Swal || (typeof Swal !== 'undefined' ? Swal : null);
    if (swalInstance && typeof swalInstance.fire === 'function') {
        swalInstance.fire({
            position: 'center',
            icon: 'error',
            title: message,
            timer: 2500,
        });
        return;
    }

    const errorEl = form.querySelector('.error-message');
    if (!errorEl) {
        alert(message);
        return;
    }

    errorEl.textContent = message;
    errorEl.classList.remove('hidden');
}

function hideFormError(form) {
    const errorEl = form.querySelector('.error-message');
    if (!errorEl) {
        return;
    }

    errorEl.textContent = '';
    errorEl.classList.add('hidden');
}

function toggleLoading(form, show) {
    const loadingEl = form.querySelector('.list-content-loading');
    if (!loadingEl) {
        return;
    }

    loadingEl.classList.toggle('hidden', !show);
    loadingEl.style.display = show ? '' : 'none';
}

function bindValidatedFormSubmit({ formSelector, buttonSelector, onSuccess }) {
    const $ = getJquery();
    const form = document.querySelector(formSelector);

    if (!form || !$ || !$.fn || typeof $.fn.validate !== 'function') {
        return;
    }

    const $form = $(form);
    const button = document.querySelector(buttonSelector);

    if (!button) {
        return;
    }

    button.addEventListener('click', function () {
        if (!$form.valid()) {
            return;
        }

        hideFormError(form);
        toggleLoading(form, true);

        http
            .postForm(form.getAttribute('action') || window.location.href, new FormData(form))
            .then((res) => {
                toggleLoading(form, false);
                onSuccess(res.data, form);
            })
            .catch((err) => {
                toggleLoading(form, false);
                const message =
                    err?.response?.data?.msg ||
                    err?.response?.data?.message ||
                    'Đã xảy ra lỗi, vui lòng thử lại.';
                showFormError(form, message);
            });
    });
}

export function initCustomerLoginForm(formSelector = '#form-login-page', buttonSelector = '.btn-login-page') {
    const $ = getJquery();
    const form = document.querySelector(formSelector);

    if (!form || !$ || !$.fn || typeof $.fn.validate !== 'function') {
        return;
    }

    $(formSelector).validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: 'required',
        },
        messages: {
            email: {
                required: 'Nhập địa chỉ email',
                email: 'Email không đúng định dạng',
            },
            password: 'Nhập mật khẩu',
        },
        errorElement: 'div',
        errorClass: 'text-red-600 text-xs mt-1',
    });

    bindValidatedFormSubmit({
        formSelector,
        buttonSelector,
        onSuccess(data, formEl) {
            if (data.error === 0) {
                window.location.href = data.redirect_back || '/';
                return;
            }

            showFormError(formEl, data.msg || 'Không thể đăng nhập. Vui lòng thử lại.');
        },
    });
}

export function initCustomerRegisterForm(formSelector = '#page-customer-register', buttonSelector = '.btn-register') {
    const $ = getJquery();
    const form = document.querySelector(formSelector);

    if (!form || !$ || !$.fn || typeof $.fn.validate !== 'function') {
        return;
    }

    $(formSelector).validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            name: 'required',
            phone: 'required',
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 6,
            },
            password_confirm: {
                required: true,
                equalTo: '#CustomerPassword',
            },
        },
        messages: {
            name: 'Nhập họ tên',
            phone: 'Nhập số điện thoại',
            email: {
                required: 'Nhập email',
                email: 'Email không đúng định dạng',
            },
            password: {
                required: 'Nhập mật khẩu',
                minlength: 'Mật khẩu tối thiểu 6 ký tự',
            },
            password_confirm: {
                required: 'Nhập lại mật khẩu',
                equalTo: 'Mật khẩu không khớp',
            },
        },
        errorElement: 'div',
        errorClass: 'text-red-600 text-xs mt-1',
    });

    bindValidatedFormSubmit({
        formSelector,
        buttonSelector,
        onSuccess(data, formEl) {
            if (data.error === 0) {
                const successUrl =
                    data.redirect_back ||
                    window.AppRoutes?.registerSuccess ||
                    '/auth/register-success';
                window.location.href = successUrl;
                return;
            }

            showFormError(formEl, data.msg || 'Đăng ký thất bại.');
        },
    });
}

window.initCustomerLoginForm = initCustomerLoginForm;
window.initCustomerRegisterForm = initCustomerRegisterForm;
