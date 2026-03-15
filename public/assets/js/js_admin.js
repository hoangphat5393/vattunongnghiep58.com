$(function () {
    $('.nav-sidebar .has-treeview').each(function (index, el) {
        if ($(this).find('a').hasClass('active')) $(this).addClass('menu-open');
        else if ($(this).find('.nav-item').hasClass('active')) $(this).addClass('menu-open');
    });

    $('#selectall').on('click', function () {
        var checkboxes = $('#table_index').find(':checkbox');
        if ($(this).is(':checked')) {
            //checkboxes.attr('checked', 'checked');
            $(':checkbox').prop('checked', true);
        } else {
            //checkboxes.removeAttr('checked');
            $(':checkbox').prop('checked', false);
        }
    });

    // $(document).on("keyup", "#price, #acreage", function (event) {
    //     if (event.which >= 37 && event.which <= 40) return;

    //     // format number
    //     $(this).val(function (index, value) {
    //         return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //     });
    // });
    var place_select = $('.place_select');
    if (place_select.length > 0) {
        place_select.each(function (index, el) {
            $(this).on('change', function () {
                var type = $(this).data('type'),
                    child = $(this).data('child'),
                    id = $(this).val();
                get_place(type, id, child);
                get_address_full();
            });
        });
    }

    //delete post
    $('.delete-post').on('click', function (event) {
        var url = $(this).data('url');
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
                    action: function () { }, // Nothing to do in this case. You can as well omit the action property.
                },
            },
        });
    });

    // //Date range picker
    // $('#cus_from').datetimepicker({
    //     format: 'YYYY-MM-DD',
    // });

    // $('#cus_to').datetimepicker({
    //     format: 'YYYY-MM-DD',
    // });

    // $('#order_from').datetimepicker({
    //     format: 'YYYY-MM-DD',
    // });

    // $('#order_to').datetimepicker({
    //     format: 'YYYY-MM-DD',
    // });

    $('.select2').select2();
    $('.multi-select2').select2();
});

function get_address_full() {
    var address_full = $('.place_select')
        .map(function () {
            var val = $(this).find('option:selected').val();
            if (val != '') return $(this).find('option:selected').text();
        })
        .get();
    address_full = address_full.reverse();

    $('#address').val(address_full.join(', '));
}

function get_place(type, id, child) {
    axios({
        method: 'post',
        url: admin_url + '/place-select',
        data: { type: type, id: id },
    })
        .then((res) => {
            $('.' + child).html(res.data);
        })
        .catch((e) => console.log(e));
}

function select_all() {
    $(function () {
        var checkboxes = $('#table_index')
            .find(':checkbox')
            .each(function () {
                if ($(this).is(':checked')) {
                    //checkboxes.attr('checked', 'checked');
                    $(':checkbox').prop('checked', true);
                } else {
                    //checkboxes.removeAttr('checked');
                    $(':checkbox').prop('checked', false);
                }
            });
    });
}
function delete_post(url) {
    arr = [];
    var post_list = $('input[name="post_list[]"]:checked')
        .map(function () {
            arr.push($(this).val());
        })
        .get();
    axios({
        method: 'POST',
        url: url,
        data: { post_list: arr },
    })
        .then((res) => {
            $('input[name="post_list[]"]:checked').each(function () {
                $('.item-' + $(this).val()).remove();
            }); //each
            $.alert({
                title: 'Delete done',
                content: '',
            });
        })
        .catch((e) => console.log(e));
}

function delete_id(type) {
    arr = new Array();
    var con = 0;
    $('input[name="seq_list[]"]:checked').each(function () {
        arr = $('input:checkbox').serializeArray();
        arr.push({ name: '_token', value: getMetaContentByName('csrf-token') });
        arr.push({ name: 'type', value: type });
    }); //each

    if (arr.length == 0) {
        alert('Vui lòng chọn mục cần xóa!');
        return false;
    }
    var info_user_admin = '';
    if (type == 'user_admin') {
        info_user_admin = 'Xóa tài khoản nhân viên sẽ xóa tất cả sản phẩm, đơn hàng thuộc tài khoản này! \n';
    }

    //  var admin_url = 'http://local.uknails.com/admin';

    if (confirm(info_user_admin + 'Are you sure delete?')) {
        $(function () {
            $.ajax({
                type: 'POST',
                url: admin_url + '/delete-id',
                data: arr, //pass the array to the ajax call
                cache: false,
                beforeSend: function () { },
                success: function () {
                    location.reload();
                },
            }); //ajax
        });
    } else {
        return false;
    }
}

function replicate_id(type) {
    arr = new Array();
    var con = 0;
    $('input[name="seq_list[]"]:checked').each(function () {
        arr = $('input:checkbox').serializeArray();
        arr.push({ name: '_token', value: getMetaContentByName('csrf-token') });
        arr.push({ name: 'type', value: type });
    }); //each

    console.log(arr);
    if (arr.length == 0) {
        alert('Chọn dữ liệu cần tạo!');
        return false;
    }

    if (confirm('Are you sure replicate?')) {
        $(function () {
            $.ajax({
                type: 'POST',
                url: admin_url + '/replicate-id',
                data: arr, //pass the array to the ajax call
                cache: false,
                beforeSend: function () { },
                success: function () {
                    location.reload();
                },
            }); //ajax
        });
    } else {
        return false;
    }
}

function update_theme_fast(product_id) {
    $(function () {
        var origin_price = $('#origin-price-' + product_id).val();
        var promotion_price = $('#promotion-price-' + product_id).val();
        // var order_short=$('#order_short-'+product_id).val();
        var start_event = $('#start-event-' + product_id).val();
        var end_event = $('#end-event-' + product_id).val();
        // if(order_short !=''){
        //     order_short=parseInt($('#order_short-'+product_id).val());
        // }else{
        //     order_short=0;
        // }
        $.ajax({
            type: 'POST',
            url: admin_url + '/ajax/process_theme_fast',
            data: {
                _token: getMetaContentByName('csrf-token'),
                id: product_id,
                origin_price: origin_price,
                promotion_price: promotion_price,
                // 'order_short':order_short,
                start_event: start_event,
                end_event: end_event,
            },
            dataType: 'text',
            cache: false,
            beforeSend: function () { },
            success: function (status) {
                $('#alert_' + product_id).html(status);
                $('#alert_' + product_id).show();
            },
        }); //ajax
    });
}
function new_item_click(product_id) {
    $(function () {
        if ($('#toggle-new-item-' + product_id + ':checkbox:checked').length > 0) {
            var check = 1;
        } else {
            var check = 0;
        }
        $.ajax({
            type: 'POST',
            url: admin_url + '/ajax/process_new_item',
            data: {
                _token: getMetaContentByName('csrf-token'),
                check: check,
                sid: product_id,
            },
            dataType: 'text',
            cache: false,
            beforeSend: function () { },
            success: function (status) { },
        }); //ajax
    });
}

function flash_sale_click(product_id) {
    $(function () {
        if ($('#toggle-flash-sale-' + product_id + ':checkbox:checked').length > 0) {
            var check = 1;
        } else {
            var check = 0;
        }
        $.ajax({
            type: 'POST',
            url: admin_url + '/ajax/process_flash_sale',
            data: {
                _token: getMetaContentByName('csrf-token'),
                check: check,
                sid: product_id,
            },
            dataType: 'text',
            cache: false,
            beforeSend: function () { },
            success: function (status) { },
        }); //ajax
    });
}

function sale_top_week_click(product_id) {
    $(function () {
        if ($('#toggle-sale-top-week-' + product_id + ':checkbox:checked').length > 0) {
            var check = 1;
        } else {
            var check = 0;
        }
        $.ajax({
            type: 'POST',
            url: admin_url + '/ajax/process_sale_top_week',
            data: {
                _token: getMetaContentByName('csrf-token'),
                check: check,
                sid: product_id,
            },
            dataType: 'text',
            cache: false,
            beforeSend: function () { },
            success: function (status) { },
        }); //ajax
    });
}

function propose_click(product_id) {
    $(function () {
        if ($('#toggle-propose-' + product_id + ':checkbox:checked').length > 0) {
            var check = 1;
        } else {
            var check = 0;
        }
        $.ajax({
            type: 'POST',
            url: admin_url + '/ajax/process_propose',
            data: {
                _token: getMetaContentByName('csrf-token'),
                check: check,
                sid: product_id,
            },
            dataType: 'text',
            cache: false,
            beforeSend: function () { },
            success: function (status) { },
        }); //ajax
    });
}

function store_status_click(product_id) {
    $(function () {
        if ($('#toggle-store-status-' + product_id + ':checkbox:checked').length > 0) {
            var check = 1;
        } else {
            var check = 0;
        }
        $.ajax({
            type: 'POST',
            url: admin_url + '/ajax/process_store_status',
            data: {
                _token: getMetaContentByName('csrf-token'),
                check: check,
                sid: product_id,
            },
            dataType: 'text',
            cache: false,
            beforeSend: function () { },
            success: function (status) { },
        }); //ajax
    });
}

function loadFile(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function loadFileIcon(event) {
    var output = document.getElementById('output_icon');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function loadFileSlishow_pc(event) {
    var output = document.getElementById('output_slishow_pc');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function loadFileSlishow_mobile(event) {
    var output = document.getElementById('output_slishow_mobile');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function getMetaContentByName(name, content) {
    var content = content == null ? 'content' : content;
    return document.querySelector("meta[name='" + name + "']").getAttribute(content);
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
        dec = typeof dec_point === 'undefined' ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

//file manager
$(function () {
    $(document).on('click', '.btn-images', function () {
        var id = $(this).attr('data');
        window.open('/admin/media-manager/fm-button?' + id, 'fm', 'width=1200,height=600');
    });

    $('.remove-icon').on('click', function (event) {
        var img = $(this).data('img');
        $(this).parent().find('img').attr('src', img);
        $(this).parent().find('input[type="hidden"]').val('');
        $(this).hide();
    });
    $('.remove-image').on('click', function (event) {
        var img = $(this).data('img');
        $(this).parent().find('img').attr('src', img);
        $(this).parent().find('input[type="hidden"]').val('');
        $(this).hide();
    });
});
// set file link
function fmSetLink($url, id = 'preview_image') {
    const myArr = $url.split('storage/');
    document.getElementById(id).value = myArr[1];
    $('.' + id).attr('src', $url);
    document.getElementsByClassName(id).src = 'test.jpg';
    // document.querySelector('.'+id).value = myArr[1];
}
//file manager

//ckeditor
function editorQuote(text) {
    CKEDITOR.replace(text, {
        toolbar: [['Source', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['FontSize', 'TextColor', 'BGColor'], ['Image'], ['btgrid'], ['txtEmbed', 'chkAutoplay']],
        filebrowserBrowseUrl: '/ckfinder/browser',
        height: '300',
    });
}

//ckeditor
function editor(text) {
    // CKEDITOR.plugins.addExternal('ckfinder', '/public/assets/plugin/ckeditor/plugins/collapsibleItem/', 'plugin.js');
    CKEDITOR.replace(text, {
        extraPlugins: 'lineheight, language, video, widget, widgetselection, clipboard, lineutils, btgrid, accordionList, collapsibleItem',
        line_height: '0.5;1;1.5;2;2.5;3;3.5;4;4.5;5',
        width: '100%',
        resize_maxWidth: '100%',
        resize_minWidth: '100%',
        height: '500',
        filebrowserBrowseUrl: '/ckfinder/browser',
    });
    CKEDITOR.instances[text];

    // filebrowserImageUploadUrl = '{!! route('uploadPhoto').'?_token='.csrf_token() !!}';
    // CKEDITOR.replace('editor1', {
    //     filebrowserImageUploadUrl: '',
    //     filebrowserBrowseUrl: 'js/ckeditor/filemanager/browser/default/browser.html?Connector=http://www.mixedwaves.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
    //     filebrowserImageBrowseUrl: 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&amp;Connector=http://www.mixedwaves.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
    //     filebrowserFlashBrowseUrl: 'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&amp;Connector=http://www.mixedwaves.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
    // });
}
//ckfinder

$(function () {
    $('.ckfinder-popup').each(function (index, el) {
        var id = $(this).attr('id'),
            input = $(this).attr('data'),
            view_img = $(this).data('show');
        var button1 = document.getElementById(id);
        button1.onclick = function () {
            selectFileWithCKFinder(input, view_img);
        };
    });

    $(document).on('click', '.ckfinder-gallery', function () {
        selectFileWithCKFinder($(this).closest('.gallery_body').find('.gallery_box'), '', '', (multi = true));
    });
});

function selectFileWithCKFinder(elementId, view_img, selected_el = '', multi = false) {
    CKFinder.modal({
        chooseFiles: true,
        width: 1000,
        height: 600,
        onInit: function (finder) {
            finder.on('files:choose', function (evt) {
                if (multi) {
                    var html = '';
                    // console.log(evt.data.files.first());
                    var files = evt.data.files;
                    var chosenFiles = '';
                    files.forEach(function (file, i) {
                        var image = file.getUrl();
                        html += '<div class="gallery_item"><div class="gallery_content"><span class="remove"><i class="fa fa-times-circle" aria-hidden="true"></i></span>';
                        html += '<input type="hidden" name="gallery[]" value="' + image + '"><img src="' + image + '">';
                        html += '</div></div>';
                    });
                    elementId.append(html);
                } else {
                    var file = evt.data.files.first();
                    var output = document.getElementById(elementId);

                    // Update new image to hidden input
                    output.value = file.getUrl();

                    // Update new image
                    if (selected_el) $('#' + selected_el).attr('src', file.getUrl());
                    // console.log(output, '#' + selected_el);

                    if (view_img != '') $('.' + view_img).attr('src', file.getUrl());
                }
            });

            finder.on('file:choose:resizedImage', function (evt) {
                var output = document.getElementById(elementId);
                output.value = evt.data.resizedUrl;

                if (view_img != '') $('.' + view_img).attr('src', evt.data.resizedUrl);
            });
        },
    });
}

$(function () {
    var timeoutID;
    $('.quick_change_value').on('change', function () {
        var element = $(this);
        var type = $(this).attr('type'),
            id = $(this).attr('data-id'),
            model = $(this).attr('data-model'),
            column = $(this).prop('id'),
            value,
            // reload = $(this).attr("reload"),
            reload = $(this)[0].hasAttribute('reload-on-change'),
            arr = {};

        // Check input type
        switch (type) {
            case 'checkbox':
                if ($(this).is(':checked')) value = $(this).val();
                else value = $(this).attr('value-off');
                break;
            default:
                value = $(this).val();
                break;
        }

        arr['_token'] = getMetaContentByName('csrf-token');
        arr['id'] = id;
        arr['model'] = model;
        arr['column'] = column;
        arr['value'] = value;

        axios({
            method: 'post',
            url: admin_url + '/quick-change',
            data: arr,
        })
            .then((res) => {
                if (reload) location.reload();
            })
            .catch((e) => console.log(e));

        // // debugger;
        // if (timeoutID) {
        //     clearTimeout(timeoutID);
        // }
        // timeoutID = setTimeout(() => {
        //     $.ajax({
        //         type: "POST",
        //         url: admin_url + "/quick-change",
        //         data: arr, //pass the array to the ajax call
        //         cache: false,
        //         beforeSend: function () {},
        //         success: function () {
        //             if (reload) location.reload();
        //         },
        //     }); //ajax
        // }, 1000);
    });
    // console.log(timeoutID);
});

function alertMsg(type = 'error', msg = '', note = '') {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
        },
        buttonsStyling: true,
    });
    swalWithBootstrapButtons.fire(msg, note, type);
}

function alertJs(type = 'error', msg = '', position = 'bottom-end') {
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
}
function alertConfirm(type = 'warning', msg = '') {
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
}


const formEdit = document.getElementById('formEdit');
if (formEdit) {
    // Nếu tồn tại, thêm sự kiện lắng nghe
    // var formData = new FormData(formEdit);
    // alert(formEdit.tagName);
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Ngăn chặn hành động mặc định (submit bằng nút Enter)
            // formEdit.submit(); // Submit form
            $('#submit_form_save').trigger('click');
        }
    });
}


$(function () {
    $('.copyButton').on('click', function () {
        event.stopPropagation();
        const text = $(this).text(); // Lấy nội dung của thẻ span
        navigator.clipboard.writeText(text).then(() => {
            alertJs('success', 'Copied to clipboard!');
        }).catch(err => {
            alertJs('error', 'Copied fail!');
        });
    });
});
