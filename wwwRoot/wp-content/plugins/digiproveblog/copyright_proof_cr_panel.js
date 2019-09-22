//<![CDATA[

// FUNCTIONS TO SUPPORT THE COPYRIGHT/OWNERSHIP PANEL IN EDIT/NEW POST
function dprv_TogglePanel()
{
	if (document.getElementById('dprv_this_yes').checked === true)
	{
		document.getElementById('dprv_copyright_panel_body').style.display = "";
		if (document.getElementById('publish_dp_div'))
		{
			document.getElementById('publish_dp_div').style.display="";
        }
        if (document.getElementById('dprv_publish_dp_block'))
        {
            document.getElementById('dprv_publish_dp_block').parentNode.style.display = "";
        }
	}
	else
	{
		//document.getElementById('dprv_notice_preview').style.display = "none";
		document.getElementById('dprv_copyright_panel_body').style.display = "none";
		if (document.getElementById('publish_dp_div'))
		{
			document.getElementById('publish_dp_div').style.display="none";
        }
        if (document.getElementById('dprv_publish_dp_block'))
        {
            document.getElementById('dprv_publish_dp_block').checked = false;
            document.getElementById('dprv_publish_dp_block').parentNode.style.display = "none";
        }
	}
}

function dprv_ToggleAttributions()
{
	if (document.getElementById('dprv_all_original_yes').checked == true)
	{
		document.getElementById('dprv_attributions_0').style.display = "none";
		document.getElementById('dprv_attributions_1').style.display = "none";
	}
	else
	{
		document.getElementById('dprv_attributions_0').style.display = "";
		document.getElementById('dprv_attributions_1').style.display = "";
	}
}

function dprv_ToggleDefault()
{
	if (document.getElementById('dprv_default_license').checked == true)
	{
		document.getElementById('dprv_custom_license').checked = false;
		document.getElementById('dprv_this_license_label').style.display="";
		document.getElementById('dprv_license_type').style.display="none";
		document.getElementById('dprv_license_type').value = dprv_defaultLicenseId;
		document.getElementById('dprv_license_input').style.display="none";
		document.getElementById('dprv_this_license_label').innerHTML = dprv_default_licenseType;
		dprv_ToggleCustom();
		dprv_SetLicense();
	}
	else
	{
		document.getElementById('dprv_this_license_label').style.display="none";
		document.getElementById('dprv_license_type').style.display="";
	}
}

function dprv_ToggleCustom()
{
	if (document.getElementById('dprv_custom_license').checked == true)
	{
		document.getElementById('dprv_default_license').checked = false;
		document.getElementById('dprv_this_license_label').style.display="none";
		document.getElementById('dprv_license_type').style.display="none";
		document.getElementById('dprv_license_input').style.display="";
		document.getElementById('dprv_license_caption_label').style.display="none";
		document.getElementById('dprv_license_abstract_label').style.display="none";
		document.getElementById('dprv_license_caption').style.display="";
		document.getElementById('dprv_license_abstract').style.display="";
		document.getElementById('dprv_license_url_link').style.display="none";
		document.getElementById('dprv_license_url_input').style.display="";
		//document.getElementById('dprv_license_input').focus();
	}
	else
	{
		document.getElementById('dprv_license_input').style.display="none";
		document.getElementById('dprv_license_caption_label').style.display="";
		document.getElementById('dprv_license_abstract_label').style.display="";
		document.getElementById('dprv_license_caption').style.display="none";
		document.getElementById('dprv_license_abstract').style.display="none";
		document.getElementById('dprv_license_url_link').style.display="";
		document.getElementById('dprv_license_url_input').style.display="none";
		if (document.getElementById('dprv_default_license').checked == true)
		{
			document.getElementById('dprv_this_license_label').style.display="";
			document.getElementById('dprv_license_type').style.display="none";
		}
		else
		{
			document.getElementById('dprv_this_license_label').style.display="none";
			document.getElementById('dprv_license_type').style.display="";
			//document.getElementById('dprv_license_type').focus();
		}
	}
}

function dprv_LicenseChanged()
{
	if (document.getElementById('dprv_license_type').value != dprv_defaultLicenseId)
	{
		document.getElementById('dprv_default_license').checked = false;
	}
	dprv_SetLicense();
}

function dprv_SetLicense()
{
	if (document.getElementById('dprv_license_type').value == 0)
	{
		document.getElementById('dprv_this_license_label').innerHTML = dprv_literals["None"];
		document.getElementById('dprv_license_caption_label').innerHTML = "";
		document.getElementById('dprv_license_abstract_label').innerHTML = "";
		document.getElementById('dprv_license_url_link').href = "";
		document.getElementById('dprv_license_url_link').innerHTML = "";
	}
	else
	{
		for (i=0; i<dprv_licenseIds.length; i++)
		{
			if (dprv_licenseIds[i] == document.getElementById('dprv_license_type').value)
			{
				document.getElementById('dprv_license_caption_label').innerHTML = dprv_licenseCaptions[i];
				document.getElementById('dprv_license_abstract_label').innerHTML = dprv_licenseAbstracts[i];
				document.getElementById('dprv_license_url_link').href = dprv_licenseURLs[i];
				document.getElementById('dprv_license_url_link').innerHTML = dprv_licenseURLs[i];
				document.getElementById('dprv_license_caption').style.display = "none";
				document.getElementById('dprv_license_abstract').style.display = "none";
				document.getElementById('dprv_license_caption_label').style.display = "";
				document.getElementById('dprv_license_abstract_label').style.display = "";
				document.getElementById('dprv_license_url_input').style.display = "none";
				document.getElementById('dprv_license_url_link').style.display = "";
				break;
			}
		}
	}
}
function dprv_DisplayFiles()
{
	document.getElementById('dprv_files_panel').style.display='block';
	document.getElementById('dprv_files_panel').style.zIndex='2';
}
function dprv_HideFiles()
{
	document.getElementById('dprv_files_panel').style.display='none';
}
//console.log("JS calling");


// FUNCTIONS TO SUPPORT EDIT/NEW POST USING BLOCK_BASED EDITOR (Introduced at Wordpress 5)

function dprv_add_digiprove_submit_button()         // Allow "& Digiprove" tick box beside Publish / Update button (Block-based editor only)
{
    // if (classic-editor){return}
    //if  (digiprive_this_post != "Yes" (return)
    var z = document.getElementsByClassName("edit-post-header__settings");
    if (z.length === 0) { return; };
    var hPanel = z[0];
    var pChildren = hPanel.childNodes;
    for (var zz = 0; zz < pChildren.length; zz++) {
        if (pChildren[zz].className.indexOf("editor-post-publish-button") !== -1 || pChildren[zz].className.indexOf("editor-post-publish-panel__toggle") !== -1) {
            pChildren[zz].style.marginLeft = "12px";
            pChildren[zz].style.marginRight = "4px";
            var chkContainer = document.createElement("div");
            chkContainer.style.paddingRight = "10px";
            chkContainer.style.paddingLeft = "4px";

            var chk = document.createElement("input");
            chk.type = "checkbox";
            chk.id = "dprv_publish_dp_block";
            chk.name = "dprv_publish_dp_block";
            chk.value = "Yes";
            chk.checked = false;
            chk.addEventListener("click", dprv_now_toggle);

            var chkLabelText = document.createTextNode("& Digiprove");
            var chkLabel = document.createElement("label");
            chkLabel.style.marginRight = "12px";
            chkLabel.for = "dprv_publish_dp_block";
            chkLabel.appendChild(chkLabelText);
            chkContainer.appendChild(chk);
            chkContainer.appendChild(chkLabel);
            hPanel.insertBefore(chkContainer, pChildren[zz + 1]);
            break;
        }
    }

    function dprv_now_toggle() {
        if (document.getElementById("dprv_publish_dp_block").checked === true) {
            document.getElementById("dprv_now").value = "Yes";
        }
        else {
            document.getElementById("dprv_now").value = "No";
        }
    }
}
jQuery(window).load(dprv_add_digiprove_submit_button);


// Code below is clunky way of displaying Digiproving result notice on Block Editor screen (from WP 5.0).
// Ideal way is just to use Wordpress notice function, but cannot get it to display - probably there is an easy way that is not documented (yet) or I'm missing
// The clunky way is:
//    - assume that a notice being displayed indicates Digiproving finished or under way
//    - on detecting this schedule an ajax call from client back to server to get "dprv_last_result" option from db (scheduling that call to allow time for Digiprove process to complete)
//    - once started, refresh the "dprv_last_result" every 3 seconds
//    - to display the results in the Copyright/Ownership/Licensing meta box and highlighting it

/*
// detect new notice having been added
jQuery("body").on('DOMSubtreeModified', ".components-notice-list", function ()  // works sporadically could not diagnose
{
    setTimeout(function () { dprv_get_last_result(); }, 1500);
});
*/

// Alternative to using jQuery (at least it works)
function detectChange(elementClass) {
    var elements = document.getElementsByClassName("components-notice-list");
    if (elements.length > 0 && elements[0].childNodes.length > 0)
    {
        //console.log("detected notice on display");
        setTimeout(function () { dprv_get_last_result(); }, 2000);  // allow time for DP process to complete
        // clearInterval(repeatDetectChange);
    }
}
var repeatDetectChange = setInterval(detectChange, 2000);

var lastNoticeMillseconds = new Date().getTime();
// AJAX Calls:
function dprv_get_last_result()
{
    // console.log("dprv_get_last_result starts");
    // Checking for either drv_now or dprv_publish_dp_block because dprv_now value is lost after post-back
    if ((document.getElementById("dprv_now") && document.getElementById("dprv_now") === "Yes") || (document.getElementById("dprv_publish_dp_block") && document.getElementById("dprv_publish_dp_block").checked == true))
    {
        //console.log("dprv_get_last_result - dprv_now is Yes");
        var thisNoticeMillseconds = new Date().getTime();
        if ((thisNoticeMillseconds - lastNoticeMillseconds) > 1000)  //Just to prevent trashing 
        {
            //console.log("getting last result");
            jQuery(document).ready(function ($) {
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
        //console.log("dprv_publish_dp_block not set");
    }
}

function dprv_showNotice(notice)
{
    notice = JSON.parse(notice);
    if (document.getElementById("dprv_last_result_notice"))
    {
        var last_notice = document.getElementById("dprv_last_result_notice").innerHTML;
        document.getElementById("dprv_last_result_notice").innerHTML = notice + "&nbsp;<br/>";
        if (last_notice != document.getElementById("dprv_last_result_notice").innerHTML)
        {
            //console.log("change - blink 5");
            dprv_highlight(document.getElementById("dprv_last_result_notice"));
        }
    }
    else
    {
        // TODO: make this more pretty
        alert(wp_strip_all_tags(notice));
    }
    var flashCount = 5;
    var repeatBGChange;
    function dprv_highlight() {
        flashCount = 5;
        repeatBGChange = setInterval(toggleBackground, 900);
    }

    function toggleBackground() {
        flashCount--;
        //console.log("flashCount="+flashCount);
        if (typeof document.getElementById("dprv_last_result_notice").style.backgroundColor === "undefined" || document.getElementById("dprv_last_result_notice").style.backgroundColor !== "yellow") {
            document.getElementById("dprv_last_result_notice").style.backgroundColor = "yellow";
        }
        else {
            document.getElementById("dprv_last_result_notice").style.backgroundColor = "transparent";
        }
        if (flashCount == 0) { clearInterval(repeatBGChange); }
    }

    /*
    // Fool around with notices
    var a = document.getElementsByClassName("components-notice-list");
    if (a[0].childNodes.length > 0)
    {
        // Find Updated notice and add text to it

        // Alternative - Create (incomplete)
        var node = document.createElement("div");
        node.className = "components-notice is-info is-dismissible";

        var content_node = document.createElement("div");
        content_node.className = "components-notice__content";

        var textnode = document.createTextNode(notice);
        content_node.appendChild(textnode);
        node.appendChild(content_node);

        var xNode = document.createElement("button");
        xNode.className = "components-button components-icon-button components-notice__dismiss";
        var att = document.createAttribute("aria-label");       // Create a "class" attribute
        att.value = "Dismiss this notice";                           // Set the value of the class attribute
        xNode.setAttributeNode(att);

        a[0].appendChild(node);
        
    }
    */
}

//]]>