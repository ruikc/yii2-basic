function plupload_init(id, type) {
    var filters = {
        mime_types: [ //只允许上传图片和zip文件
            {title: "图片文件", extensions: "jpg,gif,png,jpeg"},
        ],
        max_file_size: '400kb', //最大只能上传400kb的文件
        prevent_duplicates: true //不允许选取重复文件
    };
    if (type == 'file') {
        filters = {
            mime_types: [ //只允许上传图片和zip文件
                {title: "文档文件", extensions: "pdf,docx"},
            ],
            max_file_size: '2mb', //最大只能上传400kb的文件
            prevent_duplicates: true //不允许选取重复文件
        };
    }
    console.log(filters);
    var obj = $('#' + id);
    var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
        browse_button: id,
        url: obj.data('url'),
        flash_swf_url: './Moxie.swf',
        silverlight_xap_url: './Moxie.xap',
        unique_names: true,
        max_file_size: '2mb',
        filters: filters,
    });
    uploader.init(); //初始化
    //绑定文件添加进队列事件
    uploader.bind('FilesAdded', function (up, files) {
        console.log(up);
        console.log(files);
        up.start();
    });
    //绑定文件上传进度事件
    uploader.bind('UploadProgress', function (up, file) {
    });
    uploader.bind('FileUploaded', function (up, file, info) {
        var res = $.parseJSON(info.response);
        console.log(res);
        console.log(obj);
        if (res.error == 0) {
            if (obj.data('show')) {
                $('#' + obj.data('show')).attr('src', res.data.url);
            }
            if (obj.data('file')) {
                var show = '<div class="file_info" id="file_' + res.data.file_id + '">' + res.data.name + ' <a class="delete-file" data-id="' + res.data.file_id + '" data-value="' + obj.data('value') + '" href="javascript:void(0);">删除</a></div>';
                $('#' + obj.data('file')).html(show);
            }
            $('#' + obj.data('value')).val(res.data.file_id);
        } else {
            alert(res.msg);
        }
    });
    return uploader;
}

