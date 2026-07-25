(function () {
    'use strict';

    if (typeof axios !== 'undefined') {
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
        }
    }

    window.http = window.http || {
        get: function (url, config) {
            return axios.get(url, config);
        },
        post: function (url, data, config) {
            return axios.post(url, data, config);
        },
        postForm: function (url, data, config) {
            return axios.post(url, data, config);
        },
        postJson: function (url, data, config) {
            return axios.post(url, data, Object.assign({}, config, {
                headers: Object.assign({ 'Content-Type': 'application/json', Accept: 'application/json' }, config && config.headers),
            }));
        },
        put: function (url, data, config) {
            return axios.put(url, data, config);
        },
        delete: function (url, config) {
            return axios.delete(url, config);
        },
        postText: function (url, data, config) {
            return axios.post(url, data, Object.assign({}, config, { responseType: 'text' }));
        },
    };

    function getMetaContentByName(name, content) {
        const attr = content == null ? 'content' : content;
        const meta = document.querySelector("meta[name='" + name + "']");
        return meta ? meta.getAttribute(attr) : '';
    }

    function adminPost(url, data, config) {
        return window.http.postForm(url, data, config);
    }

    function getCheckedSeqList() {
        return $('input[name="seq_list[]"]:checked')
            .map(function () {
                return $(this).val();
            })
            .get();
    }

    window.getMetaContentByName = getMetaContentByName;

    $(function () {
        $('.nav-sidebar .has-treeview').each(function () {
            if ($(this).find('a').hasClass('active')) {
                $(this).addClass('menu-open');
            } else if ($(this).find('.nav-item').hasClass('active')) {
                $(this).addClass('menu-open');
            }
        });

        $('#selectall').on('click', function () {
            if ($(this).is(':checked')) {
                $(':checkbox').prop('checked', true);
            } else {
                $(':checkbox').prop('checked', false);
            }
        });

        const place_select = $('.place_select');
        if (place_select.length > 0) {
            place_select.each(function () {
                $(this).on('change', function () {
                    const type = $(this).data('type');
                    const child = $(this).data('child');
                    const id = $(this).val();
                    get_place(type, id, child);
                    get_address_full();
                });
            });
        }

        $('.delete-post').on('click', function () {
            const url = $(this).data('url');
            $.confirm({
                title: 'Delete Confirmation',
                message: 'You are about to delete this option. <br />It cannot be restored at a later time! Continue?',
                buttons: {
                    Yes: {
                        class: 'blue',
                        action: function () {
                            delete_post(url);
                        },
                    },
                    No: {
                        class: 'gray',
                        action: function () { },
                    },
                },
            });
        });

        $('.select2').select2();
        $('.multi-select2').select2();
    });

    function get_address_full() {
        const address_full = $('.place_select')
            .map(function () {
                const val = $(this).find('option:selected').val();
                if (val != '') {
                    return $(this).find('option:selected').text();
                }
            })
            .get()
            .reverse();

        $('#address').val(address_full.join(', '));
    }

    window.get_place = function get_place(type, id, child) {
        adminPost(admin_url + '/place-select', { type: type, id: id })
            .then(function (res) {
                $('.' + child).html(res.data);
            })
            .catch(function (e) {
                console.error(e);
            });
    };

    window.select_all = function select_all() {
        $('#table_index')
            .find(':checkbox')
            .each(function () {
                if ($(this).is(':checked')) {
                    $(':checkbox').prop('checked', true);
                } else {
                    $(':checkbox').prop('checked', false);
                }
            });
    };

    window.delete_post = function delete_post(url) {
        const post_list = $('input[name="post_list[]"]:checked')
            .map(function () {
                return $(this).val();
            })
            .get();

        adminPost(url, { post_list: post_list })
            .then(function () {
                $('input[name="post_list[]"]:checked').each(function () {
                    $('.item-' + $(this).val()).remove();
                });
                $.alert({
                    title: 'Delete done',
                    content: '',
                });
            })
            .catch(function (e) {
                console.error(e);
            });
    };

    window.delete_id = function delete_id(type) {
        const seq_list = getCheckedSeqList();

        if (seq_list.length === 0) {
            alert('Vui lòng chọn mục cần xóa!');
            return false;
        }

        let info_user_admin = '';
        if (type == 'user_admin') {
            info_user_admin = 'Xóa tài khoản nhân viên sẽ xóa tất cả sản phẩm, đơn hàng thuộc tài khoản này! \n';
        }

        if (!confirm(info_user_admin + 'Are you sure delete?')) {
            return false;
        }

        adminPost(window.AdminRoutes?.bulkDelete || admin_url + '/bulk-delete', {
            _token: getMetaContentByName('csrf-token'),
            type: type,
            seq_list: seq_list,
        })
            .then(function () {
                location.reload();
            })
            .catch(function (e) {
                console.error(e);
            });
    };

    window.replicate_id = function replicate_id(type) {
        const seq_list = getCheckedSeqList();

        if (seq_list.length === 0) {
            alert('Chọn dữ liệu cần tạo!');
            return false;
        }

        if (!confirm('Are you sure replicate?')) {
            return false;
        }

        adminPost(window.AdminRoutes?.bulkReplicate || admin_url + '/bulk-replicate', {
            _token: getMetaContentByName('csrf-token'),
            type: type,
            seq_list: seq_list,
        })
            .then(function () {
                location.reload();
            })
            .catch(function (e) {
                console.error(e);
            });
    };

    window.loadFile = function loadFile(event) {
        const output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };

    window.loadFileIcon = function loadFileIcon(event) {
        const output = document.getElementById('output_icon');
        output.src = URL.createObjectURL(event.target.files[0]);
    };

    window.loadFileSlishow_pc = function loadFileSlishow_pc(event) {
        const output = document.getElementById('output_slishow_pc');
        output.src = URL.createObjectURL(event.target.files[0]);
    };

    window.loadFileSlishow_mobile = function loadFileSlishow_mobile(event) {
        const output = document.getElementById('output_slishow_mobile');
        output.src = URL.createObjectURL(event.target.files[0]);
    };

    window.number_format = function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        const n = !isFinite(+number) ? 0 : +number;
        const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        const sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep;
        const dec = typeof dec_point === 'undefined' ? '.' : dec_point;
        let s = '';
        const toFixedFix = function (num, precision) {
            const k = Math.pow(10, precision);
            return '' + Math.round(num * k) / k;
        };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    };

    $(function () {
        $(document).on('click', '.btn-images', function () {
            const id = $(this).attr('data');
            window.open('/admin/media-manager/fm-button?' + id, 'fm', 'width=1200,height=600');
        });

        $('.remove-icon').on('click', function () {
            const img = $(this).data('img');
            $(this).parent().find('img').attr('src', img);
            $(this).parent().find('input[type="hidden"]').val('');
            $(this).hide();
        });

        $('.remove-image').on('click', function () {
            const img = $(this).data('img');
            $(this).parent().find('img').attr('src', img);
            $(this).parent().find('input[type="hidden"]').val('');
            $(this).hide();
        });
    });

    window.fmSetLink = function fmSetLink($url, id) {
        if (typeof id === 'undefined') {
            id = 'preview_image';
        }
        const myArr = $url.split('storage/');
        document.getElementById(id).value = myArr[1];
        $('.' + id).attr('src', $url);
        document.getElementsByClassName(id).src = 'test.jpg';
    };

    function simpleEditorConfig() {
        return {
            toolbar: [
                ['Source', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['FontSize', 'TextColor', 'BGColor'],
                ['Image'],
                ['btgrid'],
                ['txtEmbed', 'chkAutoplay'],
            ],
            filebrowserBrowseUrl: '/ckfinder/browser',
            height: '300',
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
        };
    }

    window.editorQuote = function editorQuote(text) {
        CKEDITOR.replace(text, simpleEditorConfig());
    };

    /** Nội dung (content): cùng toolbar đơn giản như mô tả (editorQuote). */
    window.editor = function editor(text) {
        editorQuote(text);
    };

    window.syncAllCkEditors = function syncAllCkEditors() {
        if (!window.CKEDITOR || !CKEDITOR.instances) {
            return;
        }

        Object.keys(CKEDITOR.instances).forEach(function (instance) {
            CKEDITOR.instances[instance].updateElement();
        });
    };

    window.registerCkeditorValidator = function registerCkeditorValidator(message) {
        if (typeof $.validator === 'undefined' || $.validator.methods.ckeditor_required) {
            return;
        }

        const defaultMessage = message || 'Nhập nội dung';

        $.validator.addMethod('ckeditor_required', function (value, element) {
            const editor = CKEDITOR.instances[element.id];
            if (editor) {
                const plain = editor
                    .getData()
                    .replace(/<[^>]*>/gi, '')
                    .replace(/&nbsp;/gi, ' ')
                    .trim();

                return plain.length > 0;
            }

            return String(value || '').trim().length > 0;
        }, defaultMessage);
    };

    $(function () {
        registerCkeditorValidator();
    });

    $(function () {
        $('.ckfinder-popup').each(function () {
            const id = $(this).attr('id');
            const input = $(this).attr('data');
            const view_img = $(this).data('show');
            const button1 = document.getElementById(id);
            button1.onclick = function () {
                selectFileWithCKFinder(input, view_img);
            };
        });

        $(document).on('click', '.ckfinder-gallery', function () {
            selectFileWithCKFinder($(this).closest('.gallery_body').find('.gallery_box'), '', '', true);
        });
    });

    window.selectFileWithCKFinder = function selectFileWithCKFinder(elementId, view_img, selected_el, multi) {
        if (typeof selected_el === 'undefined') {
            selected_el = '';
        }
        if (typeof multi === 'undefined') {
            multi = false;
        }

        CKFinder.modal({
            chooseFiles: true,
            width: 1000,
            height: 600,
            onInit: function (finder) {
                var initialSelected = false;

                finder.on('folder:getFiles:after', function (evt) {
                    var folder = evt.data.folder;
                    if (!initialSelected && folder && folder.get('name') === 'Images') {
                        initialSelected = true;
                        finder.request('folder:select', { folder: folder });
                        if (folder.get('hasChildren')) {
                            finder.request('folder:expand', { folder: folder });
                        }
                    }
                });

                finder.on('files:choose', function (evt) {
                    if (multi) {
                        let html = '';
                        const files = evt.data.files;
                        files.forEach(function (file) {
                            const image = file.getUrl();
                            html += '<div class="gallery_item"><div class="gallery_content"><span class="remove"><i class="fa fa-times-circle" aria-hidden="true"></i></span>';
                            html += '<input type="hidden" name="gallery[]" value="' + image + '"><img src="' + image + '">';
                            html += '</div></div>';
                        });
                        elementId.append(html);
                    } else {
                        const file = evt.data.files.first();
                        const output = document.getElementById(elementId);
                        output.value = file.getUrl();

                        if (selected_el) {
                            $('#' + selected_el).attr('src', file.getUrl());
                        }

                        if (view_img != '') {
                            $('.' + view_img).attr('src', file.getUrl());
                        }
                    }
                });

                finder.on('file:choose:resizedImage', function (evt) {
                    const output = document.getElementById(elementId);
                    output.value = evt.data.resizedUrl;

                    if (view_img != '') {
                        $('.' + view_img).attr('src', evt.data.resizedUrl);
                    }
                });
            },
        });
    };

    $(function () {
        $('.quick_change_value').on('change', function () {
            const type = $(this).attr('type');
            const id = $(this).attr('data-id');
            const model = $(this).attr('data-model');
            const column = $(this).prop('id');
            const reload = $(this)[0].hasAttribute('reload-on-change');
            let value;

            switch (type) {
                case 'checkbox':
                    value = $(this).is(':checked') ? $(this).val() : $(this).attr('value-off');
                    break;
                default:
                    value = $(this).val();
                    break;
            }

            adminPost(window.AdminRoutes?.quickChange || admin_url + '/quick-change', {
                _token: getMetaContentByName('csrf-token'),
                id: id,
                model: model,
                column: column,
                value: value,
            })
                .then(function () {
                    if (reload) {
                        location.reload();
                    }
                })
                .catch(function (e) {
                    console.error(e);
                });
        });
    });

    window.alertMsg = function alertMsg(type, msg, note) {
        if (typeof type === 'undefined') {
            type = 'error';
        }
        if (typeof msg === 'undefined') {
            msg = '';
        }
        if (typeof note === 'undefined') {
            note = '';
        }

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
            },
            buttonsStyling: true,
        });
        swalWithBootstrapButtons.fire(msg, note, type);
    };

    window.alertJs = function alertJs(type, msg, position) {
        if (typeof type === 'undefined') {
            type = 'error';
        }
        if (typeof msg === 'undefined') {
            msg = '';
        }
        if (typeof position === 'undefined') {
            position = 'bottom-end';
        }

        const Toast = Swal.mixin({
            icon: type,
            toast: true,
            position: position,
            showConfirmButton: false,
            timer: 3000,
        });
        Toast.fire({
            type: type,
            title: msg,
        });
    };

    window.alertConfirm = function alertConfirm(type, msg) {
        if (typeof type === 'undefined') {
            type = 'warning';
        }
        if (typeof msg === 'undefined') {
            msg = '';
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
        Toast.fire({
            type: type,
            title: msg,
        });
    };

    const formEdit = document.getElementById('formEdit');
    if (formEdit) {
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                $('#submit_form_save').trigger('click');
            }
        });
    }

    $(function () {
        $('.copyButton').on('click', function (event) {
            event.stopPropagation();
            const text = $(this).text();
            navigator.clipboard
                .writeText(text)
                .then(function () {
                    alertJs('success', 'Copied to clipboard!');
                })
                .catch(function () {
                    alertJs('error', 'Copied fail!');
                });
        });
    });
})();
