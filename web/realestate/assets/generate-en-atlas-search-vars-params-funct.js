

	var updateText = 'Loading ...';
	var resultBlock = 'appartment_box';
	var bg_img = '/realestate/themes/atlas/images/pages/opacity.png';

	var useGoogleMap = 0;
	var useYandexMap = 0;
	var useOSMap = 1;
		
	$(function () {
		$('div#appartment_box').on('mouseover mouseout', 'div.appartment_item', function(event){
			if (event.type == 'mouseover') {
			 $(this).find('div.apartment_item_edit').show();
			} else {
			 $(this).find('div.apartment_item_edit').hide();
			}
		});

		if(modeListShow == 'map'){
			list.apply();
		}
	});
	
    var urlsSwitching = {'block':'https\x3A\x2F\x2Fprodigies.ai\x2Frealestate\x2F\x3Fls\x3Dblock','table':'https\x3A\x2F\x2Fprodigies.ai\x2Frealestate\x2F\x3Fls\x3Dtable','map':'https\x3A\x2F\x2Fprodigies.ai\x2Frealestate\x2F\x3Fls\x3Dmap'};
	var modeListShow = 'block';
	
    function setListShow(mode){
        modeListShow = mode;
        reloadApartmentList(urlsSwitching[mode]);
    };
