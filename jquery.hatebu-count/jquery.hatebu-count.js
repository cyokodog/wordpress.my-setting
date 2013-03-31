(function($){
	$.hatebuCount = function(url,callback){
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
	$.fn.hatebuCount = function(){
		this.each(function(){
			var target = $(this);
			$.hatebuCount(target.prop('href'),function(count){
				!count ||
					target.after('<a class="hatebu-count" target="_blank"/>').next().text(count+'user').prop({
						'href' :
							'http://b.hatena.ne.jp/entry/' +
							target.prop('hostname') + '/' +
							target.prop('pathname')
					});
			});
		});
	};
})(jQuery);