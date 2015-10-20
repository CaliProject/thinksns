core.message = new function(){

    var self    = this;
    self.params = {
        /*任务栏配置*/
        taskbar : {
            clickLi : function(li, e){},
            clickLiClearMsgnum : true,
            removeLi : function(li, e){},
            lis : {
                "pl" : {
                    id : 'pl',
                    title : "评论",
                    src : THEME_URL+'/image/message/pl.png'
                },
                "zan": {
                    id : 'zan',
                    title : "赞",
                    src : THEME_URL+'/image/message/zan.png'
                },
                "tz" : {
                    id : 'tz',
                    title : "通知",
                    src : THEME_URL+'/image/message/tz.png'
                },
                "lxr": {
                    id : 'lxr',
                    title : "联系人",
                    src : THEME_URL+'/image/message/lxr.png'
                }
            }
        }//任务栏配置结束
    };

        /* 任务栏 开始 */
    var taskbar = {
        
        el : '<div id="message-taskbar">\
               <div class="wrap">\
                 <ul id="message-fixed" class="message-list"></ul>\
                 <ul id="message-users" class="message-list"></ul>\
               </div>\
             </div>',

        li : '<li class="tooltip tip-left"><a href="javascript:;"><img /><i></i></a></li>',

        isBindEvents : false,

        /**
         * 在页面上构建任务栏
         * @return void
         */
        build : function(){
            taskbar.remove();

            $('body').append(taskbar.el);
            taskbar.initEvents();

            try{
                var lis = self.params.taskbar.lis;
                $.each(lis, function(i, params){
                    taskbar.addLi(params);
                });
            }catch(e){}
        },

        /**
         * 从页面上移除任务栏
         * @return void
         */
        remove : function(){
            if(taskbar.exist()){
                taskbar.jq().remove();
            }
        },

        /**
         * 检查任务栏是否已经在页面上量
         * @return boolean 存在返回true，否则为false
         */
        exist : function(){
            return taskbar.jq().length>0;
        },

        /**
         * 设置消息数量，展现在前端提示用户
         * @param string id 列表项的ID
         * @param integer number 设置的消息数量
         * @return void
         */
        setMessageNumber : function(id, number){
            if(!taskbar.exist()) return;
            var i = taskbar.jq('#message-'+id+' i');
            i.data('num', number).text(number>9?'···':number);
            if(number <= 0){
                i.addClass('hide');
            }else{
                i.removeClass('hide');
            }
        },

        /**
         * 添加一个li标签
         * @param array|object params li的参数
         * @param string|function insert 插入的方式
         * @param string toId 插入参照的ID
         * @return void
         */
        addLi : function(params, insert, toId){
            //任务栏不存在则自动建立
            if(!taskbar.exist()) {
                taskbar.build();
            }

            var li  = $(taskbar.li);  //li标签
            var img = li.find('img'); //img标签
            var i   = li.find('i');   //i标签
            var number = params.num || 0; //消息数
            var type,isReplace,method;
            // li参数设置
            li.attr('id', 'message-'+params.id);
            li.data('id', params.id);
            li.data('realId', 'message-'+params.id);
            li.attr('title', params.title || '');
            li.attr('data-tooltip',params.title || '');
            img.attr('src', params.src);
            i.data('num', number).text(
                number>9?'···':number
            );
            if(number <= 0){
                i.addClass('hide');
            }else{
                i.removeClass('hide');
            }
            if(params.roomid){
                type = 'users';
                li.data('roomid', params.roomid);
            }else{
                type = 'fixed';
            }
            type = params.type || type;
            li.data('type', type);
            // li参数设置结束

            //是否需要替换
            isReplace = taskbar.hasId(params.id);
            //支持的插入方式
            method = ['append','prepend','after','before'];
            // 如果传入一个function，则直接执行
            if(typeof insert=='function'){
                insert(li, type, isReplace);
                return;
            }else if($.inArray(insert, method)<0){
                insert = 'append';
            }

            toId = toId ? toId : type;
            if(isReplace){
                li.addClass('noanimat');
                li.replaceAll(taskbar.getId(params.id));
            }else{
                eval('taskbar.getId(toId).'+insert+'(li);');
            }
        },
        //移除某个ID
        removeId : function(id){
            taskbar.getId(id).remove();
        },
        //清空某个类型里面的全部
        clear : function(type){
            taskbar.getId(type).html('');
        },
        //取得指定表达式的jquery对象
        jq : function(expr){
            if(expr){
                return $('#message-taskbar').find(expr);
            }else{
                return $('#message-taskbar');
            }
        },
        //取得指定ID的jquery对象
        getId : function(id){
            return taskbar.jq('#message-'+id);
        },
        //检查是否存在某个ID
        hasId : function(id){
            return taskbar.getId(id).length>0;
        },
        //初始化事件
        initEvents : function(){
            if(taskbar.isBindEvents) return;
            taskbar.isBindEvents = true;

            var noActiveMove = null;
            var isMousedown = false;
            var mousedownLi = null;
            var mousedownX  = 0;
            var mousedownY  = 0;
            var lis = taskbar.jq('.message-list li');

            lis.live('mousedown', function(e) { //开始移动
                var li = $(this);
                if(li.data('type')=='fixed'||li.hasClass('move')){
                    return false;
                }
                //按住2000毫秒后激活移动
                noActiveMove = setTimeout(function(){
                    noActiveMove = null;
                    mousedownLi = li;
                    isMousedown = true;
                    mousedownX  = e.pageX;
                    mousedownY  = e.pageY;
                }, 500);
                return false;
            }).live('click', function(e){ //单击事件
                //还未激活移动则取消
                if(noActiveMove){
                    clearTimeout(noActiveMove);
                }
                var li = $(this);
                if(li.hasClass('move')){
                    return false; //正在移动中的
                }
                try{
                    if(self.params.taskbar.clickLiClearMsgnum){
                        taskbar.setMessageNumber(li.data('id'), 0);
                    }
                    if(typeof self.params.taskbar.clickLi=='function'){
                        self.params.taskbar.clickLi(li, e);
                    }
                }catch(e){}
                return false;
            }).live('mouseup', function(){
                //还未激活移动则取消
                if(noActiveMove){
                    clearTimeout(noActiveMove);
                }
            });
            //移动出去
            $(document).mousemove(function(e) {
                if(!isMousedown) return;
                mousedownLi.css({
                    right:mousedownX-e.pageX,
                    bottom:mousedownY-e.pageY,
                }).addClass('move');
            }).mouseup(function(e){ //停止移动
                if(noActiveMove) clearTimeout(noActiveMove);
                if(!isMousedown) return;
                isMousedown = false;
                //删除
                if(parseInt(mousedownLi.css('right')) > 50){
                    mousedownLi.addClass('scale');
                    setTimeout(function(){
                        try{
                            if(typeof self.params.taskbar.removeLi=='function'){
                                self.params.taskbar.removeLi(mousedownLi, e);
                            }
                        }catch(e){}
                        mousedownLi.remove();
                        mousedownLi = null;
                    }, 800);
                }else{ // 回去
                    mousedownLi.animate({
                        right:0,bottom:0
                    }, 300, function(){
                        mousedownLi.removeAttr('style')
                          .removeClass('move');
                        mousedownLi = null;
                    });
                }
            });
        }
        
    }; /* 任务栏 结束 */

    var msgbox  = {
        el : '<div id="msgbox-shield"></div>\
              <div id="msgbox-main">\
                <div class="msgbox-title-wrap">\
                 <div class="msgbox-title">\
                  <h3></h3>\
                  <div class="rt">\
                    <div class="btn"></div>\
                    <div class="close"><a href="javascript:;">×</a></div>\
                  </div>\
                 </div>\
                </div>\
                <div class="msgbox-body"></div>\
                <div class="msgbox-footer-wrap">\
                 <div class="msgbox-footer">\
                 </div>\
                </div>\
              </div>',
        open : function(data){
            if(!msgbox.exist()){
                $('body').append(msgbox.el);
                $('#msgbox-shield').data('st', $(window).scrollTop());
                $('#msgbox-shield,#msgbox-main .close a').click(function(){
                    msgbox.close();
                });
            }else{
                msgbox.onclose();
            }
            msgbox.setData(data || {});
        },
        close : function(){
            msgbox.onclose();
            $('#msgbox-shield').remove();
            $('#msgbox-main').attr('id','msgbox-remove');
            setTimeout(function(){
                $('#msgbox-remove').remove();
            }, 1000);
        },
        exist: function(){
            return $('#msgbox-main').length>0;
        },
        setData : function(data){
            if(!msgbox.exist()) return;
            var title = $('#msgbox-main .msgbox-title');
            /*标题 系统消息[<span>ThinkSNS是智士软件旗下开源社交软件</span>] */
            data.title = data.title || '消息盒子';
            title.find('h3').children().remove();
            title.find('h3').empty().append(data.title);
            /*导航链接 <a href="">全部</a><a href="">微吧</a><a href="">分享</a> */
            data.navs = data.navs || '';
            if(data.navs){
                title.find('h3').append('<span class="navs"></span>');
                title.find('h3 .navs:last').append(data.navs);
            }
            /*按钮 <a href="javascript:;">按钮名称</a> */
            data.btn   = data.btn || '';
            title.find('.btn').children().remove();
            if(data.btn){
                title.find('.btn').empty().append(data.btn);
            }
            /*内容*/
            var msgbody = $('#msgbox-main .msgbox-body');
            if(data.loading){
                msgbody.addClass('msgbox-loading');
            }else{
                msgbody.removeClass('msgbox-loading');
            }
            msgbody.children().remove(); msgbody.empty();
            msgbody.append(data.content);
            /*底部*/
            var footer = $('#msgbox-main .msgbox-footer');
            footer.children().remove(); footer.empty();
            if(data.footer){
                footer.parent().show();
                footer.append(data.footer);
            }else{
                footer.parent().hide();
            }
            msgbox.scrollY(data.scrollY||0, data.scrollYTime);
        },
        scrollY : function(y, time){
            if(y == 'same') return;
            var msgbody = $('#msgbox-main .msgbox-body');
            if(y == 'top'){
                y = 0;
            }else if(y == 'bottom'){
                y = 0;
                msgbody.children().each(function(i, c){
                    y += $(c).outerHeight(true);
                });
                y = y-msgbody.height()+100;
            }
            time = time===undefined?200:time;
            if(time <= 0){
                msgbody.stop().scrollTop(y);
            }else{
                msgbody.stop().animate({
                    scrollTop : parseInt(y)
                }, time);
            }
        },
        openUrl : function(url, loading){
            if(loading !== false){
                msgbox.open({
                    title:'loading...',
                    loading : true,
                });
            }
            $.get(url, function(html){
                if(!msgbox.exist()) return;
                var content = $(html).filter('#set-data');
                var data = {
                    content:html,
                    loading:false,
                    scrollY:0
                };
                if(content.length > 0){
                    data.title = content.data('title');
                    data.navs  = content.data('navs');
                    data.btn   = content.data('btn');
                    data.footer = content.data('footer');
                    if(content.data('scrolly')){
                        data.scrollY = content.data('scrolly');
                    }
                    var time = parseInt(content.data('scrollytime'));
                    if(!isNaN(time)){
                        data.scrollYTime = time;
                    }
                }
                msgbox.open(data);
            });
        },
        openRoom: function(query){
            var url = U('public/WebMessage/room')+'&'+query;
            msgbox.openUrl(url, !msgbox.exist());
        },
        oncloseCallback: null,
        onclose: function(callback){
            if(callback){
                msgbox.oncloseCallback = callback;
            }else if(msgbox.oncloseCallback){
                msgbox.oncloseCallback();
                msgbox.oncloseCallback = null;
            }
        }
    };

    


    
    self._init  = function(args){
        self.init(args);
    };

    self.init   = function(args){
        if(MID <= 0) return;
        $(function(){
            taskbar.build();
            self.params.taskbar.clickLi = function(li){
                if(li.data('type') == 'fixed' && !li.data('roomid')){
                    msgbox.openUrl(U('public/WebMessage/'+li.data('id')));
                }else{
                    msgbox.openRoom('roomid='+li.data('roomid'));
                }
            }

            $(window).scroll(function(e) {
                var shield = $('#msgbox-shield');
                if(shield.length > 0){
                    $(window).scrollTop(shield.data('st'));
                }
            });
            
            var setTaskRoom = function(pos){
                var limit = (($(window).height()-40)/50)-4;
                $.get(U('public/WebMessage/latelyRoomList'), {limit:limit}, function(res){
                    if(!res.data) return;
                    var i;
                    for(i in res.data){
                        taskbar.addLi({
                            id : 'room'+res.data[i].room_id,
                            title : res.data[i].title,
                            src : res.data[i].src,
                            num: res.data[i].msg_new,
                            roomid : res.data[i].room_id
                        }, pos?pos:'append');
                    }
                }, 'json');
            }
            
            setTaskRoom('append');
            
            setInterval(function(){ setTaskRoom('prepend'); }, 30000);
            
            self.params.taskbar.removeLi = function(li){
                var data = {roomid: li.data('roomid')};
                $.get(U('public/WebMessage/clearMessage'), data, function(res){}, 'json');
            }

        });
        
        
    };

    self.taskbar = taskbar;
    self.msgbox  = msgbox;
    self.openUrl = msgbox.openUrl;
    self.openRoom = msgbox.openRoom;
    self.close   = msgbox.close;
    self.setMessageNumber = taskbar.setMessageNumber;
    self.scrollY = msgbox.scrollY;
    self.onclose = msgbox.onclose;
    return undefined;
};