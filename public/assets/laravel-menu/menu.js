var arraydata = [];
var menu_id = $('#idmenu').val();

function getmenus() {
    arraydata = [];
    var cont = 0;

    $('#spinsavemenu').show();

    $('#menu-to-edit li').each(function (index) {
        let dept = 0;

        for (var i = 0; i < $('#menu-to-edit li').length; i++) {
            var n = $(this)
                .attr('class')
                .indexOf('menu-item-depth-' + i);
            if (n != -1) dept = i;
        }

        var textoiner = $(this).find('.item-edit').text();
        var id = this.id.split('-');
        var textoexplotado = textoiner.split('|');
        var padre = 0;

        if (!!textoexplotado[textoexplotado.length - 2] && textoexplotado[textoexplotado.length - 2] != id[2]) {
            padre = textoexplotado[textoexplotado.length - 2];
        }

        arraydata.push({
            depth: dept,
            id: id[2],
            parent: padre,
            sort: cont,
        });
        cont++;
    });

    updateitem();
    actualizarmenu();
}

// Delete menu
function deletemenu() {
    let id = $('#idmenu').val();

    if (!id) {
        alert('Select menu first!');
        return false;
    }

    var r = confirm('Do you want to delete this menu ?');

    if (r == true) {
        $('#spincustomu2').show();
        axios
            .delete(`admin/menu/${id}`)
            .then((response) => {
                const resp = response.data;
                if (!resp.error) {
                    alert(resp.message);
                    window.location = menuwr;
                } else {
                    alert(resp.message);
                }
            })
            .catch((e) => console.error(e));

        $('#spincustomu2').hide();
    } else {
        return false;
    }
}

// Delete Menu & items
function delete_menu_id() {
    var id = $('#idmenu').val();

    seq_list = { 0: id };

    var arr = {
        seq_list,
        _token: getMetaContentByName('csrf-token'),
        type: 'menuwp',
    };

    if (arr.length == 0) {
        alert('Please choose item you want to delete!');
        return false;
    }
    var info_user_admin = '';

    if (confirm(info_user_admin + 'Are you sure you want to delete this menu?')) {
        $(function () {
            axios
                .post(admin_url + '/delete-id', arr)
                .then((response) => {
                    window.location = admin_url + '/menu';
                })
                .catch((e) => console.error(e));
        });
    } else {
        return false;
    }
}

// Replicate Menu
function replicate_menu_id(type, id) {
    arr = new Array();

    // var con = 0;
    // arr.push({ name: 'seq_list[]', value: id });
    // arr.push({ name: '_token', value: getMetaContentByName('csrf-token') });
    // arr.push({ name: 'type', value: type });

    seq_list = { 0: id };
    var arr = {
        seq_list,
        _token: getMetaContentByName('csrf-token'),
        type: type,
    };

    if (arr.length == 0) {
        alert('Chọn dữ liệu cần tạo!');
        return false;
    }

    if (confirm('Are you sure replicate?')) {
        $(function () {
            axios
                .post(admin_url + '/replicate-id', arr)
                .then((response) => {
                    location.reload();
                })
                .catch((e) => console.error(e));
        });
    } else {
        return false;
    }
}

// Remove Image Item
$('.remove_menu_image').on('click', function () {
    let id = $(this).attr('data-id');

    console.log(id);

    $('input[name="image_menu_' + id + '"]').val('');
    $('.icon-' + id).attr('src', 'assets/images/placeholder.png');
});

// Add Menu Item custom js
$('.add_custom_menu').on('click', function () {
    $('#spincustomu').show();

    var element = $(this).parent().parent();

    var labelmenu = element.find('#custom-menu-item-name').val(),
        slugmenu = element.find('#custom-menu-item-slug').val(),
        linkmenu = element.find('#custom-menu-item-url').val(),
        targetmenu = element.find('#custom-menu-item-target').val(),
        rolemenu = element.find('#custom-menu-item-role').val(),
        idmenu = $('#idmenu').val(),
        relmenu = '';

    var data = {
        labelmenu: labelmenu,
        slugmenu: slugmenu,
        linkmenu: linkmenu,
        targetmenu: targetmenu,
        relmenu: relmenu,
        rolemenu: rolemenu,
        idmenu: idmenu,
    };

    var idmenu = $('#idmenu').val();
    axios
        .post(`admin/menu/${idmenu}/menuitems`, data)
        .then((response) => {
            location.reload();
        })
        .catch((e) => console.error(e));

    $('#spincustomu').hide();
});

// Add Menu Item js
$('.add_menu_item').on('click', function () {
    var element = $(this).parent().parent().find('.customlinkdiv').find('input[type="checkbox"]');

    element.each(function (index, el) {
        if ($(this).is(':checked')) {
            var id = $(this).val();
            var labelmenu = $(this)
                .parent()
                .find('.item-name-' + id)
                .val();
            slug = $(this)
                .parent()
                .find('.item-slug-' + id)
                .val();
            linkmenu = $(this)
                .parent()
                .find('.item-url-' + id)
                .val();
            typemenu = $(this)
                .parent()
                .find('.item-type-' + id)
                .val();
            addMenuItem(id, typemenu, labelmenu, linkmenu, slug);
        }
    });
});

function addMenuItem(id = 0, type = 'page', labelmenu = '', linkmenu = '', slug = '', targetmenu = '', rolemenu = '', relmenu = '') {
    console.log('addMenuItem');

    $('#spincustomu').show();
    var idmenu = $('#idmenu').val();

    var data = {
        labelmenu: labelmenu,
        linkmenu: linkmenu,
        slug: slug,
        targetmenu: targetmenu,
        relmenu: relmenu,
        rolemenu: rolemenu,
        idmenu: idmenu,
    };

    axios
        .post(`admin/menu/${idmenu}/menuitems`, data)
        .then((response) => {
            location.reload();
        })
        .catch((e) => console.error(e));

    $('#spincustomu').hide();
}

// Update menu item
function updateitem(id = 0) {
    var idmenu = $('#idmenu').val();

    if (id) {
        var label = $('#idlabelmenu_' + id).val();
        // var icon = $("#icon-" + id).val();
        var image = $('#image_menu_' + id).val();
        var classes = $('#clases_menu_' + id).val();
        var target = $('#target_menu_' + id).val();
        var rel = $('#rel_menu_' + id).val();
        var slug = $('#slug_menu_' + id).val();
        var url = $('#url_menu_' + id).val();
        var role_id = 0;
        if ($('#role_menu_' + id).length) {
            role_id = $('#role_menu_' + id).val();
        }
        var data = {
            label: label,
            // icon: icon,
            image: image,
            classes: classes,
            slug: slug,
            url: url,
            target: target,
            rel: rel,
            role_id: role_id,
            id: id,
        };
    } else {
        var arr_data = [];
        $('.menu-item-settings').each(function (k, v) {
            var id = $(this).find('.edit-menu-item-id').val();
            var label = $(this).find('.edit-menu-item-title').val();
            // var icon = $(this).find(".icon-data").val();
            var image = $(this).find('.image-data').val();
            var classes = $(this).find('.edit-menu-item-classes').val();
            var slug = $(this).find('.edit-menu-item-slug').val();
            var url = $(this).find('.edit-menu-item-url').val();
            var role = $(this).find('.edit-menu-item-role').val();
            var target = $(this).find('.edit-menu-item-target').val();
            var rel = $(this).find('.edit-menu-item-rel').val(),
                content = $(this).find('.menu-content').val();

            arr_data.push({
                id: id,
                label: label,
                // icon: icon,
                image: image,
                class: classes,
                slug: slug,
                link: url,
                target: target,
                rel: rel,
                role_id: role,
                content: content,
            });
        });

        var data = { arraydata: arr_data };
    }

    if (id) {
        $('#spincustomu2').show();
    }
    axios
        .post(`admin/menu/${idmenu}/updateMenuItem`, data)
        .then((response) => {
            if (id) {
                $('#spincustomu2').hide();
            }
        })
        .catch((e) => console.error(e));
}
// End Cusome js

function actualizarmenu() {
    console.log('actualizarmenu');

    var data = {
        arraydata: arraydata,
        menuname: $('#menu-name').val(),
        idmenu: $('#idmenu').val(),
    };

    $('#spincustomu2').show();

    axios
        .post(`admin/menu/generatemenu`, data)
        .then((response) => {
            $('#spincustomu2').hide();
        })
        .catch((e) => console.error(e));
}

function deleteitem(id) {
    // console.log('deleteitem');

    var idmenu = $('#idmenu').val();

    $('#spincustomu').hide();

    axios
        .delete(`admin/menu/${idmenu}/${id}`)
        .then((response) => {
            // location.reload();
        })
        .catch((e) => console.error(e));
}

// async function deletemenu() {
//     $('#spincustomu2').show();
//     await deletemenu;
//     $('#spincustomu2').hide();
// }

function createnewmenu() {
    // console.log('createnewmenu');

    // axios.defaults.withCredentials = true;
    if (!!$('#menu-name').val()) {
        var data = {
            menuname: $('#menu-name').val(),
        };
        axios
            .post(`admin/menu`, data)
            .then((response) => {
                window.location = menuwr + '?menu=' + response.resp;
            })
            .catch((e) => console.error(e));
    } else {
        alert('Enter menu name!');
        $('#menu-name').trigger('focus');
        return false;
    }
}

function insertParam(key, value) {
    // console.log('insertParam');

    key = encodeURI(key);
    value = encodeURI(value);

    var kvp = document.location.search.substring(1).split('&');
    var i = kvp.length;
    var x;

    while (i--) {
        x = kvp[i].split('=');
        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if (i < 0) {
        kvp[kvp.length] = [key, value].join('=');
    }

    // This will reload the page, it's likely better to store this until finished
    document.location.search = kvp.join('&');
}
