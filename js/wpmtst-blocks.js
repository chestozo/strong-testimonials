/**
 * Strong Testimonials - Blocks
 */

jQuery(document).ready(function($) {
	'use strict';

	// Function to get the Max value in Array
	Array.max = function( array ){
		return Math.max.apply( Math, array );
	};

	// Convert "A String" to "a_string"
	function convertLabel(label) {
		return label.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
	}

	// UI
	$("div.radio:has(:checked)").closest('td').addClass("selected");
	
	$("#block_category_list").change(function(){
		// checked group
		var $checked = $(this).find("input:checkbox:checked");
		// unchecked group
		var $unchecked = $(this).find("input:checkbox:not(:checked)");
		// if any checked
		if($checked.length > 0) {
			// if all checked
			if($unchecked.length == 0) {
				// check the "All" box and disable it
				$("#block_category_all").prop("checked", true).attr("disabled","disabled");
			} 
			// some checked
			else {
				// uncheck the "all" box and enable it
				$("#block_category_all").prop("checked", false).removeAttr("disabled");
			}
		}
		// none checked
		else {
			// check the "All" box and enable it
			$("#block_category_all").prop("checked", true).attr("disabled","disabled");
			// check all the other boxes
			$("#block_category_list input:checkbox").prop("checked", true);		
		}
	});
	
	$("#block_category_all").change(function(){
		// if "All" checked
		if($(this).is(":checked")) {
			// check all the other boxes
			$("#block_category_list input:checkbox").prop("checked", true);
			// disable the "All" box
			$(this).attr("disabled","disabled");
		}
	});
	
	// init
	if($("#block_category_all").prop("checked")) {
		$("#block_category_list input:checkbox").prop("checked", true);
		$("#block_category_all").attr("disabled","disabled");
	}

	
	
	// Toggle screenshot
	$("#toggle-screen-options").add("#screenshot-screen-options").click(function(e) {
		$("#screenshot-screen-options").slideToggle();
		e.preventDefault();
	}).blur();

	// Restore defaults
	$("#restore-defaults").click(function(){
		return confirm("Restore the default settings?");
	});
	
	
	/*
	 * -----------------
	 * Dependent options
	 * -----------------
	 */

	/*
	 * Plugin: Show/Hide parts based on current Mode
	 */
	$.fn.updateScreen = function(mode, speed) {
		speed = speed || 400;
		if(!mode)
			return;
		
		$(".then_" + mode).fadeIn(speed);
		$(".then_not_" + mode).fadeOut(speed);
		
		// update default template
		// ~1.16
		/*
		if($("input[name='last_block_template']").val()=='') {
			var data = {
				'action' : 'wpmtst_get_default_template',
				'mode'   : mode,
			};
			$.get( ajaxurl, data, function( response ) {
				$("#block-template option[value='"+response+"']").prop("selected",true);
			});
		}
		*/
	}
	
	/*
	 * Plugin: Toggle dependent options for checkboxes.
	 * 
	 * Show/hide other option groups when checkbox is "on".
	 */
	$.fn.toggleOption = function(el, speed) {
		speed = speed || 400;
		var option = $(el).attr("id").split("-").pop();
		var checked = $(el).prop("checked");
		var deps = ".then_" + option;
		if(checked) {
			$(deps).fadeIn(speed);
		}
		else {
			$(deps).fadeOut(speed);
		}
	}

	/*
	 * Plugin: Toggle dependent options for selects.
	 *
	 * Show/hide other option groups when a *specific* option is selected.
	 */
	$.fn.selectOption = function(el, speed) {
		speed = speed || 400;
		var currentValue = $(el).val();
		var tripValue = $(el).find(".trip").val();
		var option = $(el).attr("id").split("-").pop();
		var deps = ".then_" + option;
		if(currentValue == tripValue) {
			$(deps).fadeIn(speed);
		}
		else {
			$(deps).fadeOut(speed);
		}
	}

	/*
	 * Plugin: Toggle dependent options for selects.
	 *
	 * Show/hide other option groups when any *non-empty (initial)* option is selected.
	 */
	$.fn.selectAnyOption = function(el, speed) {
		speed = speed || 400;
		var currentValue = $(el).val();
		var option = $(el).attr("id").split("-").pop();
		var deps = ".then_" + option + ".then_" + currentMode;
		var indeps = ".then_not_" + option + ".then_" + currentMode;
		if(currentValue) {
			$(deps).fadeIn(speed);
			$(indeps).fadeOut(speed);
		}
		else {
			$(deps).fadeOut(speed);
			$(indeps).fadeIn(speed);
		}
	}

	/*
	 * Initial state
	 */
	var currentMode = $("#block-mode").val();
	$.fn.updateScreen(currentMode);

	/*
	 * Mode listener
	 */
	$("#block-mode").change(function() {
		currentMode = $(this).val();
		$.fn.updateScreen(currentMode);
	});
	
	/*
	 * Template listener
	 *
	 * Pre-select the default template for the current mode
	 * unless the user has already made a selection.
	 */
	// ~1.16
	/*
	$("#block-template").change(function() {
		$("input[name='last_block_template']").val($(this).val());
	});
	*/
	
	/*
	 * Initial state & Change listeners
	 */
	$(".if.toggle").each(function(index,el) {
		$.fn.toggleOption(this);
		$(this).change(function() {
			$.fn.toggleOption(this);
		});
	});
	
	$(".if.select").each(function(index,el) {
		$.fn.selectOption(this);
		$(this).change(function() {
			$.fn.selectOption(this);
		});
	});
	
	$(".if.selectany").each(function(index,el) {
		$.fn.selectAnyOption(this);
		$(this).change(function() {
			$.fn.selectAnyOption(this);
		});
	});

	

	/*
	 * -------------
	 * Client fields
	 * -------------
	 */
	
	
	/*
	 * Make client fields sortable
	 */
	
	// First, set width on header cells to prevent collapse 
	// when dragging a row without "URL Field".
	$("table.fields th").each(function(index){
		$(this).width($(this).outerWidth());
	});
		
	$("#custom-field-list2 tbody").sortable({
		placeholder: "sortable-placeholder",
		// forcePlaceholderSize: true,
		handle: ".handle",
		cursor: "move",
		helper: function(e, tr) {
			var $originals = tr.children();
			var $helper = tr.clone();
			$helper.children().each(function(index) {
				// Set helper cell sizes to match the original sizes
				$(this).width($originals.eq(index).width());
			});
			return $helper;
		},
		start: function(e, ui){
			ui.placeholder.height(ui.item.height());
		}
	}).disableSelection();
	
	/*
	 * Add client field
	 */
	$("#add-field").click(function(e) {
		var keys = $("#custom-field-list2 tbody tr").map(function() {
			var key_id = $(this).attr("id");
			return key_id.substr( key_id.lastIndexOf("-")+1 );
		}).get();
		var nextKey = Array.max(keys)+1;
		var data = {
			'action' : 'wpmtst_block_add_field',
			'key'    : nextKey,
		};
		$.get( ajaxurl, data, function( response ) {
			// append to list
			$("#custom-field-list2").append(response);
		});
	});

	/* 
	 * Field type change listener
	 */
	$("#custom-field-list2").on("change", ".field-type select", function() {
		var $el = $(this);
		var fieldType = $el.val();
		var key_id = $el.closest("tr").attr("id");
		var key = key_id.substr( key_id.lastIndexOf("-")+1 );
		
		// if changing to [link], add link fields
		if( fieldType == 'link' ) {
			var data = {
				'action' : 'wpmtst_block_add_field_link',
				'key'    : key,
			};
			$.get( ajaxurl, data, function( response ) {
				// insert into placeholder div
				$el.closest(".field2").find(".field-link").append(response);
			});
		}
		// if changing to [text], remove link fields
		else {
			$el.closest(".field2").find(".field-link").empty();
		}
	});
	
	/*
	 * Delete a client field
	 */
	$("#custom-field-list2").on("click", ".delete-field", function(){
		var thisField = $(this).closest("tr");
		var thisLabel = thisField.find(".field-name option:selected").html();
		var yesno = confirm("Remove this field?");
		if( yesno ) {
			thisField.fadeOut(function(){$(this).remove()});
		}
	});
	
});
