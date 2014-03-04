//FB Init
function fbinit(appid) {
    window.fbAsyncInit = function() {
        FB.init({
            appId: appid,
            status: true, // check login status
            cookie: true, // enable cookies to allow the server to access the session
            oauth: true, // enable OAuth 2.0
            xfbml: true // parse XFBML
        });

        FB.Canvas.setAutoGrow();

        FB.Event.subscribe('edge.create', function(response) {
            record('like_record');
            is_fan();
        });
    };

    // Load the SDK Asynchronously
    (function() {
        var e = document.createElement('script');
        e.async = true;
        e.src = document.location.protocol + '//connect.facebook.net/zh_TW/all.js';
        document.getElementById('fb-root').appendChild(e);
    }());
}

var fbid = '';
//FB Login
function fb_login(SCOPE) {
    FB.login(function(response) {
        if (response.authResponse) {
            fbid = response.authResponse.userID;
            FB.getLoginStatus(is_fan);
        } else {
            fb_login(SCOPE);
        }
    }, {
        scope: SCOPE
    });
}

Array.prototype.deleteOf = function(a) {
    for (var i = this.length; i-- && this[i] !== a;);
    if (i >= 0) this.splice(i, 1);
};

function _show(id) {
    if ($(id).css('display') != 'block') {
        $(id).bPopup({
            modalClose: false
        });
    } else {
        $(id).bPopup().close();
    }
}

function ValidEmail(emailtoCheck) {
    var regExp = /^[^@^\s]+@[^\.@^\s]+(\.[^\.@^\s]+)+$/;
    //var regExp2 = /^[^＠^\s]+＠[^\.＠^\s]+(\.[^\.＠^\s]+)+$/;
    if (!emailtoCheck.match(regExp)) {
        return false;
    }
    return true;
}

function ValidTel(teltoCheck) {
    var regExp = /^[0-9]*$/;
    if (!teltoCheck.match(regExp)) {
        return false;
    }
    return true;
}

function checkform() {
    var f = 0;
    jQuery.each($('#data_form .required'), function(index, val) {
        if ($(this).val() == '' && f == 0) {
            bootbox.alert('請輸入' + $(this).attr('alt'));
            f = 1;
        }

        if ($(this).attr('id') == 'email' && !ValidEmail($(this).val()) && f == 0) {
            bootbox.alert('請輸入正確EMAIL格式');
            f = 1;
        }

        if ($(this).attr('id') == 'tel' && !ValidTel($(this).val()) && f == 0) {
            bootbox.alert('請輸入純數字的手機號碼');
            f = 1;
        }
    });

    if (f == 0 && !$('#agree').prop('checked')) {
        bootbox.alert('您尚未閱讀完活動說明，並同意參加活動！');
        return;
    }

    if (f == 0) {
        return true;
    }
    return false;
}

function preload_images(images) {

    load_all(images);

    function load_all(images) {
        i = 0;
        $.imgpreload(images, {
            each: function() {
                ++i;
            },
            all: function() {
                $('.preloading_block').fadeOut();
            }
        });
    }
}

// toastr

function show_toastr(position, shortCut, msg, title) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": position, //toast-top-right,toast-bottom-right,toast-bottom-left,toast-top-left,toast-top-full-width,toast-bottom-full-width
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    shortCutFunction = shortCut; // success,info,warning,error
    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    $toastlast = $toast;
}