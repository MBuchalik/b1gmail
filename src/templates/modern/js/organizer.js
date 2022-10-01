/*
 * b1gMail
 * Copyright (c) 2021 Patrick Schlangen et al
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 */

var _addrSel;
function initAddrSel()
{
	var sel = new selecTable(EBID('addressTable'), 'tr', false);
	sel.cbGetItemID = function(element)
	{
		return(element.id.substr(5));
	}
	sel.cbRowFilter = function(element)
	{
		return(element.id.substr(0, 5) == 'addr_');
	}
	sel.cbSelectSingleItem = function(element)
	{
		MakeXMLRequest('organizer.addressbook.php?action=showContact&id='+this.getItemID(element)+'&sid=' + currentSID, function(http)
			{
				if(http.readyState == 4)
				{
					EBID('previewArea').innerHTML = http.responseText;
					EBID('multiSelPreview').style.display = 'none';
					EBID('previewArea').style.display = '';
				}
			});
	}
	sel.cbSelectionChanged = function()
	{
		if(this.sel.length <= 1 || !EBID('previewArea') || !EBID('multiSelPreview'))
			return;
		showAddrMultiSelPreview(this.sel.length);
	}
	sel.cbItemContextMenu = function(element, event)
	{
		return(false);
	}
	sel.cbItemDoubleClick = function(element)
	{
		document.location.href = 'organizer.addressbook.php?action=editContact&id='+this.getItemID(element)+'&sid='+currentSID;
	}
	sel.init();
	_addrSel = sel;
}
function showAddrMultiSelPreview(no)
{
	EBID('previewArea').style.display = 'none';
	EBID('multiSelPreview').style.display = '';

	if(no > 0)
	{
		EBID('multiSelPreview_count').innerHTML = no + ' ' + lang['contactsselected'];
	}
	else
	{
		EBID('multiSelPreview_count').innerHTML = lang['nocontactselected'];;
	}
}
function transferSelectedAddresses()
{
	var f = EBID('addrIDs');
	if(f)
	{
		f.value = '';

		var IDs = _addrSel.getIDList();

		for(i=0; i<IDs.length; i++)
		{
			f.value += IDs[i] + ';';
		}

		if(f.value.length > 0)
			f.value = f.value.substr(0, f.value.length-1);
	}
}
function abExport()
{
	openOverlay('organizer.addressbook.php?sid=' + currentSID + '&action=exportDialog',
		lang['export'],
		440,
		160,
		true);
}
function abImport()
{
	openOverlay('organizer.addressbook.php?sid=' + currentSID + '&action=importDialogStart',
		lang['import'],
		440,
		140,
		true);
}
function abGroups()
{
	openOverlay('organizer.addressbook.php?sid=' + currentSID + '&action=groups',
		lang['groups'],
		550,
		400,
		true);
}
function updateCurrentGroup(id, sid)
{
	document.location.href = 'organizer.addressbook.php?sid=' + sid + '&group=' + id;
}
function checkContactForm(form)
{
	if((form.elements['vorname'].value.length < 1
		|| form.elements['nachname'].value.length < 1)
		&& form.elements['firma'].value.length < 1)
	{
		alert(lang['fillin']);
		return(false);
	}
	return(true);
}
function addrFunction(what)
{
	if(what == 'selfComplete'
		&& (trim(EBID('vorname').value).length == 0
			|| trim(EBID('nachname').value).length == 0))
	{
		alert(lang['fillinname'])
	}
	else
	{
		EBID('submitAction').value = what;
		document.forms.f1.submit();
	}
}
function addrImportVCF()
{
	openOverlay('organizer.addressbook.php?action=vcfImportDialog&sid=' + currentSID,
			lang['importvcf'],
			520,
			150);
}
function checkGroupForm(form)
{
	if(form.elements['title'].value.length < 1)
	{
		alert(lang['fillin']);
		return(false);
	}
	return(true);
}
function addrUserPicture(id)
{
	openOverlay('organizer.addressbook.php?action=userPictureDialog&id=' + id + '&sid=' + currentSID,
		lang['userpicture'],
		520,
		150);
}
function addrImportDialog(sid)
{
	openOverlay('organizer.addressbook.php?action=importDialog&type=' + EBID('importType').value + '&encoding=' + EBID('importEncoding').value + '&sid=' + sid,
		lang['import'],
		520,
		130);
}
