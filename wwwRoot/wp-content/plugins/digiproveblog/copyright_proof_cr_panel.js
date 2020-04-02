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
    //console.log("toggleCustom");
    if (document.getElementById('dprv_custom_license').checked === true)
    {
        document.getElementById('dprv_default_license').checked = false;
		document.getElementById('dprv_this_license_label').style.display="none";
		document.getElementById('dprv_license_type').style.display="none";
        document.getElementById('dprv_license_input').style.display = "";
        document.getElementById('dprv_create_own_caption').style.display = "";
		document.getElementById('dprv_license_caption_label').style.display="none";
		document.getElementById('dprv_license_abstract_label').style.display="none";
        document.getElementById('dprv_license_caption').style.display = "";

        var backup = document.getElementById('dprv_license_caption').value;  // in case no-match from list
        document.getElementById('dprv_license_caption').value = document.getElementById('dprv_license_caption_label').innerHTML;
        //console.log("set dprv_license_caption.value to " + document.getElementById('dprv_license_caption_label').innerHTML);
        if (document.getElementById('dprv_license_caption').selectedIndex == -1)
        {
            document.getElementById('dprv_license_caption').value = backup;
        }
        dprv_ToggleLicenseCaptionInputChanged();

        //document.getElementById('dprv_license_caption_input').style.display = "";
        document.getElementById('dprv_create_own_caption').style.display = "";

        document.getElementById('dprv_license_abstract').style.display = "";
		document.getElementById('dprv_license_url_link').style.display="none";
		document.getElementById('dprv_license_url_input').style.display="";
	}
	else
	{
		document.getElementById('dprv_license_input').style.display="none";
		document.getElementById('dprv_license_caption_label').style.display="";
		document.getElementById('dprv_license_abstract_label').style.display="";
		document.getElementById('dprv_license_caption').style.display="none";
        //document.getElementById('dprv_license_caption_input').style.display = "none";
        document.getElementById('dprv_create_own_caption').style.display = "none";
        document.getElementById('dprv_license_abstract').style.display = "none";
		document.getElementById('dprv_license_url_link').style.display="";
		document.getElementById('dprv_license_url_input').style.display="none";
		if (document.getElementById('dprv_default_license').checked == true)
		{
			document.getElementById('dprv_this_license_label').style.display="";
			document.getElementById('dprv_license_type').style.display="none";
		}
		else
		{
            if (document.getElementById('dprv_license_type').selectedIndex < 0) {
                document.getElementById('dprv_license_caption_label').innerHTML = "";
            }
            document.getElementById('dprv_this_license_label').style.display = "none";
			document.getElementById('dprv_license_type').style.display="";
			//document.getElementById('dprv_license_type').focus();
        }
        dprv_LicenseChanged();
	}
}
function dprv_ToggleLicenseCaptionInputChanged()
{
    if (document.getElementById('dprv_license_caption_input').value.trim() === "")
    {
        //alert("document.getElementById(dprv_license_caption_input).value.trim() = " + document.getElementById('dprv_license_caption_input').value.trim());
        document.getElementById('dprv_license_caption').style.color = '#000000';
        if (document.getElementById('dprv_license_caption').selectedIndex > -1) {
            document.getElementById('dprv_license_caption_label').innerHTML = document.getElementById('dprv_license_caption').value;
        }
    }
    else
    {
        document.getElementById('dprv_license_caption_label').innerHTML = document.getElementById('dprv_license_caption_input').value;
        document.getElementById('dprv_license_caption').style.color = '#999999';
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
    //console.log("dprv_SetLicense, document.getElementById(dprv_license_type'.value == " + document.getElementById('dprv_license_type').value);
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
                //document.getElementById('dprv_license_caption_input').style.display = "none";
                document.getElementById('dprv_create_own_caption').style.display = "none";
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

//]]>