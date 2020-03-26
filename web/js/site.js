var site = {
    that: this,
    showModal: function (url) {
        var obj = $('#remoteModal');
        obj.html('');
        obj.load(url);
        obj.modal('show');
        return false;
    },
    getCheckboxValues: function (name) {
        var st = [];
        $("input[name='" + name + "']:checked").each(function () {
            st.push($(this).val());
        });
        return st;
    },
    toUrl: function (url, param) {
        var params = [];
        if (param != '') {
            params.push(param);
        }
        if (url.indexOf('?') < 0) {
            return url + '?' + params.join();
        }
        return url + '&' + params.join();
    },
    getAllForm: function () {
        var params = $("#" + id).serializeArray();
        var values = {};
        for (var x in params) {
            values[params[x].name] = params[x].value;
        }
        return values;
    },
    postData: function (url, data, back) {
        data._csrf = $('meta[name="csrf-token"]').attr("content");
        data.time = new Date().getTime();
        $.post(url,
            data,
            back,
        );
    }
};

$('body').on('click', '.delete-file', function () {
    console.log($(this));
    var id = $(this).data('id');
    var value = $(this).data('value');
    $('#file_' + id).remove();
    $('#' + value).val(0);
});


