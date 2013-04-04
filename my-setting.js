;(function($){
	var my = $.mysetting;

	if(my.hatebu_users == '1'){
		$($.mysetting.hatebu_users_code || 'a').hatebuUsers();
	}

	;(function(){
		var social_btn_link = $(my.social_btn_link);
		if(social_btn_link.size()){
			var param = {};
			var social_button = [
				'hatebu',
				'facebook',
				'twitter',
				'googleplus'
			];
			var found = false;
			$.each(social_button,function(idx){
				var name = social_button[idx];
				var no = my[name+'_sort'];
				if(!no){
					param[name] = false;
				}
				else{
					found = true;
					param[name] = {sort:no};
				}
			});
			if(found){
				if(my.social_btn_area) param.appendTo = my.social_btn_area;
				if(my.social_large_btn == '1') param.size = 'large';
				social_btn_link.socialButtons(param);
			}
		}
	})();


	if(my.external == '1'){
		$('a').external();
	}

	if(my.ga_id != ''){
		//$.googleAnalytics(my.ga_id);
	}

	if(my.prettify == '1'){
		$('pre').each(function(){
			$(this)[0].className || $(this).addClass('prettyprint linenums');
		});
		prettyPrint();
	}
	if(my.google_search_id != ''){
		if(my.google_search_replace == '1'){
			$('<div class="google-search"/>').insertAfter('#searchform').googleCustomSearch({
				cx : my.google_search_id
			});
			$('#searchform').remove();
		}
		else
		if(my.google_search_area){
			$(my.google_search_area).googleCustomSearch({
				cx : my.google_search_id
			});
		}
	}

})(jQuery);


