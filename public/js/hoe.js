$(document).ready(function() {
    HoeDatapp = {
        appinit: function() {
            HoeDatapp.HandleSidebartoggle();
            HoeDatapp.Handlelpanel();
            HoeDatapp.Handlelpanelmenu();
            HoeDatapp.Handlethemeoption();
            HoeDatapp.Handlesidebareffect();
            HoeDatapp.Handlesidebarposition();
            HoeDatapp.Handlecontentheight();
            HoeDatapp.Handlethemecolor();
			HoeDatapp.Handlenavigationtype();
			HoeDatapp.Handlesidebarside();
			HoeDatapp.Handleactivestatemenu();
			HoeDatapp.Handlethemelayout();
			HoeDatapp.Handlethemebackground();
			 

        },
		Handlethemebackground: function() {
            function setthemebgcolor() {
                $('#theme-color > a.theme-bg').on("click", function() {
                    $('body').attr("theme-bg", $(this).attr("hoe-themebg-type"));
                });
            };
			setthemebgcolor(); 
        },
		Handlethemelayout: function() {
			 $('#theme-layout').on("change", function() {
                if ($(this).val() == 'box-layout') {
                  $('body').attr("theme-layout", "box-layout");
                }else {
				 $('body').attr("theme-layout", "wide-layout");
				}
            });
        },
		Handleactivestatemenu: function() {
			 $(".panel-list li:not('.hoe-has-menu') > a").on("click", function() {
				if ($('body').attr("hoe-navigation-type") == "vertical" || $('body').attr("hoe-navigation-type") == "vertical-compact")   {
					if ($(this).closest('li.hoe-has-menu').length === 1){
						$(this).closest('.panel-list').find('li.active').removeClass('active');
						$(this).parent().addClass('active');
						$(this).parent().closest('.hoe-has-menu').addClass('active');
						$(this).parent('li').closest('li').closest('.hoe-has-menu').addClass('active');
					} else {
						$(this).closest('.panel-list').find('li.active').removeClass('active');
						$(this).closest('.panel-list').find('li.opened').removeClass('opened');
						$(this).closest('.panel-list').find('ul:visible').slideUp('fast');
						$(this).parent().addClass('active');
						 
					}
				}
			});
        }, 
		Handlesidebarside: function() {
			 $('#navigation-side').on("change", function() {
                if ($(this).val() == 'rightside') {
                  $('body').attr("hoe-nav-placement", "right"); 
				  $('body').attr("hoe-navigation-type", "vertical");
				  $('#hoeapp-wrapper').removeClass("compact-hmenu");
                }else {
				 $('body').attr("hoe-nav-placement", "left"); 
				 $('body').attr("hoe-navigation-type", "vertical");
				  $('#hoeapp-wrapper').removeClass("compact-hmenu");
				}
            });
        },
		Handlenavigationtype: function() {
			 $('#navigation-type').on("change", function() {
                if ($(this).val() == 'horizontal') {
                    $('body').attr("hoe-navigation-type", "horizontal");
					$('#hoeapp-wrapper').removeClass("compact-hmenu");
					$('#hoe-header, #hoeapp-container').removeClass("hoe-minimized-lpanel");
					$('body').attr("hoe-nav-placement", "left");
					$('#hoe-header').attr("hoe-color-type","logo-bg7");
					
                }else if  ($(this).val() == 'horizontal-compact'){
                    $('body').attr("hoe-navigation-type", "horizontal");
					$('#hoeapp-wrapper').addClass("compact-hmenu");
					$('#hoe-header, #hoeapp-container').removeClass("hoe-minimized-lpanel");
					$('body').attr("hoe-nav-placement", "left");
					$('#hoe-header').attr("hoe-color-type","logo-bg7");
                }else if  ($(this).val() == 'vertical-compact'){
                    $('body').attr("hoe-navigation-type", "vertical-compact");
					$('#hoeapp-wrapper').removeClass("compact-hmenu");
					$('#hoe-header, #hoeapp-container').addClass("hoe-minimized-lpanel");
					$('body').attr("hoe-nav-placement", "left"); 
                }else {
					$('body').attr("hoe-navigation-type", "vertical");
					$('#hoeapp-wrapper').removeClass("compact-hmenu");
					$('#hoe-header, #hoeapp-container').removeClass("hoe-minimized-lpanel");
					$('body').attr("hoe-nav-placement", "left"); 
				}
            });
        },
		
        Handlethemecolor: function() {

            function setheadercolor() {
                $('#theme-color > a.header-bg').on("click", function() {
                    $('#hoe-header > .hoe-right-header').attr("hoe-color-type", $(this).attr("hoe-color-type"));
                });
            };

            function setlpanelcolor() {
                $('#theme-color > a.lpanel-bg').on("click", function() {
                    $('#hoeapp-container').attr("hoe-color-type", $(this).attr("hoe-color-type"));
                });
            };

            function setllogocolor() {
                $('#theme-color > a.logo-bg').on("click", function() {
                    $('#hoe-header').attr("hoe-color-type", $(this).attr("hoe-color-type"));
                });
            };
            setheadercolor();
            setlpanelcolor();
            setllogocolor();
        },
        Handlecontentheight: function() {

            function setHeight() {
                var WH = $(window).height();
                var HH = $("#hoe-header").innerHeight();
                var FH = $("#footer").innerHeight();
                var contentH = WH - HH - FH - 2;
				var lpanelH = WH - HH - 2;
                $("#main-content ").css('min-height', contentH)
				 $(".inner-left-panel ").css('height', lpanelH)

            };
            setHeight();

            $(window).resize(function() {
                setHeight();
            });
        },
        Handlesidebarposition: function() {

            $('#sidebar-position').on("change", function() {
                if ($(this).val() == 'fixed') {
                    $('#hoe-left-panel,.hoe-left-header').attr("hoe-position-type", "fixed");
                } else {
                    $('#hoe-left-panel,.hoe-left-header').attr("hoe-position-type", "absolute");
                }
            });
        },
        Handlesidebareffect: function() {
            $('#leftpanel-effect').on("change", function() {
                if ($(this).val() == 'overlay') {
                    $('#hoe-header, #hoeapp-container').attr("hoe-lpanel-effect", "overlay");
                } else if ($(this).val() == 'push') {
                    $('#hoe-header, #hoeapp-container').attr("hoe-lpanel-effect", "push");
                } else {
                    $('#hoe-header, #hoeapp-container').attr("hoe-lpanel-effect", "shrink");
                }
            });

        },

        Handlethemeoption: function() {
            $('.selector-toggle > a').on("click", function() {
                $('#styleSelector').toggleClass('open')
            });

        },
        Handlelpanelmenu: function() {
            $('.hoe-has-menu > a').on("click", function() {
                var compactMenu = $(this).closest('.hoe-minimized-lpanel').length;
                if (compactMenu === 0) {
                    $(this).parent('.hoe-has-menu').parent('ul').find('ul:visible').slideUp('fast');
                    $(this).parent('.hoe-has-menu').parent('ul').find('.opened').removeClass('opened');
                    var submenu = $(this).parent('.hoe-has-menu').find('>.hoe-sub-menu');
                    if (submenu.is(':hidden')) {
                        submenu.slideDown('fast');
                        $(this).parent('.hoe-has-menu').addClass('opened');
                    } else {
                        $(this).parent('.hoe-has-menu').parent('ul').find('ul:visible').slideUp('fast');
                        $(this).parent('.hoe-has-menu').removeClass('opened');
                    }
                }
            });

        },
        HandleSidebartoggle: function() {
            $('.hoe-sidebar-toggle a').on("click", function() {
                if ($('#hoeapp-wrapper').attr("hoe-device-type") !== "phone") {
                    $('#hoeapp-container').toggleClass('hoe-minimized-lpanel');
                    $('#hoe-header').toggleClass('hoe-minimized-lpanel');
					if ($('body').attr("hoe-navigation-type") !== "vertical-compact") {
						$('body').attr("hoe-navigation-type", "vertical-compact"); 
					}else{
						$('body').attr("hoe-navigation-type", "vertical"); 
					}
                } else {
                    if (!$('#hoeapp-wrapper').hasClass('hoe-hide-lpanel')) {
                        $('#hoeapp-wrapper').addClass('hoe-hide-lpanel');
                    } else {
                        $('#hoeapp-wrapper').removeClass('hoe-hide-lpanel');
                    }
                }
            });

        },
        Handlelpanel: function() {

            function Responsivelpanel() {
                
				var totalwidth = $(window)[0].innerWidth;
                if (totalwidth >= 768 && totalwidth <= 1024) {
                    $('#hoeapp-wrapper').attr("hoe-device-type", "tablet");
                    $('#hoe-header, #hoeapp-container').addClass('hoe-minimized-lpanel');
					$('li.theme-option select').attr('disabled', false);
                } else if (totalwidth < 768) {
                    $('#hoeapp-wrapper').attr("hoe-device-type", "phone");
                    $('#hoe-header, #hoeapp-container').removeClass('hoe-minimized-lpanel');
					$('li.theme-option select').attr('disabled', 'disabled');
                } else {
					if ($('body').attr("hoe-navigation-type") !== "vertical-compact") {
						$('#hoeapp-wrapper').attr("hoe-device-type", "desktop");
						$('#hoe-header, #hoeapp-container').removeClass('hoe-minimized-lpanel');
						$('li.theme-option select').attr('disabled', false);
					}else {
						$('#hoeapp-wrapper').attr("hoe-device-type", "desktop");
						$('#hoe-header, #hoeapp-container').addClass('hoe-minimized-lpanel');
						$('li.theme-option select').attr('disabled', false);	
						
					}
                }
            }
            Responsivelpanel();
            $(window).resize(Responsivelpanel);

        },

    };
    HoeDatapp.appinit();
});