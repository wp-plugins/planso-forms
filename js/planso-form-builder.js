(function( $ ) {
	$(document).ready(function(){
		var $ = jQuery;
		var is_mobile = false;
		if( navigator.userAgent.match(/Android/i)
		 || navigator.userAgent.match(/webOS/i)
		 || navigator.userAgent.match(/iPhone/i)
		 || navigator.userAgent.match(/iPad/i)
		 || navigator.userAgent.match(/iPod/i)
		 || navigator.userAgent.match(/BlackBerry/i)
		 || navigator.userAgent.match(/Windows Phone/i)
		){
			is_mobile = true;	
		}
		if(!is_mobile && $('.planso-form-builder').length > 0){
			
			$('.planso-form-builder input[type="date"],.planso-form-builder input[type="datetime"],.planso-form-builder input[type="time"]').each(function(){
				 $(this).closest('.form-group .input-group').addClass('date');
			});
			if( $('.planso-form-builder input[type="date"]').length > 0){
				$('.planso-form-builder input[type="date"]').attr('type','text').each(function(){
					
					if( $(this).closest('.form-group .input-group').length > 0){
						var me = $(this).closest('.form-group .input-group')
					} else {
						var me = $(this);
					}
					me.datetimepicker({
	          locale: planso_form_builder.locale,
	          format:'l',
	          useCurrent:false,
	          showTodayButton:true,
	          showClear:true,
	          icons: {
              time: 'fa fa-clock-o',
	            date: 'fa fa-calendar',
	            up: 'fa fa-arrow-up',
	            down: 'fa fa-arrow-down',
	            previous: 'fa fa-arrow-left',
	            next: 'fa fa-arrow-right',
	            today: 'fa fa-calendar',
	            clear: 'fa fa-trash'
            }
		      });
		    });
			}
			if( $('.planso-form-builder input[type="datetime"]').length > 0){
				$('.planso-form-builder input[type="datetime"]').attr('type','text').each(function(){
					
					if( $(this).closest('.form-group .input-group').length > 0){
						var me = $(this).closest('.form-group .input-group')
					} else {
						var me = $(this);
					}
					me.datetimepicker({
	          locale: planso_form_builder.locale,
	          useCurrent:false,
	          showTodayButton:true,
	          showClear:true,
	          icons: {
              time: 'fa fa-clock-o',
	            date: 'fa fa-calendar',
	            up: 'fa fa-arrow-up',
	            down: 'fa fa-arrow-down',
	            previous: 'fa fa-arrow-left',
	            next: 'fa fa-arrow-right',
	            today: 'fa fa-calendar',
	            clear: 'fa fa-trash'
            }
          });
	      });
			}
			if( $('.planso-form-builder input[type="time"]').length > 0){
				$('.planso-form-builder input[type="time"]').attr('type','text').each(function(){
					
					if( $(this).closest('.form-group .input-group').length > 0){
						var me = $(this).closest('.form-group .input-group')
					} else {
						var me = $(this);
					}
					me.datetimepicker({
	          locale: planso_form_builder.locale,
	          showTodayButton:true,
	          showClear:true,
	          format:'HH:mm',
	          useCurrent:false,
	          icons: {
              time: 'fa fa-clock-o',
	            date: 'fa fa-calendar',
	            up: 'fa fa-arrow-up',
	            down: 'fa fa-arrow-down',
	            previous: 'fa fa-arrow-left',
	            next: 'fa fa-arrow-right',
	            today: 'fa fa-clock-o',
	            clear: 'fa fa-trash'
            }
          });
	      });
			}
		}
	});
}( jQuery ));