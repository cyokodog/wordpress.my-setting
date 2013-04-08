;(function($){

	// Namespace
	$.social = $.social || {};

	// Constructor
	var plugin = $.social.buttons = function(option){
		var o = this,
		c = o.config = $.extend(true,{}, $.social.buttons.defaults, option, o.getDataParam());
		if(c.appendTo && $(c.appendTo).size() > c.index) {
			c.wrapper = $(c.appendTo).eq(c.index);
		}
		else{
			c.wrapper = $('<div/>').insertAfter(c.target);
		}
		c.wrapper.addClass('social-buttons-container');
		if(c.size == 'large') {
			c.wrapper.addClass('social-buttons-large');
			c.twitter['data-count'] = 'vertical';
			c.hatebu['layout'] = 'vertical-balloon';
			c.facebook['data-layout'] = 'box_count';
			c.googleplus['data-size'] = 'tall';
		}

		c.url = c.url || c.target.prop('href');
		var sort = [];
		var add_sort = function(name){
			if(c[name] && c[name].sort){
				sort[c[name].sort] = name;
			}
			return add_sort;
		}
		add_sort('twitter')('hatebu')('facebook')('googleplus');
		for(var i = 1; i <= sort.length; i++){
			o.addButton(sort[i]);
		}
	}

	// API
	$.extend($.social.buttons.prototype, {
		getDataParam : function(){
			try{eval('return ' + this.config.target.attr('data-' + plugin.id));}catch(e){return {};}
		},
		getTarget : function(){
			return this.config.target;
		},
		addButton : function(name){
			var o = this, c = o.config;
			var p = c[name],b = plugin[name];
			if(p && b){
				var urlName = (name == 'hatebu' ? 'url' : (name == 'twitter' ? 'data-url' : 'data-href' ) );
				p[urlName] = p[urlName] || c.url;
				b(p).wrap('<span class="social-buttons-wrapper"/>').parent().addClass($.social.buttons.id+'-'+name).appendTo(c.wrapper);
			}
			return o;
		}
	});

	plugin.rendar = function(name, option){
		var b = plugin[name];
		if($.extend({},plugin.defaults, option)[name] && b){
			b.rendar();
		}
	}


	// Setting
	$.extend($.social.buttons,{
		defaults : {
			api : false,
			appendTo : '',
			url : '',
			size : '',	//large
			hatebu : {
				sort : 1
			},
			twitter : {
				sort : 2
			},
			facebook : {
				sort : 3
			},
			googleplus : {
				sort : 4
			}
		},
		version : '0.1.0',
		id : 'social-buttons'
	});

	// jQuery Method
	$.fn.socialButtons = function(option){
		var targets = this,api = [];
		targets.each(function(index) {
			var target = targets.eq(index);
			var obj = target.data(plugin.id) ||
				new $.social.buttons($.extend({}, option, {'target': target,'targets': targets, 'index': index}));
			api.push(obj);
			target.data(plugin.id, obj);
		});
		plugin.rendar('hatebu', option);
		plugin.rendar('twitter', option);
		plugin.rendar('googleplus', option);
		plugin.rendar('facebook', option);
		return option && option.api ? ($.ex.api ? $.ex.api(api) : api.length > 1 ? api : api[0]) : targets;
	}
})(jQuery);


;(function($){
	var sb = $.social.buttons;
	sb.twitter = function(option){
		var c = $.extend({},sb.twitter.defaults,option);
		var data = {};
		for(var i in c) !c[i] || !(/^data-.+/.test(i)) || (data[i] = c[i]);
		return $('<a class="twitter-share-button" href="https://twitter.com/share"/>').
			text(c.text).
			attr(data);
	}
	sb.twitter.rendar = function(){
		$('<script/>').prop({
			charset : 'utf-8',
			src : 'http://platform.twitter.com/widgets.js'
		}).appendTo('body');
	}
	sb.twitter.defaults = {
		'text' : 'Tweet',
		'data-url' : '',
		'data-text' : '',
		'data-via' : '',
		'data-lang' : '',
		'data-size' : '',
		'data-related' : '',
		'data-count' : '', //vertical
		'data-hashtags' : ''
	}
})(jQuery);

;(function($){
	var sb = $.social.buttons;
	sb.hatebu = function(option){
		var c = $.extend({},sb.hatebu.defaults,option);
		if(!c.title && c.url == location.href) c.title = $('title').text();
		return $('<a class="hatena-bookmark-button"></a>').
			prop({
				'href':'http://b.hatena.ne.jp/entry/' + encodeURIComponent(c.url),
				title : c.title
			}).
			attr({
				'data-hatena-bookmark-layout':c.layout
			});
	}
	sb.hatebu.rendar = function(){
		$('<script/>').prop({
			charset : 'utf-8',
			async : 'async',
			src : 'http://b.st-hatena.com/js/bookmark_button.js'
		}).appendTo('body');
	}		 
	sb.hatebu.defaults = {
		url : location.href,
		title : 'このエントリーをはてなブックマークに追加',
		layout : 'simple-balloon'	//simple,simple-balloon,standard-balloon,vertical-balloon
	}
})(jQuery);


;(function($){
	var sb = $.social.buttons;
	sb.facebook = function(option){
		var c = $.extend({},sb.facebook.defaults,option);
		var data = {};
		for(var i in c) !c[i] || !(/^data-.+/.test(i)) || (data[i] = c[i]);
		return $('<div class="fb-like facebook-like-button"/>').attr(data);
	}
	sb.facebook.rendar = function(){
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			js = d.createElement(s);
			js.id = id;
			js.src = '//connect.facebook.net/ja_JP/all.js#xfbml=1';
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	}
	sb.facebook.defaults = {
		'data-href' : location.href,
		'data-send' : 'false',
		'data-layout' : 'button_count',	//button_count,box_count
		'data-show-faces' : 'false'
	}
})(jQuery);


;(function($){
	var sb = $.social.buttons;
	sb.googleplus = function(option){
		var c = $.extend({},sb.googleplus.defaults,option);
		var data = {};
		for(var i in c) !c[i] || !(/^data-.+/.test(i)) || (data[i] = c[i]);
		return $('<div class="g-plusone"/>').attr(data);
	}
	sb.googleplus.rendar = function(){
		$('<script/>').prop({
			charset : 'utf-8',
			src : 'https://apis.google.com/js/plusone.js'
		}).appendTo('body');
	}
	sb.googleplus.defaults = {
		'data-href' : location.href,
		'data-size': 'Medium'	//Medium,tall
	}
})(jQuery);

//Hatebu Users
(function($){
	$.hatebuUsers = function(url,callback){
		$.ajax({
			type: 'GET',
			url: 'http://api.b.st-hatena.com/entry.count',
			data: {
				url : url
			},
			dataType: 'jsonp',
			success: callback
		});
	}
	$.fn.hatebuUsers = function(){
		this.each(function(){
			var target = $(this);
			$.hatebuUsers(target.prop('href'),function(count){
				!count ||
					target.after('<a class="hatebu-users" target="_blank"/>').next().text(count+'users').prop({
						'href' :
							'http://b.hatena.ne.jp/entry/' +
							target.prop('hostname') + 
							target.prop('pathname')
					});
			});
		});
	};
})(jQuery);


//External
(function(){
	$.fn.external = function(option){
		var c = $.extend($.fn.external.defaults,option||{});
		var reg = new RegExp('^' + location.host);
		this.each(function(idx) {
			var target = $(this);
			if(!reg.test(target[0]['host']) && target.prop('href')){
				target.prop('target','_blank');
				if(c.className && !target.find('img').size()) target.addClass(c.className);
			}
		});
		return this;
	}
	$.fn.external.defaults = {
		className : 'external'
	}
})(jQuery);

//Google Analytics
(function(){
	$.googleAnalytics = function(ga_id){
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', ga_id]);
		_gaq.push(['_trackPageview']);
		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	}
})(jQuery);





//Google Custom Search
(function($){
	var plugin = $.googleCustomSearch = function(target,option){
		var o = this,
		c = o.config = $.extend(true,{}, plugin.defaults, option);
		if(!c.cx) return;

		if(!c.displayButton) target.addClass('hide-gcse-button');

		$('<gcse:search></gcse:search>').appendTo(target);
		var gcse = document.createElement('script');
		gcse.type = 'text/javascript';
		gcse.async = true;
		gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
		    '//www.google.com/cse/cse.js?cx=' + c.cx;
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(gcse, s);
	}
	plugin.defaults = {
		cx : '',
		displayButton : true
	}
	$.fn.googleCustomSearch = function(option){
		this.each(function(){
			var target = $(this);
			$.googleCustomSearch(target,option);
		});
	};
})(jQuery);
