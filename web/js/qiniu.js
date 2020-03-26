var QiNiu = function (id) {
    this.config = {
        useCdnDomain: true,
        disableStatisticsReport: true,
        retryCount: 6
    };
    this.putExtra = {
        fname: "",
        params: {},
        mimeType: null
    };
    this.fileExt = function (str) {
        str = str.split(".");
        return str[str.length - 1].toLowerCase();
    };
    this.fileName = function (str) {
        var strar = str.split('.');
        strar.splice(strar.length - 1, 1);
        return strar.join('_');
    };
    this.generateKey = function (name) {
        var newtime = this.formatDate('yyyyMMdd_hhmmss');
        var rand = parseInt(Math.random() * 10000);
        var ext = that.fileExt(name);
        return newtime + '_' + rand + '.' + ext;
    };
    this.formatDate = function (format) {
        var dt = new Date();
        var o = {
            "M+": dt.getMonth() + 1,
            "d+": dt.getDate(),
            "h+": dt.getHours(),
            "m+": dt.getMinutes(),
            "s+": dt.getSeconds(),
            "q+": Math.floor((dt.getMonth() + 3) / 3),
            "S": dt.getMilliseconds()
        }
        if (/(y+)/.test(format)) {
            format = format.replace(RegExp.$1, (dt.getFullYear() + "").substr(4 - RegExp.$1.length));
        }
        for (var k in o) {
            if (new RegExp("(" + k + ")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
            }
        }
        return format;
    };
    this.ajaxToken = function (url, file, obj) {
        $.ajax({
            url: url, success: function (res) {
                that.putExtra.params = $.extend(that.putExtra.params, {
                    "x:ext": that.fileExt(file.name),
                    "x:size": file.size,
                    "x:name": that.fileName(file.name)
                });
                var file_id = 'file_id_' + Date.parse(new Date());
                var observable = qiniu.upload(file, that.generateKey(file.name), res.uptoken, that.putExtra, that.config);
                var subscription = observable.subscribe({
                    next: function (res) {
                        if (obj.data('list') != undefined) {
                            var file_info = $('.' + file_id);
                            if (file_info.length == 0) {
                                var tmp = '<div class="files_item ' + file_id + '">\n' +
                                    '            <div class="name">' + file.name + ' <a href="javascript:void(0)" class="cancel" >取消</a></div>\n' +
                                    '            <div class="upload_process">\n' +
                                    '                  <div class="process" style="width: 0%;"></div>\n' +
                                    '            </div>\n' +
                                    '     </div>';

                                $('#' + obj.data('list')).html(tmp).css('display', 'block');
                                $('.' + file_id).find('.cancel').click(function () {
                                    subscription.unsubscribe();
                                    $(this).closest('.files_item').remove();
                                });
                            }
                            $('.' + file_id).find('.process').css('width', res.total.percent + '%');
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    },
                    complete: function (data) {
                        obj.url = url;
                        obj.domain = 'http://' + res.domain + '/';
                        obj.file_id = file_id;
                        that.complete(data, obj);
                    }
                });
            }
        })
    }
    ;
    this.showimg = '';
    this.finish = '';
    this.complete = function (data, obj) {
        data.domain = obj.domain;
        data._csrf = $('meta[name="csrf-token"]').attr("content");
        data.time = new Date().getTime();
        //提交请求，得到文件id
        $.ajax({
            type: 'POST',
            url: obj.url,
            data: data,
            success: function (response) {
                var res = response.data;
                var id = obj.data('id');
                if (typeof that.showimg == 'function') {
                    that.showimg(res);
                } else {
                    console.log(obj.data('show'));
                    if (obj.data('show') != undefined) {
                        $('#' + obj.data('show')).attr('src', obj.domain + res.key).css('display', 'block');
                    } else {
                        $('.' + obj.file_id).find('.process').css('width', '0%');
                        $('.' + obj.file_id).find('.cancel').html('删除');
                    }
                    $('#' + id).val(res.id);
                }
                $('#' + that.id).val('');
                if (typeof that.finish == 'function') {
                    that.finish(res);
                }
            }
        });
    };
    this.init = function () {
        $('#' + id).unbind("change").bind('change', function () {
            var obj = $(this);
            for (var i = 0; i < this.files.length; i++) {
                that.ajaxToken(obj.data('url'), this.files[i], obj);
            }
        })
    };
    var that = this;
};


