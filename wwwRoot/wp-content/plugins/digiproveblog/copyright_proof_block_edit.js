// FUNCTIONS TO SUPPORT EDIT/NEW POST USING BLOCK_BASED EDITOR (Introduced at Wordpress 5)
// This JS file only loaded when Block Editor is active

// Code below is clunky way of displaying Digiproving result notice on Block Editor screen (from WP 5.0).
// 
// The clunky way is:
//    - assume that a notice being displayed indicates Digiproving finished or under way
//    - So start periodic checking for that starting when user presses Update or Publish and has previous;y checked the "& Digiprove" checkbox
//    - on detecting this, schedule an ajax call from client back to server to get "dprv_last_result" option from db (scheduling that call to allow time for Digiprove process to complete)
//    - once started, refresh the "dprv_last_result" every 3 seconds
//    - as well as displaying notice using wp-notices API, to display the results in the Copyright/Ownership/Licensing meta box and highlighting it with flashing yellow bg

jQuery(document).ready(function ()
{        // start of document ready wrapper

console.log("copyright_proof_block-edit.js loaded");

    function dprv_add_digiprove_submit_button()         // Allow "& Digiprove" tick box beside Publish / Update button (Block-based editor only)
    {
        //if  (digiprive_this_post != "Yes" (return)
        var z = document.getElementsByClassName("edit-post-header__settings");
        if (z.length === 0) { return; }
        var hPanel = z[0];
        var chkContainer = document.createElement("span");
        var publish_btn;
        var btns = hPanel.getElementsByClassName("editor-post-publish-button");
        if (btns.length === 0) {
            btns = hPanel.getElementsByClassName("editor-post-publish-panel__toggle");
        }
        if (btns.length > 0) {
            publish_btn = btns[0];
        }
        else
        {
            var pChildren = hPanel.childNodes;
            for (var zz = 0; zz < pChildren.length; zz++)
            {
                if (pChildren[zz].className.indexOf("editor-post-publish-button") !== -1 || pChildren[zz].className.indexOf("editor-post-publish-panel__toggle") !== -1)
                {
                    publish_btn = pChildren[zz];
                    chkContainer = document.createElement("div");
                    break;
                }
            }
        }
        if (typeof publish_btn === "object")
        {
            publish_btn.style.marginLeft = "12px";
            publish_btn.marginRight = "4px";
            chkContainer.style.paddingRight = "10px";
            chkContainer.style.paddingLeft = "4px";

            var chk = document.createElement("input");
            chk.type = "checkbox";
            chk.id = "dprv_publish_dp_block";
            chk.name = "dprv_publish_dp_block";
            chk.value = "Yes";
            chk.checked = false;
            chk.addEventListener("click", dprv_now_toggle);
            publish_btn.addEventListener("click", startDetection);

            var chkLabelText = document.createTextNode("& Digiprove");
            var chkLabel = document.createElement("label");
            chkLabel.style.marginRight = "12px";
            chkLabel.htmlFor = "dprv_publish_dp_block";
            chkLabel.appendChild(chkLabelText);
            chkContainer.appendChild(chk);
            chkContainer.appendChild(chkLabel);
            publish_btn.insertAdjacentElement("afterend", chkContainer);
        }

        // below executes on click of "& Digiprove" checkbox
        function dprv_now_toggle()      
        {
            if (document.getElementById("dprv_publish_dp_block").checked === true) {
                document.getElementById("dprv_now").value = "Yes";
            }
            else {
                document.getElementById("dprv_now").value = "No";
            }
        }
    }

jQuery(window).load(dprv_add_digiprove_submit_button);


// Looking to detect notice on display by Gutenberg("Post Updated or similar, indicating that Digiproving operation has finished"
function detectChange() {
    console.log("detectChange starts");

    // Introduced at 4.05 another way of detecting as previous way did not work with later WP releases
    var elements = document.getElementsByClassName("components-notice__content");
    if (elements.length > 0 && elements[0].innerHTML.length > 0) {
        // success on scenario 1
    }
    else {
        elements = document.getElementsByClassName("components-notice-list");
        if (elements.length > 0 && elements[0].childNodes.length > 0) {
            // Success on scenario 2
        }
        else
        {
            elements = jQuery("[class*='components-snackbar__content']");
            if (elements.length === 0 || elements[0].innerHTML.length === 0)
            {
                return;
            }
			// success on scenario 3  (arises in WP 5.3.1 or 5.3.2)
        }
    }
    // detected notice on display
    setTimeout(function () { dprv_get_last_result(); }, 2000);  // allow time for DP process to complete
    clearInterval(repeatDetectChange);
}
var repeatDetectChange;
var lastNoticeMillseconds;
function startDetection()
{
    if (document.getElementById("dprv_publish_dp_block").checked === true) {
        repeatDetectChange = setInterval(detectChange, 2000);
        lastNoticeMillseconds = new Date().getTime();
    }
}

// AJAX Calls:
function dprv_get_last_result() {
    //console.log("dprv_get_last_result starts");
    // Checking for either drv_now or dprv_publish_dp_block because dprv_now value is lost after post-back
    if ((document.getElementById("dprv_now") && document.getElementById("dprv_now") === "Yes") || (document.getElementById("dprv_publish_dp_block") && document.getElementById("dprv_publish_dp_block").checked == true)) {
        //console.log("dprv_get_last_result - dprv_now is Yes");
        var thisNoticeMillseconds = new Date().getTime();
        if ((thisNoticeMillseconds - lastNoticeMillseconds) > 1000)  //Just to prevent trashing 
        {
            //console.log("getting last result");
            jQuery(document).ready(function () {
                var data = { action: "dprv_ajax_functions", function: "dprv_get_last_result" };
                jQuery.post(ajaxurl, data, function (response) { dprv_showNotice(response); });
            });
        }
        else {
            // console.log("skipped getting last result - too soon since last");
        }
        lastNoticeMillseconds = thisNoticeMillseconds;
    }
    else {
        console.log("dprv_publish_dp_block not set");
    }
}

function dprv_showNotice(notice) {
    notice = JSON.parse(notice);
    //console.log("dprv_showNotice " + notice);
    if (document.getElementById("dprv_last_result_notice")) {
        var last_notice = document.getElementById("dprv_last_result_notice").innerHTML;
        document.getElementById("dprv_last_result_notice").innerHTML = notice + "&nbsp;<br/>";
        if (last_notice != document.getElementById("dprv_last_result_notice").innerHTML) {
            dprv_highlight(document.getElementById("dprv_last_result_notice"));
        }
    }
    else {
        // TODO: make this more pretty
        alert(wp_strip_all_tags(notice));
    }
    // Display notice at top of page
    var explanation = "";
    var plainNotice = "";
    var noticeStatus = "warning";
    if (notice.startsWith("Digiprove certificate id")) {
        noticeStatus = "success";
    }
    var p0 = notice.indexOf("<span");
    if (p0 !== -1) {
        var p1 = notice.indexOf("title=", p0);
        if (p1 !== -1) {
            var p3 = notice.indexOf(">", p1);
            if (p3 !== -1) {
                explanation = notice.substr(p1 + 7, p3 - p1 - 8);
            }
        }
        if (p0 !== -1) {
            plainNotice = notice.substr(0, p0);
            if (p3 !== -1) {
                plainNotice += notice.substr(p3+1);
                plainNotice = plainNotice.replace("</span>", "").replace("&quot;", "\"");
            }
           
            if (explanation.length > 0) {
                plainNotice += (" (" + explanation + ")").replace("&quot;", "\"").replace("&quot;", "\"");
            }
        }
    }
    // Fancy (JSX) notice
    var fancyNotice = "<Notice status=\'" + noticeStatus + "\'>" + notice + "</Notice>";   // will need JSX to make this work

    (function (wp) {
        wp.data.dispatch('core/notices').createNotice(                      // Reports wp.data.dispatch('core/notices') as undefined
            'success', // Can be one of: success, info, warning, error.
            plainNotice, // Text string to display.
            {
                isDismissible: true  //, // Whether the user can dismiss the notice.
                // Any actions the user can perform.
                //actions: [
                //     {
                //        url: '#',
                //        label: 'View post'
                //    }
                //]
            }
        );
    })(window.wp);

    /*
    // Below is jsx way of doing things, can apparently support html/styled notices.  Does not work, probably needs some form of initialisation 
    const MyNotice = () => (
        <Notice status="error">
            An unknown error occurred.
    </Notice>
    );
    */

    var flashCount = 5;
    var repeatBGChange;
    function dprv_highlight() {
        flashCount = 5;
        repeatBGChange = setInterval(toggleBackground, 900);
    }

    function toggleBackground() {
        flashCount--;
        //console.log("flashCount=" + flashCount);
        if (typeof document.getElementById("dprv_last_result_notice").style.backgroundColor === "undefined" || document.getElementById("dprv_last_result_notice").style.backgroundColor !== "yellow") {
            document.getElementById("dprv_last_result_notice").style.backgroundColor = "yellow";
        }
        else {
            document.getElementById("dprv_last_result_notice").style.backgroundColor = "transparent";
        }
        if (flashCount == 0) { clearInterval(repeatBGChange); }
    }
}

});     // end of document ready wrapper
