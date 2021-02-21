(function ($){
  "use strict";
$(window).scroll(function () {
  var window_top = $(window).scrollTop() + 1;
  if (window_top > 50) {
    $('.main_menu').addClass('menu_fixed animated fadeInDown');
  } else {
    $('.main_menu').removeClass('menu_fixed animated fadeInDown');
  }
});

    $('.product_relation_slider').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        dots:false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:6
            }
        }
    })

    $('.slider_slick_thumbnail').slick({
        dots: false,
        arrows: true,
        infinite: false,
        vertical: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="btn-unipro-arrow slick-prev"><i class="fas fa-chevron-up"></i></button>',
        nextArrow: '<button type="button" class="btn-unipro-arrow slick-next"><i class="fas fa-chevron-down"></i></button>',
        responsive:[{
            breakpoint: 768,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                vertical: false,
                arrows: false,
            }
        }],
    });

}(jQuery));

const navExpand = [].slice.call(document.querySelectorAll('.nav-expand'))
const backLink = `<li class="nav-item">
	<a class="nav-link nav-back-link" href="javascript:;">
		Back
	</a>
</li>`

navExpand.forEach(item => {
    item.querySelector('.nav-expand-content').insertAdjacentHTML('afterbegin', backLink)
    item.querySelector('.nav-link').addEventListener('click', () => item.classList.add('active'))
    item.querySelector('.nav-back-link').addEventListener('click', () => item.classList.remove('active'))
})

// ---------------------------------------
// not-so-important stuff starts here

const ham = document.getElementById('ham')
ham.addEventListener('click', function() {
    document.body.classList.toggle('nav-is-toggled')
})

function close_menu_mobile(){
    document.body.classList.toggle('nav-is-toggled')
}

/// Display view 360
$.fn.j360 = function(options) {
    var defaults = {
        clicked: false,
        currImg: 1
    }
    var options = jQuery.extend(defaults, options);
    return this.each(function() {
        var $obj = jQuery(this);
        var $loading = jQuery(this).attr('data-loading');

        var aImages = {};
        $obj.css({
            'margin-left' : 'auto',
            'margin-right' : 'auto',
            'text-align' : 'center',
            'overflow' : 'hidden',
            'background-image' : 'url('+$obj.data('image')+')',
            'background-position':'0px 600px',
        });
        // $obj.prepend('<img src="/images/loader.gif" class="loader" style="margin-top:' + ($obj.height()/2 - 15) + 'px" />');

        $overlay = $obj.clone(true);
        $overlay.html('<img src="'+$loading+'" class="loader" style="margin-top:' + ($obj.height()/2 - 15) + 'px" />');
        $overlay.attr('id', 'view_overlay');
        $overlay.css({
            'position' : 'absolute',
            'z-index': '5',
           // 'top' : $obj.offset().top,
            'top' : 0,
            'left' : $obj.offset().left,
            'background' : '#fff'
        });

        $obj.after($overlay);
        $obj.after('<div id="colors_ctrls"></div>');
        jQuery('#colors_ctrls').css({
            'width' : $obj.width(),
            'position' : 'absolute',
            'z-index': '5',
            'top' : $obj.offset().top + $obj.height - 50,
            'left' : $obj.offset().left
        });
        var imageTotal = 36;

        $overlay.animate({
            'filter' : 'alpha(Opacity=0)',
            'opacity' : 0
        }, 1000);
        $overlay.bind('mousedown touchstart', function(e) {
            if (e.type == "touchstart") {
                options.currPos = window.event.touches[0].pageX;
            } else {
                options.currPos = e.pageX;
            }
            options.clicked = true;
            // return false;

        });
        jQuery(document).bind('mouseup touchend', function() {
            options.clicked = false;
        });
        jQuery(document).bind('mousemove touchmove', function(e) {
            if (options.clicked) {
                var pageX;
                if (e.type == "touchmove") {
                    pageX = window.event.targetTouches[0].pageX;
                } else {
                    pageX = e.pageX;
                }

                var width_step = 4;
                if (Math.abs(options.currPos - pageX) >= width_step) {
                    if (options.currPos - pageX >= width_step) {
                        options.currImg++;
                        if (options.currImg > imageTotal) {
                            options.currImg = 1;
                        }
                    } else {
                        options.currImg--;
                        if (options.currImg < 1) {
                            options.currImg = imageTotal;
                        }
                    }
                    options.currPos = pageX;
                    options.xb = options.currImg*600;
                    options.yb = 600;
                    $obj.css({ 'background-position':options.xb+'px '+options.yb+'px'});
                }
            }

        });


    });
};

function onresizeFunc($obj, $overlay) {
    /*
	$obj.css({
        'margin-top' : $(document).height()/2 - 150
    });*/
    $overlay.css({
        'margin-top' : 0,
        'top' : $obj.offset().top,
        'left' : $obj.offset().left
    });

    jQuery('#colors_ctrls').css({
        'top' : $obj.offset().top + $obj.height - 50,
        'left' : $obj.offset().left
    });
}

function preload(image) {
    if (typeof document.body == "undefined") return;
    try {
        var div = document.createElement("div");
        var s = div.style;
        s.position = "absolute";
        s.top = s.left = 0;
        s.visibility = "hidden";
        document.body.appendChild(div);
        div.innerHTML = "<img class=\"preload_img\" src=\"" + image + "\" />";
    } catch(e) {
        // Error. Do nothing.
    }
}

function minTwoDigits(n) {
    return (n < 10 ? '0' : '') + n;
}

///get get parameters javascript
function get_parameters(key){
    var resulf = '';
    location.search.substr(1).split("&").forEach(function(item) {
       let data =  item.split("=");
        if(data[0] == key){
            resulf = data[1];
            return resulf;
        }
    });
    return resulf;
}




