jQuery(function($){
	var my = $.mysetting;

	try{
		if(my.behave == '1'){
			$('textarea').each(function(){
				var params = $([
					'replaceTab',
					'softTabs',
					'autoOpen',
					'overwrite',
					'autoStrip',
					'autoIndent'
				])
				var param = {textarea:this,tabSize:my['behave-tabSize']};
				params.each(function(idx){
					var name = params[idx];
					param[name] = my['behave-'+name] == '1' ? true : false;
				});
				new Behave(param);
			});
		}
	}
	catch(e){
	}

});

