$(function(){
$('#getLskyToken').on('click',function(){
        const extensionId = $(this).attr('data-id');
        layer.confirm('是否确认获取Token？注意：本主题不会保存您的账号密码信息，仅用于向站点发送以获取Token', {
  btn: ['确认获取','取消'],
  title:'获取LskyPro Token',
}, function(){
    layer.prompt({title: '填写LskyPro 账号', formType: 3}, function(username, index){
        layer.close(index);
        layer.prompt({title: '填写LskyPro 密码', formType: 1}, function(password, index){
            layer.close(index);
    layer.msg('正在获取Token，请勿刷新', {icon: 1});
    layer.msg('Token获取中...', {
  icon: 16
  ,shade: 0.01,
  time: 9999999
});
    $.ajax({
                        type: "POST",
                        async:true,
                        url: siteUrl+"index.php/UserAction",
                        data: {
                            "type": 'getLskyProToken',
                            "lskypro_username":username,
                            "lskypro_password":password,
                        },
                        dateType: "json",
                        success: function(data) {
                            json = JSON.parse(data);
                             if(json.code == 0){
                             layer.msg(json.msg, {icon: 2});
                             }
                             else if(json.code == 1){
                                 $('input[name="beargallery[lskypro_token]"]').val(json.msg);
                             layer.msg("Token获取完毕，已自动填入。", {icon: 1});  
                             }
                             else{
                             layer.msg('操作出错', {icon: 2});    
                             }
                        },
                        error: function() {
                            layer.msg('操作出错，请稍后再试', {icon: 2});    
                        }
                    });
        });
    });
  
});
    });
});