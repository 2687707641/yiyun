$(function () {
    //获取表单中url,type,data
    //post
    $('.ajax_post').each(function (index,form) {
        try{
            $(form).submit(function (from) {
                var data = $(form).serialize(), url = $(form).attr('action');
                _ajax_send(url, data, 'post');
                return false;
            })
        }catch (e) {
            alert(e);
            console.info('表单加载失败');
        }
    })

    //get
    $('.ajax_get').click(function (e) {
        e.preventDefault(); //禁止跳转页面
        var url = $(this).attr('url') || $(this).attr('href'); //拿到url
        if ($(this).hasClass('confirm')) {
            _confirmInit('get', this, '', url)
        } else {
            _ajax_send(url, '', 'get');
        }
    })

    //批量删除
    $('.ajax_all_del').click(function (e) {
        e.preventDefault(); //禁止跳转页面
        var ids;
        var url = $(this).attr('url') || $(this).attr('href'); //拿到url
        //获取被选取的id
        $(":checkbox:checked").each(function () {
            if (isEmpty(ids)) {
                ids = $(this).val();
            } else {
                ids += ',' + $(this).val();
            }
        })
        if (isEmpty(ids)) {
            layer.msg('请您选择数据!', {icon: 2, shade: [0.3, '#666666']});
            return;
        }
        if (url.indexOf("?") > -1) {
            url += "&id=" + ids;
        } else {
            url += "?id=" + ids;
        }
        if ($(this).hasClass('confirm')) {
            console.log('tishi');
            console.log(ids);
            _confirmInit('get', this, '', url);
        } else {
            _ajax_send(url, '', 'get');
        }
    })



})


/***
 * 提交
 */
var _ajax_send = function(url,data,method){
    //加载时动画
    var loading = layer.load(1,{shade: [0.3, '#666666']});

    $.ajax({
        url: url,
        type: method, //GET  or post
        async: true, //或false,是否异步
        data: data,
        timeout: 5000, //超时时间
        dataType: 'json', //返回的数据格式：json/xml/html/script/jsonp/text
        beforeSend: function (xhr) {
            console.log(xhr)
            console.log('发送前')
        },
        success: function (data, textStatus, jqXHR) {
            updateAlert(data);
        },
        error: function (xhr, textStatus) {
            layer.close(loading);
            layer.msg(xhr.responseText, {icon: 2, shade: [0.3, '#666666'], time: 5000});
        },
        complete: function () {
            layer.close(loading);
        }
    })
}

/***
 * 页面提示框
 */
window.updateAlert = function (data,c) {
    var index =parent.layer.getFrameIndex(window.name);

    if(data.code == 1){
        layer.msg(data.msg,{icon:1,shade: [0.3, '#666666'], time: 500},function () {
            //控制是否是顶级刷新，默认是
            if (typeof (data.isTop) && data.isTop == undefined) {
                data.isTop = 1;
            }
            if (index && data.isTop == 1) {
                if (data.url) {
                    window.parent.location.href = data.url;
                } else {
                    window.parent.location.reload();
                }
                return;
            }
            if (data.url) {
                window.location.href = data.url;
            } else {
                window.location.reload();
            }
        });
    } else{
        layer.msg(data.msg,{shade: [0.3, '#666666'], time: 1500}, function () {
            if (data.url) {
                window.location.href = data.url;
                if (index) {
                    window.parent.location.href = data.url;
                    return false;
                }
            }
        });
    }
}

/***
 *onfrim提示初始化
 */
function _confirmInit(method,that,query,url) {
    var btn_data = $(that).attr("btn_data"),
        title = $(that).attr("title"),
        msg = $(that).attr("msg");
    // console.log(msg);
    // if (isEmpty(btn_data)) {
    //     btn_data = ['确认', '取消'];
    // } else {
    //     btn_data = eval("(" + btn_data + ")");
    // }
    // if (isempty(msg)) {
    //     msg = '您确要执行此操作吗？';
    // }
    layer.confirm(msg, {
        btn: btn_data, //按钮
        btn1: function (index) {
            _ajax_send(url, query, method);
        },
        btn2: function (index) {

        },
        title: title
    });
}


window.isEmpty = function (str) {
    if (str == '' || str === undefined || str == null) {
        return true;
    }
    return false;
}