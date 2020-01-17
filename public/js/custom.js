
$(document).ready(function ()
{

    sidebarDirection = {left: '-251px'};
    sidebarDirectionClose = {left: '0'};

	// NAVBAR MAIN
	$(".navbar-toggle").click(function () {
		$('.mobile-filter-sidebar.xxx').addClass('is-display')
        .prepend("<button style='display:flex; align-items:center; justify-content:center;' class='closeFilter btn go-button'><i style='font-size:14px; ' class='unir-close_l'></i></button>")
            .animate(sidebarDirectionClose, 250, "linear", function () {
			});
		$('.menu-overly-mask').addClass('is-visible');
    });

    $(".menu-overly-mask").click(function () {
        $(".mobile-filter-sidebar.xxx").removeClass('is-display').animate(sidebarDirection, 250, "linear", function () {
		});
		$('.menu-overly-mask').removeClass('is-visible');
    });

    $(document).on('click', '.closeFilter', function () {
		$(".closeFilter").remove();
		$(".mobile-filter-sidebar.xxx").animate(sidebarDirection, 250, "linear", function () {
		});
		$('.menu-overly-mask').removeClass('is-visible');
	});

	function navbarShadow() {
		if ($(window).scrollTop() == 0) {
		  $('#navshadow').css('-webkit-box-shadow', 'none').css('-moz-box-shadow', 'none').css('box-shadow', 'none');
		} else {
		  $('#navshadow').css('-webkit-box-shadow', '0px -5px 10px 0px rgba(136,136,136,1)').css('-moz-box-shadow', '0px -5px 10px 0px rgba(136,136,136,1)').css('box-shadow', '0px -5px 10px 0px rgba(136,136,136,1)');
		}
	}
	navbarShadow();

	$(window).scroll(function() {
		navbarShadow();
	});


	var navbarSite = $('#navshadow');
	var helpBlock = $('.help-block');


	var stickyScroller = function () {
		var intialscroll = 0;
		$(window).scroll(function (event) {
			var windowScroll = $(this).scrollTop();
			if (windowScroll > intialscroll) {
				/* downward-scrolling */
				navbarSite.addClass('stuck');
				navbarSite.removeClass('stuck');
			} else {
				/* upward-scrolling */
				navbarSite.removeClass('stuck');
				helpBlock.addClass('help-margin');
			}
			if (windowScroll < 450) {
				/* downward-scrolling */
				navbarSite.removeClass('stuck');
				helpBlock.addClass('help-margin');
			}
			intialscroll = windowScroll;
		});
	};
	stickyScroller();

	$(function () {
		var location = window.location.href;
		var cur_url = '/' + location.split('/').pop();

		$('div.help-block h4').each(function () {
			var link = $(this).find('a').attr('href');

			if (location == link) {
				$(this).find('a').addClass('current');
			}
		});
	});

	$('.owner-phone > div.input-group > input#phone').keypress(function(e) {
		e = e['originalEvent']['key'];
		if (/\+/.test($(this).val())) {
			if (!Number(e) || $(this).val().length > 13) {
				return false;
			}
		} else {
			if (e !== '+' && !Number(e) || $(this).val().length > 13) {
				return false;
			}
		}
	})

	$('#showNegotiable').click(function () {
		var fieldQueryString = $('#negotiableQueryString').val();
		let val = $(this).prop('checked') ? 1 : 0;

			if (fieldQueryString != '') {
				fieldQueryString = fieldQueryString + '&';
			}
			fieldQueryString = fieldQueryString + 'showNegotiable=' + val;
		var searchUrl = baseUrl + '?' + fieldQueryString;
		redirect(searchUrl);
	});

	let sort_by_text = $('li a.link-bold').text();
	$('.dropdown-toggle span#mobileCurrent').text(sort_by_text);

	/*=======================================================================================
	 cat-collapse Homepage Category Responsive view
	 =======================================================================================*/

	 var catCollapse = $('.cat-collapse');

	 $(window).bind('resize load', function () {

		 if ($(this).width() < 767) {
			 catCollapse.collapse('hide');
			 catCollapse.on('show.bs.collapse', function () {
				 $(this).prev('.cat-title').find('.icon-down-open-big').addClass("active-panel");
			 });

			 catCollapse.on('hide.bs.collapse', function () {
				 $(this).prev('.cat-title').find('.icon-down-open-big').removeClass("active-panel");
			 });

		 } else {
			 $('#bd-docs-nav').collapse('show');
			 catCollapse.collapse('show');
		 }

	 });
});
