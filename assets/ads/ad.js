let isOverIFrame = false;
let currElem;
let siteurl;
let totalRetry = 0;
let adCashId;
let ads = {};


function log(message) {
    console.log(message)
}

function attachOnloadEvent(func, obj) {
    if (typeof window.addEventListener != 'undefined') {
        window.addEventListener('load', func, false);
    } else if (typeof document.addEventListener != 'undefined') {
        document.addEventListener('load', func, false);
    } else if (typeof window.attachEvent != 'undefined') {
        window.attachEvent('onload', func);
    } else {
        if (typeof window.onload == 'function') {
            var oldonload = onload;
            window.onload = function () {
                oldonload();
                func();
            };
        } else {
            window.onload = func;
        }
    }
}

function httpRequest(AdUrl) {
    if (AdUrl != '') {
        var xhttp = new XMLHttpRequest();
        xhttp.open('GET', AdUrl, true);
        xhttp.send();
    }
}
async function sleep(time) {
    return new Promise(resolve => {
        setTimeout(resolve, time);
    });
}

function getStyle(el, styleProp) {
    var value, defaultView = (el.ownerDocument || document).defaultView;
    // W3C standard way:
    if (defaultView && defaultView.getComputedStyle) {
        // sanitize property name to css notation
        // (hypen separated words eg. font-Size)
        styleProp = styleProp.replace(/([A-Z])/g, "-$1").toLowerCase();
        return defaultView.getComputedStyle(el, null).getPropertyValue(styleProp);
    } else if (el.currentStyle) { // IE
        // sanitize property name to camelCase
        styleProp = styleProp.replace(/\-(\w)/g, function (str, letter) {
            return letter.toUpperCase();
        });
        value = el.currentStyle[styleProp];
        // convert other units to pixels on IE
        if (/^\d+(em|pt|%|ex)?$/i.test(value)) {
            return (function (value) {
                var oldLeft = el.style.left, oldRsLeft = el.runtimeStyle.left;
                el.runtimeStyle.left = el.currentStyle.left;
                el.style.left = value || 0;
                value = el.style.pixelLeft + "px";
                el.style.left = oldLeft;
                el.runtimeStyle.left = oldRsLeft;
                return value;
            })(value);
        }
        return value;
    }
}
// Create a new instance of MutationObserver
var observer = new MutationObserver(async function (mutations) {
    // Check if the element is present after each mutation
    // Element is present, you can now run your function
    await sleep(1000)
    let iframes = document.querySelectorAll('iframe');

    for (let ad in ads) {
        for (let item of ads[ad]) {
            let AdUrl = ''
            let elem
            if (ad == 'adcash') {
                let adCaseIframe = await adCashElemGet(item.tag, item.querySelector)
                if (adCaseIframe && !item.alreadyMuted) {
                    elem = adCaseIframe
                }
            }
            else if (ad == 'adsterra') {
                let element = await adSterraElemGet(iframes, item)
                if (element && !item.alreadyMuted) {
                    elem = element
                }
            }
            else if (ad == 'googlead') {
                if (item['nextElement'].tagName == 'INS' && item['nextElement'].getAttribute('data-ad-status') != 'unfilled' && !item.alreadyMuted) {
                // if (item['nextElement'].tagName == 'INS' && !item.alreadyMuted) {
                    elem = item['nextElement']
                }
            }
            else if (ad == 'monetag') {
                // Get all iframes for monetage vignette banner
                let monetagElem = await monetagElemGet(iframes, item)
                if (monetagElem && !item.alreadyMuted) {
                    elem = monetagElem
                }
            }

            if (elem) {
                item.alreadyMuted = true
                elem.onmouseover = processMouseOver;
                elem.onmouseout = processMouseOut;
                AdUrl = siteurl + '/tp-ads/' + item.getPublisher + '/' + item.thirdPartyId + '/' + item.currentUrl;
            }
            httpRequest(AdUrl)
        }
    }
    // Stop observing once the element is rendered (optional)
});

// Configure and start the observer
var config = { childList: true, subtree: true };
observer.observe(document.body, config);

function processMouseOut() {
    // log("IFrame mouse >> OUT << detected.");
    isOverIFrame = false;
    currElem = null
    top.focus();
}

function processMouseOver(e) {
    currElem = e.srcElement
    // log("IFrame mouse >> OVER << detected.");

    isOverIFrame = true;
}

function processIFrameClick() {
    if (isOverIFrame) {
        let getPublisher, thirdPartyId
        var advertises = document.getElementsByClassName("MainAdverTiseMentDiv");
        getPublisher = advertises[0].getAttribute('data-publisher');
        // replace with your function
        if (currElem.getAttribute('id') == 'creative_iframe' || currElem.getAttribute('id') == 'note-0') {
            // log("IFrame >> CLICK << detected adcash. ");
            thirdPartyId = ads['adcash'][0].thirdPartyId;
        }
        else if (!currElem.getAttribute('id')?.includes('container') && (currElem.tagName == 'DIV' || currElem.tagName == 'IFRAME') && parseInt(getStyle(currElem, 'z-index')) > 214748364 && getStyle(currElem, 'background').includes("rgba(0, 0, 0, 0.3)")) {
            // log("IFrame >> CLICK << detected. ");
            for (let item of ads['monetag']) {
                if (item.adType == 'monetag-vignette') {
                    thirdPartyId = item.thirdPartyId
                    break
                }
            }
        }
        else if (!currElem.getAttribute('id')?.includes('container') && (currElem.tagName == 'DIV' || currElem.tagName == 'IFRAME') && parseInt(getStyle(currElem, 'z-index')) > 214748364) {
            // log("IFrame >> CLICK << detected. ");
            for (let item of ads['monetag']) {
                if (item.adType == 'monetag-inpage') {
                    thirdPartyId = item.thirdPartyId
                    break
                }
            }
        }
        else if (currElem.tagName == 'IFRAME' && currElem.getAttribute('data-google-container-id')) {
            // log("IFrame >> CLICK << detected. ");
            thirdPartyId = currElem.parentElement?.parentElement?.previousElementSibling?.getAttribute('data-id') || ads['googlead'][0].thirdPartyId;
        }

        else {
            // log("IFrame >> CLICK << detected. ");
            let id = currElem.getAttribute('id')
            for (let item of ads['adsterra']) {
                if (id == item.id) {
                    thirdPartyId = item.thirdPartyId
                    break
                }
            }
            thirdPartyId = thirdPartyId || ads['adsterra'][0].thirdPartyId;
        }
        let AdUrl = siteurl + '/tp-ad-clicked/' + getPublisher + '/' + thirdPartyId
        httpRequest(AdUrl)
    }
}


async function adCashElemGet(query, querySelector) {
    let containers
    if (query == 'in-page-message') {
        containers = document.getElementsByTagName(query)
    }
    else {
        containers = document.querySelectorAll(query)
    }
    let adCaseIframe;
    for (let container of containers) {
        if (container.shadowRoot) {
            adCaseIframe = container.shadowRoot.querySelector(querySelector)
            if (adCaseIframe) {
                break
            }
        }
    }
    return adCaseIframe
}

async function monetagElemGet(iframes, item) {
    let element
    for (let el of iframes) {
        let result = getStyle(el, 'background')

        let inPageResult = getStyle(el, 'max-width')
        if (result.includes("rgba(0, 0, 0, 0.3)") && item.adType == 'monetag-vignette' && !item.alreadyMuted) {
            let iframe = el.contentDocument || el.contentWindow.document
            var iframeContent = iframe.querySelectorAll('div');
            for (let iEl of iframeContent) {
                let result = getStyle(iEl, 'top')
                if (result.includes("50")) {
                    element = iEl
                    break
                }
            }
            if (!element) element = el
            break
        }
        else if (!result.includes("rgba(0, 0, 0, 0.3)") && inPageResult == '420px' && item.adType == 'monetag-inpage' && !item.alreadyMuted) {
            element = el
            break
        }
    }
    if (!element) {
        var divElems = document.querySelectorAll('div');
        for (let el of divElems) {
            let result = getStyle(el, 'z-index')
            if (parseInt(result) > 214748364 && item.adType == 'monetag-inpage' && el.childNodes[0]?.tagName !== 'A' && !item.alreadyMuted) {
                element = el
                break
            }
        }
    }
    return element
}

async function adSterraElemGet(iframes, item) {
    let nextElement = item['nextElement'].nextElementSibling;
    if (nextElement?.tagName == 'IFRAME' && !item.alreadyMuted) {
        return nextElement
    }
    else if (item['nextElement'].tagName == 'SCRIPT' && item['nextElement'].getAttribute('src') && !item.alreadyMuted) {
        let src = item['nextElement'].getAttribute('src')
        let srcArr = src.split('/')
        let lastIdxValue = srcArr[srcArr.length - 1].split('.')[0]

        let element
        for (let el of iframes) {
            let id = el.getAttribute('id')

            if (id?.includes(lastIdxValue)) {
                item.id = id
                element = el
                break
            }
        }
        return element
    }
}
async function processAdArr(key, value) {
    if (!ads[key]) {
        ads[key] = []
    }
    ads[key].push(value)
}

async function init() {
    var advertises = document.getElementsByClassName("MainAdverTiseMentDiv");
    var scripTags = document.getElementsByClassName("adScriptClass");
    var scripturl = scripTags[0].getAttribute('src');
    siteurl = scripturl.replace("/assets/ads/ad.js", "");
    var currentUrl = window.location.hostname;
    var inx;
    for (inx = 0; inx < advertises.length; inx++) {
        advertises[inx].setAttribute("style", "position:relative; z-index: 0; display:inline-block;");

        var getAdSize = advertises[inx].getAttribute('data-adsize');
        var getPublisher = advertises[inx].getAttribute('data-publisher');
        const isThirdParty = advertises[inx].getAttribute('data-thirdparty');
        const thirdPartyId = advertises[inx].getAttribute('data-id');
        const adType = advertises[inx].getAttribute('data-ad-type');
        // Get all iframe elements on the page
        if (isThirdParty) {
            let nextElement = advertises[inx].nextElementSibling;
            if (adType == 'adsterra-banner' || adType == 'adsterra-social') {
                processAdArr('adsterra', {
                    thirdPartyId,
                    nextElement,
                    getPublisher,
                    currentUrl
                })
            }
            if (adType == 'adcash') {
                processAdArr('adcash', {
                    thirdPartyId,
                    tag: 'div',
                    querySelector: '#modal',
                    getPublisher,
                    currentUrl
                })
                processAdArr('adcash', {
                    thirdPartyId,
                    tag: 'in-page-message',
                    querySelector: '#note-0',
                    getPublisher,
                    currentUrl
                })
            }
            if (adType == 'adcash-vignette') {
                processAdArr('adcash', {
                    thirdPartyId,
                    tag: 'div',
                    querySelector: '#modal',
                    getPublisher,
                    currentUrl
                })
            }
            if (adType == 'adcash-inpage') {
                processAdArr('adcash', {
                    thirdPartyId,
                    tag: 'in-page-message',
                    querySelector: '#note-0',
                    getPublisher,
                    currentUrl
                })
            }
            if (adType == 'monetag-vignette' || adType == 'monetag-inpage') {
                processAdArr('monetag', {
                    thirdPartyId,
                    getPublisher,
                    currentUrl,
                    adType
                })
            }

            if (adType == 'google') {
                processAdArr('googlead', {
                    thirdPartyId,
                    getPublisher,
                    currentUrl,
                    nextElement
                })
            }
        }
        else {
            var AdUrl = siteurl + '/ads/' + getPublisher + '/' + getAdSize + '/' + currentUrl;
            var xhttp = new XMLHttpRequest();
            xhttp.customdata = advertises[inx];
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText) {
                        this.customdata.innerHTML = this.responseText;
                    }
                }
            };
            xhttp.open('GET', AdUrl, true);
            xhttp.send();
        }

    }

}

if (typeof window.attachEvent != 'undefined') {
    top.attachEvent('onblur', processIFrameClick);
}
else if (typeof window.addEventListener != 'undefined') {
    top.addEventListener('blur', processIFrameClick, false);
}

attachOnloadEvent(init);


