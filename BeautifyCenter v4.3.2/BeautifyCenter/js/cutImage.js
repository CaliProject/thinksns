function ImgCut(containerName,imgSrc,cutW,cutH,dCutLeft,dCutTop){
    this.ContainerName=containerName;
	this.ImgSrc=imgSrc;
	this.CutW=cutW;
	this.CutH=cutH;
	this.DCutLeft=dCutLeft;
	this.DCutTop=dCutTop;
	this.Fill();
}
ImgCut.prototype={
    ContainerName:"",//在何处创建对象
	ImgSrc:"",//原始图片的地址
	CutW:150,//截取的宽度
	CutH:200,//截取的高度
	DCutLeft:100,//截取框距外框左右边距
	DCutTop:100,//截取框距外框上下边距
	Fill:function(){
		$("#"+this.ContainerName).append('<div class="imgCut1" id="cutImage">'+
			'<div class="imgCut11" style="width:250px;height:60px;">'+
			'<img class="imgCut111" src="'+this.ImgSrc+'" id="temp_img"/>'+
			'<table class="imgCut112" border="0" cellpadding="0" cellspacing="0">'+
			'<tr><td/><td/><td/></tr><tr><td/><td class="main"/><td/>'+
			'</tr><tr><td/><td/><td/></tr></table></div>'+
			'<div class="imgCut12"><div class="imgCut121"></div><div class="imgCut122">'+
			'<div class="imgCut1221"></div></div><div class="imgCut123"></div></div></div>');
		$("#"+this.ContainerName+" .imgCut1").css({width:(this.CutW+this.DCutLeft*2+2 < 249 ? 249 : this.CutW+this.DCutLeft*2+2),height:this.CutH+this.DCutTop*2+20+2});		
		$("#"+this.ContainerName+" .imgCut11").css({width:this.CutW+this.DCutLeft*2,height:this.CutH+this.DCutTop*2});
		$("#"+this.ContainerName+" .imgCut112 td").css({width:this.DCutLeft,height:this.DCutTop});
		$("#"+this.ContainerName+" .imgCut112 td.main").css({width:this.CutW,height:this.CutH});
		this.Drag("#"+this.ContainerName+" .imgCut112","#"+this.ContainerName+" .imgCut111");
		var jImgCut111=$("#"+this.ContainerName+" .imgCut111");
		var w=jImgCut111.get(0).offsetWidth;
		var h=jImgCut111.get(0).offsetHeight;
		var zoom=function(e){
			var neww=parseInt(w/100*e.left);
			var newh=parseInt(neww/w*h);
			jImgCut111.css({width:neww,height:newh});
		};
		this.Drag("#"+this.ContainerName+" .imgCut1221","#"+this.ContainerName+" .imgCut1221","#"+this.ContainerName+" .imgCut122","h",zoom);
		var jImgCut1221=$("#"+this.ContainerName+" .imgCut1221");
		$("#"+this.ContainerName+" .imgCut123").click(function(){var l=parseInt(jImgCut1221.css("left").replace("px",""))+1;l=l>200?200:l;jImgCut1221.css("left",l);zoom({left:l});});
		$("#"+this.ContainerName+" .imgCut121").click(function(){var l=parseInt(jImgCut1221.css("left").replace("px",""))-1;l=l<0?0:l;jImgCut1221.css("left",l);zoom({left:l});});
	},
	Drag:function(touchScreenName,dragName,rangeName,track,onDragFunction){
	    //鼠标是否被按下
	    var isMouseDown=false;
		//鼠标上次移动的X坐标
		var cX=0;
		//鼠标上次移动的Y坐标
		var cY=0;
		//触摸屏是否与拖拽对象分离
	    var jObjImgGrag=dragName && $(dragName) || $(touchScreenName);
		//是否限制活动范围
		var rangeX=[];
		var rangeY=[];
		if(rangeName){
			rangeX=[0,$(rangeName).get(0).offsetWidth-jObjImgGrag.get(0).offsetWidth];
			rangeY=[0,$(rangeName).get(0).offsetHeight-jObjImgGrag.get(0).offsetHeight];
		}
		$(touchScreenName).mousedown(function(){isMouseDown=true;$(this).css("cursor","move");cX=0;cY=0;});
		$(document).mouseup(function(){isMouseDown=false;$(touchScreenName).css("cursor","pointer");}).mousemove(function(e){
		     //这句很重要,使得拖拽更流畅
		     try {document.selection && document.selection.empty && (document.selection.empty(), 1) || window.getSelection && window.getSelection().removeAllRanges();}             catch(exp){}
			 if(!isMouseDown)
			     return;
		     if(cX==0 && cY==0){
			     cX=e.clientX;
				 cY=e.clientY;
		     }
			 if(track){
			     if(track=="h") cY=e.clientY;
				 if(track=="v") cX=e.clientX;
			 }
			 var newLeft=parseInt(jObjImgGrag.css("left").replace("px",""))+e.clientX-cX;
			 var newTop=parseInt(jObjImgGrag.css("top").replace("px",""))+e.clientY-cY;
			 if(rangeName){
			     newLeft=newLeft<rangeX[0] ? 0 : newLeft;
				 newLeft=newLeft>rangeX[1] ? rangeX[1] : newLeft;
				 newTop=newTop<rangeY[0] ? 0 : newTop;
				 newTop=newTop>rangeY[1] ? rangeY[1] : newTop;
			 }
			 //开始拖拽
			 jObjImgGrag.css({left:newLeft,top:newTop});
			 //拖拽时触发的函数
			 onDragFunction && onDragFunction({left:newLeft,top:newTop});
			 cX=e.clientX;
			 cY=e.clientY;
		});
	},
	GetCutLeft:function(){
	    return this.DCutLeft-parseInt($("#"+this.ContainerName+" .imgCut111").css("left").replace("px",""));
	},//相对于原始图左边的位置
	GetCutTop:function(){
	    return this.DCutTop-parseInt($("#"+this.ContainerName+" .imgCut111").css("top").replace("px",""));
	},//相对于原始图顶边的位置
	GetImgWidth:function(){
	    return $("#"+this.ContainerName+" .imgCut111").get(0).offsetWidth;
	},//原始图当前的宽度
	GetImgHeight:function(){
	    return $("#"+this.ContainerName+" .imgCut111").get(0).offsetHeight;
	}//原始图当前的高度
}