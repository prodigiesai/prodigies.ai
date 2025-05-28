<?php
$isInner = isset($isInner) && $isInner ? 1 : 0;
$compact = param("useCompactInnerSearchForm", true);
$loc = (issetModule('location')) ? 1 : 0;
$enableMetro = (issetModule('metroStations')) ? 1 : 0;
$urlReloadForm = Yii::app()->createUrl('/quicksearch/main/loadForm', array('lang' => Yii::app()->language));

$sumoMetroSelectCaptionFormat = CJavaScript::encode(tc('{0} Selected'));
$sumoMetroSelectAlltext = CJavaScript::encode(tc('check all'));
$sumoMetroSelectPlaceholder = CJavaScript::encode(tt('Select metro stations', 'metroStations'));
$sumoMetroFilterText = CJavaScript::encode(tc('enter initial letters'));

$sliderRangeFields = CJavaScript::encode(SearchForm::getSliderRangeFields());
$cityField = CJavaScript::encode(SearchForm::getCityField());
$countField = SearchForm::getCountFiled() + ($loc ? 2 : 0);
$isCompact = $compact ? 1 : 0;
$objType = isset($this->objType) ? $this->objType : SearchFormModel::OBJ_TYPE_ID_DEFAULT;
$datePickerLang = Yii::app()->controller->datePickerLang;

$labelLessOptions = CJavaScript::encode(tc('Less options'));
$labelMoreOptions = CJavaScript::encode(tc('More options'));

$labelSearchByDesc = CJavaScript::encode(tc("Search by description or address"));
$minLengthSearch = (int) param('minLengthSearch', 4);
$urlSearch = Yii::app()->createAbsoluteUrl('/quicksearch/main/mainsearch', array('lang' => Yii::app()->language));
$labelAlert = CJavaScript::encode(Yii::t('common', 'Minimum {min} characters.', array('{min}' => param('minLengthSearch', 4))));

$script = <<< JS
var sumoMetroSelectCaptionFormat = $sumoMetroSelectCaptionFormat;
var sumoMetroSelectAlltext = $sumoMetroSelectAlltext;
var sumoMetroSelectPlaceholder = $sumoMetroSelectPlaceholder;
var sumoMetroFilterText = $sumoMetroFilterText;

var sliderRangeFields = $sliderRangeFields;
var cityField = $cityField;
var loc = $loc;
var enableMetro = $enableMetro;
var countFiled = $countField;
if (enableMetro)
    countFiled = countFiled + 1;
var isInner = $isInner;
var heightField = 54;
var advancedIsOpen = 0;
var compact = $isCompact;
var minHeight = isInner ? 80 : 360;
var searchCache = [[]];
var objType = $objType;
var useSearchCache = false;

if (useDatePicker === undefined) {
    var useDatePicker = false;
}

function doSearchAction() {
    if($("#search_term_text").length){
        var term = $(".search-term input#search_term_text").val();
        if (term.length < $minLengthSearch || term == $labelSearchByDesc) {
            $(".search-term input#search_term_text").attr("disabled", "disabled");
        }
    }
    
    $('#search-form input, #search-form select').each(function() {
        var val = $(this).val();
        var disable = (!val || val == null || val == 0);
        if(disable){
            $(this).prop('disabled', 'disabled');
        }
    });

    $("#search-form").submit();
}

var search = {
    init: function() {

        if (sliderRangeFields) {
            $.each(sliderRangeFields, function() {
                search.initSliderRange(this.params);
            });
        }

        if (countFiled <= 6) {
            if (advancedIsOpen) {
                if (isInner) {
                    search.innerSetAdvanced();
                } else {
                    search.indexSetNormal();
                    $('#more-options-link').hide();
                }
            } else if (!isInner) {
                $('#more-options-link').hide();
            }
        } else {
            if (!isInner) {
                $('#more-options-link').show();
            }

            if (advancedIsOpen) {
                if (isInner) {
                    search.innerSetAdvanced();
                } else {
                    search.indexSetAdvanced();
                }
            }
        }

        if (useDatePicker) {
            jQuery.each(useDatePicker, function(id, options) {
                options.beforeShow = function(input, inst) {
                    $(".hasDatepicker.eval_period").each(function(index, elm) {
                        if (index == 0) {
                            from = elm;
                        }
                        if (index == 1) {
                            to = elm;
                        }
                    });

                    if (window.to && (to.id == input.id)) {
                        to = null;
                    }
                    if (window.from && (from.id == input.id)) {
                        from = null;
                    }
                    if (window.to) {
                        maxDate = $(to).val();
                        if (maxDate) {
                            $(inst.input).datepicker("option", "maxDate", maxDate);
                        }
                    }
                    if (window.from) {
                        minDate = $(from).val();
                        if (minDate) {
                            $(inst.input).datepicker("option", "minDate", minDate);
                        }
                    }
                    $("#ui-datepicker-div").css("clip", "auto");
                };
                jQuery('#' + id).datepicker(jQuery.extend({
                    showMonthAfterYear: false
                }, jQuery.datepicker.regional['$datePickerLang'], options));
            });
        }

        if ($("#search_term_text").length) {
            search.initTerm();
        }

    },

    initTerm: function() {
        $(".search-term input#search_term_text").keypress(function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                /* Enter keycode */
                prepareSearch();
                return false;
            }
        });
    },

    initSliderRange: function(sliderParams) {
        $("#slider-range-" + sliderParams.field).slider({
            range: true,
            min: sliderParams.min,
            max: sliderParams.max,
            values: [sliderParams.min_sel, sliderParams.max_sel],
            step: sliderParams.step,
            slide: function(e, ui) {
                $("#" + sliderParams.field + "_min_val").html(Math.floor(ui.values[0]));
                $("#" + sliderParams.field + "_min").val(Math.floor(ui.values[0]));
                $("#" + sliderParams.field + "_max_val").html(Math.ceil(ui.values[1]));
                $("#" + sliderParams.field + "_max").val(Math.ceil(ui.values[1]));
            },
            stop: function(e, ui) {
                changeSearch();
            }
        });
    },

    indexSetNormal: function() {
        $(".forma").animate({
            "height": "380"
        });
        $("div.index-header-form").animate({
            "height": "334"
        });
        $("div.searchform-index").animate({
            "height": "367"
        });
        $("div.index-header-form").removeClass("search-form-is-opened");
        $("#more-options-link").html($labelMoreOptions);
        advancedIsOpen = 0;
    },

    indexSetAdvanced: function() {
        var height = search.getHeight();

        $(".forma").animate({
            "height": height + 60
        });
        $("div.index-header-form").animate({
            "height": height + 60
        });
        $("div.searchform-index").animate({
            "height": height + 60
        });
        $("div.index-header-form").addClass("search-form-is-opened");
        $("#more-options-link").html($labelLessOptions);
        advancedIsOpen = 1;
    },

    innerSetNormal: function() {
        $("div.filtr").addClass("collapsed");
        advancedIsOpen = 0;
    },

    innerSetAdvanced: function() {
        if ($(window).height >= 1024) {
            var height = search.getHeight();
            $("div.filtr").removeClass("collapsed").animate({
                "height": height
            });
            $("#search_form").animate({
                "height": height
            });
        }
        advancedIsOpen = 1;
    },

    getHeight: function() {
        var height = isInner ? parseInt(countFiled / 3) * heightField + 30 : countFiled * heightField;
        if (height < minHeight) {
            return minHeight;
        }

        return height;
    },

    renderForm: function(obj_type_id, ap_type_id) {
        $('#search_form').html(searchCache[obj_type_id][ap_type_id].html);
        sliderRangeFields = searchCache[obj_type_id][ap_type_id].sliderRangeFields;
        cityField = searchCache[obj_type_id][ap_type_id].cityField;
        countFiled = searchCache[obj_type_id][ap_type_id].countFiled + (loc ? 2 : 0) + (enableMetro ? 1 : 0);
        search.init();
        if (!useSearchCache) {
            delete(searchCache[obj_type_id][ap_type_id]);
        }
        changeSearch();

        if (loc) {
            $('#country').select2([]);
            $('#region').select2([]);
        }
        $('#city').select2([]);

        if (enableMetro) {
            $('#metro').SumoSelect({
                captionFormat: sumoMetroSelectCaptionFormat,
                selectAlltext: sumoMetroSelectAlltext,
                csvDispCount: 1,
                placeholder: sumoMetroSelectPlaceholder,
                filter: true,
                filterText: sumoMetroFilterText
            });
        }
    },

    reloadForm: function() {
        var obj_type_id = $('#objType').val();
        var ap_type_id = $('#apType').val();
        if (typeof searchCache[obj_type_id] == 'undefined' || typeof searchCache[obj_type_id][ap_type_id] == 'undefined') {
            $.ajax({
                url: '$urlReloadForm' + '?' + $('#search-form').serialize(),
                dataType : 'json',
                type: 'GET',
                data: {
                    is_inner: $isInner,
                    compact : advancedIsOpen ? 0 : 1
                },
                success: function(data) {
                    if (data.status == 'ok') {
                        searchCache[obj_type_id] = [];
                        searchCache[obj_type_id][ap_type_id] = [];
                        searchCache[obj_type_id][ap_type_id].html = data.html;
                        searchCache[obj_type_id][ap_type_id].sliderRangeFields = data.sliderRangeFields;
                        searchCache[obj_type_id][ap_type_id].cityField = data.cityField;
                        searchCache[obj_type_id][ap_type_id].countFiled = data.countFiled;
                        search.renderForm(obj_type_id, ap_type_id);
                    }
                }
            })
        } else {
            search.renderForm(obj_type_id, ap_type_id);
        }
    }
}

$(function() {
    search.init();

    $('#search-form').on('change', '#objType,#apType', function() {
        search.reloadForm();
    });

    if (isInner) {
        $("#search-form").on('click', '#more-options-link-inner, #more-options-img', function() {
            if (advancedIsOpen) {
                search.innerSetNormal();
            } else {
                search.innerSetAdvanced();
            }
        });
    } else {
        $("#search-form").on('click', '#more-options-link', function() {
            if (advancedIsOpen) {
                search.indexSetNormal();
            } else {
                search.indexSetAdvanced();
            }
        });
    }

    if (isInner && !compact) {
        search.innerSetAdvanced();
    }
});

function prepareSearch() {
    var term = $(".search-term input#search_term_text").val();
    
    if (term != $labelSearchByDesc) {
        if (term.length >= $minLengthSearch) {
            term = term.split(" ");
            term = term.join("+");
            $("#do-term-search").val(1);
            window.location.replace("$urlSearch?term="+term+"&do-term-search=1");
        } else {
            alert($labelAlert);
        }
    }
}

JS;

Yii::app()->clientScript->registerScript('init-js', $script, CClientScript::POS_END);
?>