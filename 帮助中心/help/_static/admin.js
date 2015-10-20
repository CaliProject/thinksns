var admin = {};

/**
 * 收缩展开某个DOM
 */
admin.fold = function(id){
  	$('#'+id).slideToggle('fast');
};

/**
 * 处理ajax返回数据之后的刷新操作
 */
admin.ajaxReload = function(obj,callback){
    if("undefined" == typeof(callback)){
        callback = "location.href = location.href";
    }else{
        callback = 'eval('+callback+')';
    }
    if(obj.status == 1){
        ui.success(obj.data);
        setTimeout(callback,1500);
     }else{
        ui.error(obj.data);
    }
};


admin.getChecked = function() {
    var ids = new Array();
    $.each($('#list input:checked'), function(i, n){
        if($(n).val() !='0' && $(n).val()!='' ){
            ids.push( $(n).val() );    
        }
    });
    return ids;
};

admin.checkon = function(o){
    if( o.checked == true ){
        $(o).parents('tr').addClass('bg_on');
    }else{
        $(o).parents('tr').removeClass('bg_on');
    }
};

admin.checkAll = function(o){
    if( o.checked == true ){
        $('#list input[name="checkbox"]').attr('checked','true');
        $('tr[overstyle="on"]').addClass("bg_on"); 
    }else{
        $('#list input[name="checkbox"]').removeAttr('checked');
        $('tr[overstyle="on"]').removeClass("bg_on");
    }
};
//绑定tr上的on属性
admin.bindTrOn = function(){
    $("tr[overstyle='on']").hover(
      function () {
        $(this).addClass("bg_hover");
      },
      function () {
        $(this).removeClass("bg_hover");
      }
    );
};


admin.checkAddHelp = function(form){
	if(getLength(form.title.value) < 1){
		ui.error('请输入标题');
		return false;
	}
    return true;	
};


admin.delHelp = function(help_id){
    if("undefined" == typeof(help_id) || help_id=='') help_id = admin.getChecked();
    if(help_id==''){
        ui.error('请选择要删除的帮助');
        return false;
    }  
    if(confirm('确定要删除该帮助吗？')){
        $.post(U('help/Admin/delHelp'),{help_id:help_id},function(msg){
            admin.ajaxReload(msg);
        },'json');
    }
};