! function() {
    var r, n, i, e = {},
        c = (window.onload, 400),
        l = "AcceptUIContainer",
        s = "AcceptUIBackground";

    function t() {
        var t = document.getElementsByClassName("AcceptUI")[0];
        ("ontouchstart" in window || navigator.msMaxTouchPoints) && p(t, "touchend", a), p(t, "click", a), p(window, "message", g),
            function(t, e) {
                e.billingAddressOptions = t.getAttribute("data-billingAddressOptions"), e.paymentOptions = t.getAttribute("data-paymentOptions"), e.apiLoginID = t.getAttribute("data-apiLoginID"), e.clientKey = t.getAttribute("data-clientKey"), e.acceptUIFormBtnTxt = t.getAttribute("data-acceptUIFormBtnTxt"), e.acceptUIFormHeaderTxt = t.getAttribute("data-acceptUIFormHeaderTxt"), e.parentUrl = window.location.href, i = t.getAttribute("data-responseHandler")
            }(t, e), d(),
            function() {
                var t = document.createElement("style");
                t.type = "text/css", t.innerHTML = "#AcceptUIBackground {visibility: hidden;opacity: 0;z-index: -1; }#AcceptUIContainer.show {visibility: visible; z-index: 200;opacity: 1; top: 50%;}#AcceptUIBackground.show { opacity: .7;visibility: visible;z-index: 8;}#collapseThree .AcceptUI{visibility: hidden;}#AcceptUIContainer{    min-height: 288px;}", document.getElementsByTagName("head")[0].appendChild(t)
            }()
    }

    function a(t) {
        return function() {
            var t = w(l);
            t.className = t.className = "show", overlay = v(s), overlay.className = "show"
        }(), t.stopPropagation(), t.preventDefault(), window.scrollTo(0, 0), !1
    }

    function p(t, e, n) {
        t.addEventListener ? t.addEventListener(e, n, !1) : t.attachEvent && t.attachEvent("on" + e, n)
    }

    function m() {
        return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
    }

    function u() {
        var t = w(l),
            e = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
            n = m();
        I() ? (t.style.top = "0", t.style.marginLeft = "-10px", t.style.width = "100%", t.style.height = "100%") : (e <= 550 ? (t.style.left = "0", t.style.width = e + "px", t.style.marginLeft = "0") : ( t.style.width = "100%"), n <= c ? (t.style.top = "0", t.style.height = "288px", t.style.marginTop = "0") : (t.style.height = "288px"))
    }

    function o() {
        return "https://js.authorize.net"
    }

    function y(t) {
        var e = document.getElementById(s);
        e ? (e.style.display = "block", e.style.height = function() {
            var t = document.body,
                e = document.documentElement;
            return Math.max(t.scrollHeight, t.offsetHeight, e.clientHeight, e.scrollHeight, e.offsetHeight)
        }() + "px") : ((e = document.createElement("div")).innerHTML = '<div id="' + s + '" style="opacity: 0.1; background: #000000; position: absolute;  left: 0; right: 0; top: 0; bottom: 0; min-height: 450px;"></div>', e = e.firstChild, document.addEventListener && (e.style.zIndex = "8"), document.body.insertBefore(e, t))
    }

    function d(t) {
        var e = w(l),
            n = e.firstChild,
            i = b(),
            a = v(s),
            o = {
                verifyOrigin: "AcceptUI",
                type: "SYNC",
                pktData: window.location.origin
            },
            d = ~navigator.userAgent.indexOf("Android") && navigator.userAgent.match(/Chrome\/[.0-9]* Mobile/);
        I() ? (c = m(), e.style.borderRadius = "0px") : e.style.borderRadius = "6px", e.style.visibility = "", e.style.position = "relative", e.style.boxShadow = "rgba(0, 0, 0, 0.40) 5px 5px 16px", e.style.zIndex = "9", e.style.display = "block", d || (e.style.overflow = "hidden"), n.src = i + "#" + encodeURIComponent(JSON.stringify(o)), n.style.height = "100%", n.style.width = "100%", n.style.scrolling = "no", n.style.seamless = "seamless", n.style.overflowY = "hidden", n.style.overflowX = "hidden", document.body.querySelector('[data-payment="paymentFormAuthorize"]').appendChild(a), document.getElementById(l) || document.body.querySelector('[data-payment="paymentFormAuthorize"]').appendChild(e), r = function(n, i, a) {
            var o;
            return function() {
                var t = this,
                    e = arguments;
                o ? clearTimeout(o) : a && n.apply(t, e), o = setTimeout(function() {
                    a || n.apply(t, e), o = null
                }, i || 100)
            }
        }(u, 30), p(window, "resize", r), u(), y(document.getElementById(l)), document.getElementById(s), window.scrollTo(0, 0)
    }

    function h() {
        var t, e;
        n ? (n.close(), n = null) : ((t = v(s)).parentElement === document.body && document.body.removeChild(t), (e = w(l)).parentElement === document.body && document.body.removeChild(e), r && function(t, e, n) {
            t.removeEventListener && t.removeEventListener(e, n, !1), t.detachEvent && t.detachEvent("on" + e, n)
        }(window, "resize", r))
    }

    function g(t) {
        if ("https://js.authorize.net" === t.origin && t.data && "object" == typeof t.data && t.data.verifyOrigin && "AcceptMain" === t.data.verifyOrigin) switch (t.data.type) {
            case "ACK":
                clearInterval(void 0),
                    function(t, e) {
                        var n = {
                            verifyOrigin: "AcceptUI",
                            type: e,
                            pktData: t
                        };
                        w(l).firstChild.contentWindow.postMessage(n, o())
                    }(e, "INIT");
                break;
            case "FIT_WINDOW":
                t.data.pktData && t.data.pktData.height && (iframe = w(l), iframe.style.height = t.data.pktData.height + "px", iframe.firstChild.style.height = t.data.pktData.height + "px", c = t.data.pktData.height);
                break;
            case "RESPONSE":
                A(t.data.pktData), h(), d();
                break;
            case "CLOSE_IFRAME":
                h(), d()
        }
    }
    var f, v = function(t) {
            var e = document.getElementById(t),
                n = {
                    position: "absolute",
                    top: "0",
                    left: "0",
                    background: "#000",
                    opacity: "0",
                    filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=60)",
                    minWidth: "100%",
                    minHeight: "100%",
                    zIndex: "8"
                };
            if (!e)
                for (var i in (e = document.createElement("div")).id = t, n) e.style[i] = n[i];
            return e
        },
        w = function(t) {
            var e, n = document.getElementById(t);
            if (n && "IFRAME" === n.tagName ? (n.id = t + "_inner", (e = document.createElement("div")).id = t, e.appendChild(n)) : e = n, !e) {
                var i = document.createElement("iframe");
                i.frameBorder = 0, (e = document.createElement("div")).name = "Accept UI Merchant Window.", e.id = t, e.appendChild(i)
            }
            return e
        },
        b = function(t) {
            return "https://js.authorize.net/v3/acceptMain/acceptMain.html"
        },
        I = ((f = function() {
            return f.any
        }).Android = !!navigator.userAgent.match(/Android/i), f.BlackBerry = !!navigator.userAgent.match(/BlackBerry/i), f.iOS = !!navigator.userAgent.match(/iPhone|iPad|iPod/i), f.Opera = !!navigator.userAgent.match(/Opera Mini/i), f.Windows = !!navigator.userAgent.match(/IEMobile/i), f.any = f.Android || f.BlackBerry || f.iOS || f.Opera || f.Windows, f),
        A = function(t) {
            "function" == typeof i ? i.call(null, t) : window[i](t)
        };
    "complete" === document.readyState ? t() : document.onreadystatechange = function() {
        "complete" == document.readyState && t()
    }
}(window.AcceptUI = window.AcceptUI || {});

function responseHandler(response) {
    if (response.messages.resultCode === "Error") {
        var i = 0;
        var msg_error = '';
        while (i < response.messages.message.length) {
            console.log(
                response.messages.message[i].code + ": " +
                response.messages.message[i].text
            );
            msg_error +=response.messages.message[i].code + ": " +
                response.messages.message[i].text;
            i = i + 1;
        }
        Swal.fire({
            icon: 'error',
            text: msg_error,
        });
    } else {
        console.log(response);
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        data_payment.createTransactionRequest.transactionRequest.payment.opaqueData.dataDescriptor = response.opaqueData.dataDescriptor;
        data_payment.createTransactionRequest.transactionRequest.payment.opaqueData.dataValue = response.opaqueData.dataValue;
        var raw = JSON.stringify(data_payment);

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };

        fetch("https://api.authorize.net/xml/v1/request.api", requestOptions)
            .then(response => response.text())
            .then(result =>{
            var data = JSON.parse(result);
            console.log(data);
            var i = 0;
        if (data.messages.resultCode === "Error") {
            var msg_error = '';
            while (i < data.transactionResponse.errors.length) {
                console.log(
                    data.transactionResponse.errors[i].errorCode + ": " + data.transactionResponse.errors[i].errorText
                );
                msg_error +=data.transactionResponse.errors[i].errorCode + ": " + data.transactionResponse.errors[i].errorText;
                i = i + 1;
            }
            Swal.fire({
                icon: 'error',
                text: msg_error,
            });
        }else{
            while (i < data.transactionResponse.messages.length) {
                console.log(
                    data.transactionResponse.messages[i].code + ": " +
                    data.transactionResponse.messages[i].description
                );
                i = i + 1;
            }
            let data_info = [];
            let total = document.body.querySelector('[data-payment="paymentFormAuthorize"]').getAttribute('data-total');
            data_info[1] = {name:'payment',value: payment_order()};
            data_info[2] = {name:'products',value: cart.products};
            data_info[3] = {name:'total',value: JSON.parse(total)  };
            data_info[4] = {name:'payment_type',value: 3 };
            send_order(data_info);
        }
            }
                )
            .catch(error => console.log('error', error ));
    }
}


function paymentFormUpdate(opaqueData) {
    document.getElementById("dataDescriptor").value = opaqueData.dataDescriptor;
    document.getElementById("dataValue").value = opaqueData.dataValue;
    document.getElementById("paymentForm").submit();
}