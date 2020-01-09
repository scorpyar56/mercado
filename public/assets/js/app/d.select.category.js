/*
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

/* Prevent errors, If these variables are missing. */
if (typeof category === 'undefined') {
    var category = 0;
}
if (typeof subCategory === 'undefined') {
    var subCategory = 0;
}
if (typeof packageIsEnabled === 'undefined') {
    var packageIsEnabled = false;
}
var select2Language = languageCode;
if (typeof langLayout !== 'undefined' && typeof langLayout.select2 !== 'undefined') {
    select2Language = langLayout.select2;
}

$(document).ready(function () {

    /* CSRF Protection */
    var token = $('meta[name="csrf-token"]').attr('content');
    if (token) {
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': token},
            async: true,
            cache: false
        });
    }

    /* On load */
    if (subCategory==0) {
        $('#subCatBloc2').hide();
	} else {
        $('#subCatBloc2').show();
    }
    //var catObj = getSubCategories(siteUrl, languageCode, category, subCategory);
    getSubCategoriesNew(siteUrl, languageCode, category, subCategory);


    applyCategoryTypeActions('parentType', categoryType, packageIsEnabled);
    getCustomFieldsByCategory(siteUrl, languageCode, category, subCategory);

    /* On category selected */
    $('#parentId').bind('click, change', function () {
        category = $(this).val();

		if (category == 0) {
            $('#subCatBloc2').hide();
            $('#categoryId').val(null);
            $('#customFields').html("");
		} else {
            $('#subCatBloc2').show();
		}

        var selectedCat = $(this).find('option:selected');
        var selectedCatType = selectedCat.data('type');

        /* Get sub-categories */
        //catObj = getSubCategories(siteUrl, languageCode, category, 0);

        /* Update 'parent_type' field */
        $('input[name=parent_type]').val(selectedCatType);

        /* Check resume file field */
        applyCategoryTypeActions('parentType', selectedCatType, packageIsEnabled);

        /* Get the category's custom fields */
        // getCustomFieldsByCategory(siteUrl, languageCode, category, 0);

        getSubCategoriesNew(siteUrl, languageCode, category, subCategory);

        // $('#tree').tree({
        // 	data: data,
        // 	selectable: true
        // });
    });

    /* On subcategory selected */
    // $('#categoryId').bind('click, change', function() {
    // 	category = $('#parentId').val();
    // 	subCategory = $(this).val();
    //
    // 	var selectedSubCat = $(this).find('option:selected');
    // 	var selectedSubCatType = selectedSubCat.data('type');
    //
    // 	/* Check resume file field */
    // 	if (selectedSubCatType != '') {
    // 		applyCategoryTypeActions('categoryType', selectedSubCatType, packageIsEnabled);
    // 	}
    //
    // 	/* Get the category and subcategory's custom fields (merged) */
    // 	// if (category != 0 && subCategory != 0) {
    // 	// 	getCustomFieldsByCategory(siteUrl, languageCode, category, subCategory);
    // 	// }
    // });

    $('#tree').on(
        'tree.click',
        function (event) {
            category = $('#parentId').val();
            event.preventDefault();
            var node = event.node;
            if (node.children.length === 0) {
                $('#tree').tree('selectNode', node);
                $('#categoryId').val(node.id);
                getCustomFieldsByCategory(siteUrl, languageCode, category, node.id);
            } else {
                $('#tree').tree('toggle', node);
            }
        }
    );


});

function getSubCategories(siteUrl, languageCode, catId, selectedSubCatId) {
    /* Check Bugs */
    if (typeof languageCode === 'undefined' || typeof catId === 'undefined') {
        return false;
    }

    /* Don't make ajax request if any category has selected. */
    if (catId == 0 || catId == '') {
        /* Remove all entries from subcategory field. */
        $('#categoryId').empty();
        $('#categoryId').append('<option value="0" data-type="">' + lang.select.subCategory + '</option>');
        $('#categoryId').val('0');
        $('#categoryId').trigger('change');

        $('#categoryType').val('');
        return false;
    }

    /* Default number of sub-categories */
    var countSubCats = 0;

    /* Make ajax call */
    $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/category/sub-categories',
        data: {
            '_token': $('input[name=_token]').val(),
            'catId': catId,
            'selectedSubCatId': selectedSubCatId,
            'languageCode': languageCode
        }
    }).done(function (obj) {
        /* init. */
        $('#categoryId').empty();
        $('#categoryId').append('<option value="0" data-type="">' + lang.select.subCategory + '</option>');
        $('#categoryId').val('0');
        $('#categoryId').trigger('change');

        /* error */
        if (typeof obj.error !== "undefined") {
            $('#categoryId').find('option').remove().end().append('<option value="0" data-type=""> ' + obj.error.message + ' </option>');
            $('#categoryId').closest('.form-group').addClass('has-error');
            return false;
        } else {
            /* $('#categoryId').closest('.form-group').removeClass('has-error'); */
        }

        if (typeof obj.subCats === "undefined" || typeof obj.countSubCats === "undefined") {
            return false;
        }

        /* Default sub-category 'type' (Set to the parent category value) */
        var subCategoryType = $('#parentType').val();

        /* Bind data into Select list */
        if (obj.countSubCats == 1) {
            $('#subCatBloc').hide();

            $('#categoryId').empty();
            $('#categoryId').append('<option value="' + obj.subCats[0].tid + '" data-type="' + obj.subCats[0].type + '">' + obj.subCats[0].name + '</option>');
            $('#categoryId').val(obj.subCats[0].tid);
            $('#categoryId').trigger('change');

            /* Get the selected sub-category's 'type' field value */
            subCategoryType = obj.subCats[0].type;
        } else {
            $('#subCatBloc').show();

            $.each(obj.subCats, function (key, subCat) {
                if (selectedSubCatId == subCat.tid) {
                    $('#categoryId').append('<option value="' + subCat.tid + '" data-type="' + subCat.type + '" selected="selected">' + subCat.name + '</option>');

                    /* Get the selected sub-category's 'type' field value */
                    subCategoryType = subCat.type;
                } else {
                    $('#categoryId').append('<option value="' + subCat.tid + '" data-type="' + subCat.type + '">' + subCat.name + '</option>');
                }
            });
        }

        /* Apply category type actions (for Sub-categories) */
        applyCategoryTypeActions('categoryType', subCategoryType, packageIsEnabled);
        $('#categoryType').val(subCategoryType);

        /* Get number of sub-categories */
        countSubCats = obj.countSubCats;
    });

    /* Get result */
    return {
        'catId': catId,
        'countSubCats': countSubCats
    };
}

function getSubCategoriesNew(siteUrl, languageCode, parent, subCat) {
    $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/category/sub-categories-new',
        data: {
            '_token': $('input[name=_token]').val(),
            'catId': parent,
            'selectedSubCatId': subCat,
            'languageCode': languageCode
        },
        async: false
    }).done(function (obj) {
        var tree2 = $('#subCatTree2');
        if (tree2.hasClass("select2-hidden-accessible")) {
            tree2.html('');
            tree2.off('select2:select');
            tree2.off('select2:unselect');
            tree2.val(null).trigger('change');
        }
        var data = obj.subCats.map(function (v) {
            return {id:v.id, text:v.text, selected:v.state.selected}
        });
        tree2.select2({
            data: data,
            placeholder: 'Select subcategory',
            width: '100%',
            multiple: false,
            language: select2Language,
            dropdownAutoWidth: 'true',
            minimumResultsForSearch: -1
        });
        if (subCat!==0) {
            tree2.val(subCategory).trigger('change');
            $('#categoryId').val(subCategory);
        } else {
            tree2.val(null).trigger('change');
        }
        tree2.on('select2:select', function (e) {
            $('#categoryId').val(e.params.data.id);
            category = $('#parentId').val();
            getCustomFieldsByCategory(siteUrl, languageCode, category, e.params.data.id);
        });
        tree2.on('select2:unselect', function (e) {
            $('#categoryId').val(null);
            $('#customFields').html("");
        });

    });
}

function getTree(siteUrl, languageCode, parent) {
    var data = $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/category/sub-categories',
        data: {
            '_token': $('input[name=_token]').val(),
            'catId': parent,
            'selectedSubCatId': 0,
            'languageCode': languageCode
        },
        async: false
    }).responseJSON;

    var tempdata = [];
    data.subCats.forEach(function (value) {
        if (value.active === "1" && value.id !== parent) {
            var element = {
                id: value.id,
                name: value.name
            };
            var children = getTree(siteUrl, languageCode, value.id);
            if (children.length > 0) {
                element.children = children;
            }
            tempdata.push(element);
        }
    });
    return tempdata;
}

/**
 * Get the Custom Fields by Category
 *
 * @param siteUrl
 * @param languageCode
 * @param catId
 * @param subCatId
 * @returns {*}
 */
function getCustomFieldsByCategory(siteUrl, languageCode, catId, subCatId) {
    /* Check undefined variables */
    if (typeof languageCode === 'undefined' || typeof catId === 'undefined') {
        return false;
    }

    /* Don't make ajax request if any category has selected. */
    if (catId == 0 || catId == '') {
        return false;
    }

    /* Make ajax call */
    $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/category/custom-fields',
        data: {
            '_token': $('input[name=_token]').val(),
            'languageCode': languageCode,
            'catId': catId,
            'subCatId': subCatId,
            'errors': errors,
            'oldInput': oldInput,
            'postId': (typeof postId !== 'undefined') ? postId : ''
        }
    }).done(function (obj) {
        /* Load Custom Fields */
        $('#customFields').html(obj.customFields);

        /* Apply Fields Components */
        initSelect2($('#customFields'), languageCode);
        $('#customFields').find('.selecter, .sselecter').select2({
            width: '100%',
            minimumResultsForSearch: -1
        });
    });

    return catId;
}

/**
 * Apply Category Type actions (for Job offer/search & Services for example)
 *
 * @param categoryTypeFieldId
 * @param categoryTypeValue
 * @param packageIsEnabled
 */
function applyCategoryTypeActions(categoryTypeFieldId, categoryTypeValue, packageIsEnabled) {
    $('#' + categoryTypeFieldId).val(categoryTypeValue);
    $('#' + categoryTypeFieldId).val(categoryTypeValue);

    /* Debug */
    /* console.log(categoryTypeFieldId + ': ' + categoryTypeValue); */

    if (categoryTypeValue == 'job-offer') {
        $('#postTypeBloc label[for="post_type_id-1"]').show();
        $('#priceBloc label[for="price"]').html(lang.salary);
        $('#priceBloc').show();
    } else if (categoryTypeValue == 'job-search') {
        $('#postTypeBloc label[for="post_type_id-2"]').hide();

        $('#postTypeBloc input[value="1"]').attr('checked', 'checked');
        $('#priceBloc label[for="price"]').html(lang.salary);
        $('#priceBloc').show();
    } else if (categoryTypeValue == 'not-salable') {
        $('#priceBloc').hide();

        $('#postTypeBloc label[for="post_type_id-2"]').show();
    } else {
        $('#postTypeBloc label[for="post_type_id-2"]').show();
        $('#priceBloc label[for="price"]').html(lang.price);
        $('#priceBloc').show();
    }

    $('#nextStepBtn').html(lang.nextStepBtnLabel.next);
}

function initSelect2(selectElementObj, languageCode) {
    selectElementObj.find('.selecter').select2({
        language: select2Language,
        dropdownAutoWidth: 'true',
        minimumResultsForSearch: Infinity
    });

    selectElementObj.find('.sselecter').select2({
        language: select2Language,
        dropdownAutoWidth: 'true'
    });
}
