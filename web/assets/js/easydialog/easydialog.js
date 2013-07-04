/** 
 * easyDialog v2.2
 * Url : http://stylechen.com/easydialog-v2.0.html
 * Author : chenmnkken@gmail.com
 * Date : 2012-04-22
 */
(function( win, undefined ){

var	doc = win.document,
	docElem = doc.documentElement;

var easyDialog = function(){

var	body = doc.body,
	isIE = !-[1,],	// 判断IE6/7/8 不能判断IE9
	isIE6 = isIE && /msie 6/.test( navigator.userAgent.toLowerCase() ), // 判断IE6
	uuid = 1,
	expando = 'cache' + ( +new Date() + "" ).slice( -8 ),  // 生成随机数
	cacheData = {
	/**
	 *	1 : {
	 *		eclick : [ handler1, handler2, handler3 ]; 
	 *		clickHandler : function(){ //... }; 
	 *	} 
	 */	
	};

var	Dialog = function(){};

Dialog.prototype = {
	// 参数设置
	getOptions : function( arg ){
		var i,
			options = {},
			// 默认参数
			defaults = {
				container:   null,			// string / object   弹处层内容的id或内容模板
				overlay:     true,			// boolean  		 是否添加遮罩层
				drag:	     true,			// boolean  		 是否绑定拖拽事件
				fixed: 	     true,			// boolean  		 是否静止定位
				follow:      null,			// string / object   是否跟随自定义元素来定位
				followX:     0,				// number   		 相对于自定义元素的X坐标的偏移
				followY:     0,				// number  		     相对于自定义元素的Y坐标的偏移
				autoClose:   0,				// number            自动关闭弹出层的时间
				lock:        false,			// boolean           是否允许ESC键来关闭弹出层
				callback:    null			// function          关闭弹出层后执行的回调函数
				/** 
				 *  container为object时的参数格式
				 *	container : {
				 *		header : '弹出层标题',
				 *		content : '弹出层内容',
				 *		yesFn : function(){},	    // 确定按钮的回调函数
				 *		noFn : function(){} / true,	// 取消按钮的回调函数
				 *		yesText : '确定',		    // 确定按钮的文本，默认为‘确定’
				 *		noText : '取消' 		    // 取消按钮的文本，默认为‘取消’		
				 *	}		
				 */
			};
		
		for( i in defaults ){
			options[i] = arg[i] !== undefined ? arg[i] : defaults[i];
		}
		Dialog.data( 'options', options );
		return options;
	},
		
	// 防止IE6模拟fixed时出现抖动
	setBodyBg : function(){
		if( body.currentStyle.backgroundAttachment !== 'fixed' ){
			body.style.backgroundImage = 'url(about:blank)';
			body.style.backgroundAttachment = 'fixed';
		}
	},
	
	// 防止IE6的select穿透
	appendIframe : function(elem){
		elem.innerHTML = '<iframe style="position:absolute;left:0;top:0;width:100%;height:100%;z-index:-1;border:0 none;filter:alpha(opacity=0)"></iframe>';
	},
	
	/**
	 * 设置元素跟随定位
	 * @param { Object } 跟随的DOM元素
	 * @param { String / Object } 被跟随的DOM元素
	 * @param { Number } 相对于被跟随元素的X轴的偏移
	 * @param { Number } 相对于被跟随元素的Y轴的偏移
	 */
	setFollow : function( elem, follow, x, y ){
		follow = typeof follow === 'string' ? doc.getElementById( follow ) : follow;
		var style = elem.style;
		style.position = 'absolute';			
		style.left = Dialog.getOffset( follow, 'left') + x + 'px';
		style.top = Dialog.getOffset( follow, 'top' ) + y + 'px';
	},
	
	/**
	 * 设置元素固定(fixed) / 绝对(absolute)定位
	 * @param { Object } DOM元素
	 * @param { Boolean } true : fixed, fasle : absolute
	 */
	setPosition : function( elem, fixed ){
		var style = elem.style;
		style.position = isIE6 ? 'absolute' : fixed ? 'fixed' : 'absolute';
		if( fixed ){
			if( isIE6 ){
				style.setExpression( 'top','fuckIE6=document.documentElement.scrollTop+document.documentElement.clientHeight/2+"px"' );
			}
			else{
				style.top = '50%';
			}
			style.left = '50%';
		}
		else{
			if( isIE6 ){
				style.removeExpression( 'top' );
			}
			style.top = docElem.clientHeight/2 + Dialog.getScroll( 'top' ) + 'px';
			style.left = docElem.clientWidth/2 + Dialog.getScroll( 'left' ) + 'px';
		}
	},
	
	/**
	 * 创建遮罩层
	 * @return { Object } 遮罩层 
	 */
	createOverlay : function(){
		var overlay = doc.createElement('div'),
			style = overlay.style;
			
		style.cssText = 'margin:0;padding:0;border:none;width:100%;height:100%;background:#333;opacity:0.6;filter:alpha(opacity=60);z-index:9999;position:fixed;top:0;left:0;';
		
		// IE6模拟fixed
		if(isIE6){
			body.style.height = '100%';
			style.position = 'absolute';
			style.setExpression('top','fuckIE6=document.documentElement.scrollTop+"px"');
		}
		
		overlay.id = 'overlay';
		return overlay;
	},
	
	/**
	 * 创建弹出层
	 * @return { Object } 弹出层 
	 */
	createDialogBox : function(){
		var dialogBox = doc.createElement('div');		
		dialogBox.style.cssText = 'margin:0;padding:0;border:none;z-index:10000;';
		dialogBox.id = 'easyDialogBox';		
		return dialogBox;
	},

	/**
	 * 创建默认的弹出层内容模板
	 * @param { Object } 模板参数
	 * @return { Object } 弹出层内容模板
	 */
	createDialogWrap : function( tmpl ){
		// 弹出层标题
		var header = tmpl.header ? 
			'<h4 class="easyDialog_title" id="easyDialogTitle"><a href="javascript:void(0)" title="关闭窗口" class="close_btn" id="closeBtn">&times;</a>' + tmpl.header + '</h4>' :
			'',
			// 确定按钮
			yesBtn = typeof tmpl.yesFn === 'function' ? 
				'<button class="btn_highlight" id="easyDialogYesBtn">' + ( typeof tmpl.yesText === 'string' ? tmpl.yesText : '确定' ) + '</button>' :
				'',
			// 取消按钮	
			noBtn = typeof tmpl.noFn === 'function' || tmpl.noFn === true ? 
				'<button class="btn_normal" id="easyDialogNoBtn">' + ( typeof tmpl.noText === 'string' ? tmpl.noText : '取消' ) + '</button>' :
				'',			
			// footer
			footer = yesBtn === '' && noBtn === '' ? '' :
				'<div class="easyDialog_footer">' + noBtn + yesBtn + '</div>',
			
			dialogTmpl = [
			'<div class="easyDialog_content">',
				header,
				'<div class="easyDialog_text">' + tmpl.content + '</div>',
				footer,
			'</div>'
			].join(''),

			dialogWrap = doc.getElementById( 'easyDialogWrapper' ),
			rScript = /<[\/]*script[\s\S]*?>/ig;
			
		if( !dialogWrap ){
			dialogWrap = doc.createElement( 'div' );
			dialogWrap.id = 'easyDialogWrapper';
			dialogWrap.className = 'easyDialog_wrapper';
		}
		dialogWrap.innerHTML = dialogTmpl.replace( rScript, '' );		
		return dialogWrap;
	}		
};
	
/**
 * 设置并返回缓存的数据 关于缓存系统详见：http://stylechen.com/cachedata.html
 * @param { String / Object } 任意字符串或DOM元素
 * @param { String } 缓存属性名
 * @param { Anything } 缓存属性值
 * @return { Object } 
 */
Dialog.data = function( elem, val, data ){
    if( typeof elem === 'string' ){
        if( val !== undefined ){
			cacheData[elem] = val;
	    }
		return cacheData[elem];
	}
	else if( typeof elem === 'object' ){
		// 如果是window、document将不添加自定义属性
		// window的索引是0 document索引为1
		var index = elem === win ? 0 : 
				elem.nodeType === 9 ? 1 : 
				elem[expando] ? elem[expando] : 
				(elem[expando] = ++uuid),
			
			thisCache = cacheData[index] ? cacheData[index] : ( cacheData[index] = {} );
				
		if( data !== undefined ){
			// 将数据存入缓存中
			thisCache[val] = data;
		}
		// 返回DOM元素存储的数据
		return thisCache[val];
	}
};

/**
 * 删除缓存
 * @param { String / Object } 任意字符串或DOM元素
 * @param { String } 要删除的缓存属性名
 */
Dialog.removeData = function( elem, val ){
	if( typeof elem === 'string' ){
		delete cacheData[elem];
	}
	else if( typeof elem === 'object' ){
		var index = elem === win ? 0 :
				elem.nodeType === 9 ? 1 :
				elem[expando];
			
		if( index === undefined ) return;		
		// 检测对象是否为空
		var isEmptyObject = function( obj ) {
				var name;
				for ( name in obj ) {
					return false;
				}
				return true;
			},
			// 删除DOM元素所有的缓存数据
			delteProp = function(){
				delete cacheData[index];
				if( index <= 1 ) return;
				try{
					// IE8及标准浏览器可以直接使用delete来删除属性
					delete elem[expando];
				}
				catch ( e ) {
					// IE6/IE7使用removeAttribute方法来删除属性(document会报错)
					elem.removeAttribute( expando );
				}
			};

		if( val ){
			// 只删除指定的数据
			delete cacheData[index][val];
			if( isEmptyObject( cacheData[index] ) ){
				delteProp();
			}
		}
		else{
			delteProp();
		}
	}
};

// 事件处理系统
Dialog.event = {
	
	bind : function( elem, type, handler ){
		var events = Dialog.data( elem, 'e' + type ) || Dialog.data( elem, 'e' + type, [] );
		// 将事件函数添加到缓存中
		events.push( handler );
		// 同一事件类型只注册一次事件，防止重复注册
		if( events.length === 1 ){
			var eventHandler = this.eventHandler( elem );
			Dialog.data( elem, type + 'Handler', eventHandler );
			if( elem.addEventListener ){
				elem.addEventListener( type, eventHandler, false );
			}
			else if( elem.attachEvent ){
				elem.attachEvent( 'on' + type, eventHandler );
			}
		}
	},
		
	unbind : function( elem, type, handler ){
		var events = Dialog.data( elem, 'e' + type );
		if( !events ) return;
			
		// 如果没有传入要删除的事件处理函数则删除该事件类型的缓存
		if( !handler ){
			events = undefined;		
		}
		// 如果有具体的事件处理函数则只删除一个
		else{
			for( var i = events.length - 1, fn = events[i]; i >= 0; i-- ){
				if( fn === handler ){
					events.splice( i, 1 );
				}				
			}
		}		
		// 删除事件和缓存
		if( !events || !events.length ){
			var eventHandler = Dialog.data( elem, type + 'Handler' );			
			if( elem.addEventListener ){
				elem.removeEventListener( type, eventHandler, false );
			}
			else if( elem.attachEvent ){
				elem.detachEvent( 'on' + type, eventHandler );
			}		
			Dialog.removeData( elem, type + 'Handler' );
			Dialog.removeData( elem, 'e' + type );
		}
	},
		
	// 依次执行事件绑定的函数
	eventHandler : function( elem ){
		return function( event ){
			event = Dialog.event.fixEvent( event || win.event );
			var type = event.type,
				events = Dialog.data( elem, 'e' + type );
				
			for( var i = 0, handler; handler = events[i++]; ){
				if( handler.call(elem, event) === false ){
					event.preventDefault();
					event.stopPropagation();
				}
			}
		}
	},
	
	// 修复IE浏览器支持常见的标准事件的API
	fixEvent : function( e ){
		// 支持DOM 2级标准事件的浏览器无需做修复
		if ( e.target ) return e; 
		var event = {}, name;
		event.target = e.srcElement || document;
		event.preventDefault = function(){
			e.returnValue = false;
		};		
		event.stopPropagation = function(){
			e.cancelBubble = true;
		};
		// IE6/7/8在原生的window.event中直接写入自定义属性
		// 会导致内存泄漏，所以采用复制的方式
		for( name in e ){
			event[name] = e[name];
		}				
		return event;
	}
};

/**
 * 首字母大写转换
 * @param { String } 要转换的字符串
 * @return { String } 转换后的字符串 top => Top
 */
Dialog.capitalize = function( str ){
	var firstStr = str.charAt(0);
	return firstStr.toUpperCase() + str.replace( firstStr, '' );
};

/**
 * 获取滚动条的位置
 * @param { String } 'top' & 'left'
 * @return { Number } 
 */	
Dialog.getScroll = function( type ){
	var upType = this.capitalize( type );		
	return docElem['scroll' + upType] || body['scroll' + upType];	
};

/**
 * 获取元素在页面中的位置
 * @param { Object } DOM元素
 * @param { String } 'top' & 'left'
 * @return { Number } 
 */		
Dialog.getOffset = function( elem, type ){
	var upType = this.capitalize( type ),
		client  = docElem['client' + upType]  || body['client' + upType]  || 0,
		scroll  = this.getScroll( type ),
		box = elem.getBoundingClientRect();
		
	return Math.round( box[type] ) + scroll - client;
};

/**
 * 拖拽效果
 * @param { Object } 触发拖拽的DOM元素
 * @param { Object } 要进行拖拽的DOM元素
 */
Dialog.drag = function( target, moveElem ){
	// 清除文本选择
	var	clearSelect = 'getSelection' in win ? function(){
		win.getSelection().removeAllRanges();
		} : function(){
			try{
				doc.selection.empty();
			}
			catch( e ){};
		},
		
		self = this,
		event = self.event,
		isDown = false,
		newElem = isIE ? target : doc,
		fixed = moveElem.style.position === 'fixed',
		_fixed = Dialog.data( 'options' ).fixed;
	
	// mousedown
	var down = function( e ){
		isDown = true;
		var scrollTop = self.getScroll( 'top' ),
			scrollLeft = self.getScroll( 'left' ),
			edgeLeft = fixed ? 0 : scrollLeft,
			edgeTop = fixed ? 0 : scrollTop;
		
		Dialog.data( 'dragData', {
			x : e.clientX - self.getOffset( moveElem, 'left' ) + ( fixed ? scrollLeft : 0 ),	
			y : e.clientY - self.getOffset( moveElem, 'top' ) + ( fixed ? scrollTop : 0 ),			
			// 设置上下左右4个临界点的位置
			// 固定定位的临界点 = 当前屏的宽、高(下、右要减去元素本身的宽度或高度)
			// 绝对定位的临界点 = 当前屏的宽、高 + 滚动条卷起部分(下、右要减去元素本身的宽度或高度)
			el : edgeLeft,	// 左临界点
			et : edgeTop,  // 上临界点
			er : edgeLeft + docElem.clientWidth - moveElem.offsetWidth,  // 右临界点
			eb : edgeTop + docElem.clientHeight - moveElem.offsetHeight  // 下临界点
		});
		
		if( isIE ){
			// IE6如果是模拟fixed在mousedown的时候先删除模拟，节省性能
			if( isIE6 && _fixed ){
				moveElem.style.removeExpression( 'top' );
			}
			target.setCapture();
		}
		
		event.bind( newElem, 'mousemove', move );
		event.bind( newElem, 'mouseup', up );
		
		if( isIE ){
			event.bind( target, 'losecapture', up );
		}
		
		e.stopPropagation();
		e.preventDefault();
		
	};
	
	event.bind( target, 'mousedown', down );
	
	// mousemove
	var move = function( e ){
		if( !isDown ) return;
		clearSelect();
		var dragData = Dialog.data( 'dragData' ),
			left = e.clientX - dragData.x,
			top = e.clientY - dragData.y,
			et = dragData.et,
			er = dragData.er,
			eb = dragData.eb,
			el = dragData.el,
			style = moveElem.style;
		
		// 设置上下左右的临界点以防止元素溢出当前屏
		style.marginLeft = style.marginTop = '0px';
		style.left = ( left <= el ? el : (left >= er ? er : left) ) + 'px';
		style.top = ( top <= et ? et : (top >= eb ? eb : top) ) + 'px';
		e.stopPropagation();
	};
	
	// mouseup
	var up = function( e ){
		isDown = false;
		if( isIE ){
			event.unbind( target, 'losecapture', arguments.callee );
		}
		event.unbind( newElem, 'mousemove', move );
		event.unbind( newElem, 'mouseup', arguments.callee );		
		if( isIE ){
			target.releaseCapture();
			// IE6如果是模拟fixed在mouseup的时候要重新设置模拟
			if( isIE6 && _fixed ){
				var top = parseInt( moveElem.style.top ) - self.getScroll( 'top' );
				moveElem.style.setExpression('top',"fuckIE6=document.documentElement.scrollTop+" + top + '+"px"');
			}
		}
		e.stopPropagation();
	};
};

var	timer,	// 定时器
	// ESC键关闭弹出层
	escClose = function( e ){
		if( e.keyCode === 27 ){
			extend.close();
		}
	},	
	// 清除定时器
	clearTimer = function(){
		if( timer ){
			clearTimeout( timer );
			timer = undefined;
		}
	};
	
var extend = {
	open : function(){
		var $ = new Dialog(),
			options = $.getOptions( arguments[0] || {} ),	// 获取参数
			event = Dialog.event,
			docWidth = docElem.clientWidth,
			docHeight = docElem.clientHeight,
			self = this,
			overlay,
			dialogBox,
			dialogWrap,
			boxChild;
			
		clearTimer();
		
		// ------------------------------------------------------
		// ---------------------插入遮罩层-----------------------
		// ------------------------------------------------------
		
		// 如果页面中已经缓存遮罩层，直接显示
		if( options.overlay ){
			overlay = doc.getElementById( 'overlay' );			
			if( !overlay ){
				overlay = $.createOverlay();
				body.appendChild( overlay );
				if( isIE6 ){
					$.appendIframe( overlay );
				}
			}
			overlay.style.display = 'block';
		}
		
		if(isIE6){
			$.setBodyBg();
		}
		
		// ------------------------------------------------------
		// ---------------------插入弹出层-----------------------
		// ------------------------------------------------------
		
		// 如果页面中已经缓存弹出层，直接显示
		dialogBox = doc.getElementById( 'easyDialogBox' );
		if( !dialogBox ){
			dialogBox = $.createDialogBox();
			body.appendChild( dialogBox );
		}
		
		if( options.follow ){
			var follow = function(){
				$.setFollow( dialogBox, options.follow, options.followX, options.followY );
			};
			
			follow();
			event.bind( win, 'resize', follow );
			Dialog.data( 'follow', follow );
			if( overlay ){
				overlay.style.display = 'none';
			}
			options.fixed = false;
		}
		else{
			$.setPosition( dialogBox, options.fixed );
		}
		dialogBox.style.display = 'block';
				
		// ------------------------------------------------------
		// -------------------插入弹出层内容---------------------
		// ------------------------------------------------------
		
		// 判断弹出层内容是否已经缓存过
		dialogWrap = typeof options.container === 'string' ? 
			doc.getElementById( options.container ) : 
			$.createDialogWrap( options.container );
		
		boxChild = dialogBox.getElementsByTagName('*')[0];
		
		if( !boxChild ){
			dialogBox.appendChild( dialogWrap );
		}
		else if( boxChild && dialogWrap !== boxChild ){
			boxChild.style.display = 'none';
			body.appendChild( boxChild );
			dialogBox.appendChild( dialogWrap );
		}
		
		dialogWrap.style.display = 'block';
		
		var eWidth = dialogWrap.offsetWidth,
			eHeight = dialogWrap.offsetHeight,
			widthOverflow = eWidth > docWidth,
			heigthOverflow = eHeight > docHeight;
			
		// 强制去掉自定义弹出层内容的margin	
		dialogWrap.style.marginTop = dialogWrap.style.marginRight = dialogWrap.style.marginBottom = dialogWrap.style.marginLeft = '0px';	
		
		// 居中定位
		if( !options.follow ){			
			dialogBox.style.marginLeft = '-' + (widthOverflow ? docWidth/2 : eWidth/2) + 'px';
			dialogBox.style.marginTop = '-' + (heigthOverflow ? docHeight/2 : eHeight/2) + 'px';			
		}
		else{
			dialogBox.style.marginLeft = dialogBox.style.marginTop = '0px';
		}
				
		// 防止select穿透固定宽度和高度
		if( isIE6 && !options.overlay ){
			dialogBox.style.width = eWidth + 'px';
			dialogBox.style.height = eHeight + 'px';
		}
		
		// ------------------------------------------------------
		// --------------------绑定相关事件----------------------
		// ------------------------------------------------------
		var closeBtn = doc.getElementById( 'closeBtn' ),
			dialogTitle = doc.getElementById( 'easyDialogTitle' ),
			dialogYesBtn = doc.getElementById('easyDialogYesBtn'),
			dialogNoBtn = doc.getElementById('easyDialogNoBtn');		

		// 绑定确定按钮的回调函数
		if( dialogYesBtn ){
			event.bind( dialogYesBtn, 'click', function( event ){
				if( options.container.yesFn.call(self, event) !== false ){
					self.close();
				}
			});
		}
		
		// 绑定取消按钮的回调函数
		if( dialogNoBtn ){
			var noCallback = function( event ){
				if( options.container.noFn === true || options.container.noFn.call(self, event) !== false ){
					self.close();
				}
			};
			event.bind( dialogNoBtn, 'click', noCallback );
			// 如果取消按钮有回调函数 关闭按钮也绑定同样的回调函数
			if( closeBtn ){
				event.bind( closeBtn, 'click', noCallback );
			}
		}			
		// 关闭按钮绑定事件	
		else if( closeBtn ){
			event.bind( closeBtn, 'click', self.close );
		}
		
		// ESC键关闭弹出层
		if( !options.lock ){
			event.bind( doc, 'keyup', escClose );
		}
		// 自动关闭弹出层
		if( options.autoClose && typeof options.autoClose === 'number' ){
			timer = setTimeout( self.close, options.autoClose );
		}		
		// 绑定拖拽(如果弹出层内容的宽度或高度溢出将不绑定拖拽)
		if( options.drag && dialogTitle && !widthOverflow && !heigthOverflow ){
			dialogTitle.style.cursor = 'move';
			Dialog.drag( dialogTitle, dialogBox );
		}
		
		// 确保弹出层绝对定位时放大缩小窗口也可以垂直居中显示
		
		if( !options.follow && !options.fixed ){
			var resize = function(){
				$.setPosition( dialogBox, false );
			};
			// 如果弹出层内容的宽度或高度溢出将不绑定resize事件
			if( !widthOverflow && !heigthOverflow ){
				event.bind( win, 'resize', resize );
			}
			Dialog.data( 'resize', resize );
		}
		
		// 缓存相关元素以便关闭弹出层的时候进行操作
		Dialog.data( 'dialogElements', {
			overlay : overlay,
			dialogBox : dialogBox,
			closeBtn : closeBtn,
			dialogTitle : dialogTitle,
			dialogYesBtn : dialogYesBtn,
			dialogNoBtn : dialogNoBtn			
		});
	},
	
	close : function(){
		var options = Dialog.data( 'options' ),
			elements = Dialog.data( 'dialogElements' ),
			event = Dialog.event;
			
		clearTimer();
		//	隐藏遮罩层
		if( options.overlay && elements.overlay ){
			elements.overlay.style.display = 'none';
		}
		// 隐藏弹出层
		elements.dialogBox.style.display = 'none';
		// IE6清除CSS表达式
		if( isIE6 ){
			elements.dialogBox.style.removeExpression( 'top' );
		}
		
		// ------------------------------------------------------
		// --------------------删除相关事件----------------------
		// ------------------------------------------------------
		if( elements.closeBtn ){
			event.unbind( elements.closeBtn, 'click' );
		}

		if( elements.dialogTitle ){
			event.unbind( elements.dialogTitle, 'mousedown' );
		}
		
		if( elements.dialogYesBtn ){
			event.unbind( elements.dialogYesBtn, 'click' );
		}
		
		if( elements.dialogNoBtn ){
			event.unbind( elements.dialogNoBtn, 'click' );
		}
		
		if( !options.follow && !options.fixed ){
			event.unbind( win, 'resize', Dialog.data('resize') );
			Dialog.removeData( 'resize' );
		}
		
		if( options.follow ){
			event.unbind( win, 'resize', Dialog.data('follow') );
			Dialog.removeData( 'follow' );
		}
		
		if( !options.lock ){
			event.unbind( doc, 'keyup', escClose );
		}
		// 执行callback
		if(typeof options.callback === 'function'){
			options.callback.call( extend );
		}
		// 清除缓存
		Dialog.removeData( 'options' );
		Dialog.removeData( 'dialogElements' );
	}
};

return extend;

};

// ------------------------------------------------------
// ---------------------DOM加载模块----------------------
// ------------------------------------------------------
var loaded = function(){
		win.easyDialog = easyDialog();
	},
	
	doScrollCheck = function(){
		if ( doc.body ) return;

		try {
			docElem.doScroll("left");
		} catch(e) {
			setTimeout( doScrollCheck, 1 );
			return;
		}
		loaded();
	};

(function(){
	if( doc.body ){
		loaded();
	}
	else{
		if( doc.addEventListener ){
			doc.addEventListener( 'DOMContentLoaded', function(){
				doc.removeEventListener( 'DOMContentLoaded', arguments.callee, false );
				loaded();
			}, false );
			win.addEventListener( 'load', loaded, false );
		}
		else if( doc.attachEvent ){
			doc.attachEvent( 'onreadystatechange', function(){
				if( doc.readyState === 'complete' ){
					doc.detachEvent( 'onreadystatechange', arguments.callee );
					loaded();
				}
			});
			win.attachEvent( 'onload', loaded );			
			var toplevel = false;
			try {
				toplevel = win.frameElement == null;
			} catch(e) {}

			if ( docElem.doScroll && toplevel ) {
				doScrollCheck();
			}
		}
	}
})();

})( window, undefined );

// 2012-04-12 修复跟随定位缩放浏览器时无法继续跟随的BUG
// 2012-04-22 修复弹出层内容的尺寸大于浏览器当前屏尺寸的BUG