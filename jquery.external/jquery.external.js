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