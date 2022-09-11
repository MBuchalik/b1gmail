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

/**************************************************************************
 * Addressbook
 *************************************************************************/
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

/**************************************************************************
 * Calendar
 *************************************************************************/
function calendarDaySizer()
{
	var wholeDay = EBID('calendarWholeDayBody'), wholeDayHeight = getElementMetrics(wholeDay, 'h'),
		day = EBID('calendarDayBody');
	var cParent = EBID('calendarContainer');

	var or = function()
	{
		var parentHeight = getElementMetrics(cParent, 'h');
		day.style.height = (parentHeight-wholeDayHeight) + 'px';

		var wt = EBID('weekWholeDayTable');
		if(wt)
		{
			var dayIndex = 0;
			var TDs = wt.getElementsByTagName('td');
			for(var i=0; i<TDs.length; i++)
			{
				var TD = TDs[i];
				if(TD.className == 'calendarWeekDayCaption')
				{
					var timeRow = EBID('timeRow_'+dayIndex+'_0');
					if(!timeRow)
					{
						alert("nada");
						continue;
					}

					var dayW = getElementMetrics(timeRow, 'w');
					TD.style.width = (dayW-3) + 'px';

					dayIndex++;
				}
			}
		}

		if(typeof(IE7) != 'undefined') IE7.recalc();
	}

	addEvent(window, 'resize', or);

	or();
}
function generateAttendeeList()
{
	var attendees = EBID('attendeeList');

	// clear
	var node;
	while(node = attendees.firstChild)
		attendees.removeChild(node);

	// files
	var files = EBID('attendees').value.split(';');

	// generate new table
	var table = document.createElement('table');
	table.style.marginTop = '5px';
	table.style.marginBottom = '5px';
	table.className = 'listTable';

	var tbody = document.createElement('tbody');

	// head
	var headTR = document.createElement('tr'),
		headTH = document.createElement('th');
	headTH.className = 'listTableHead';
	headTH.colSpan = '3';
	headTH.style.textAlign = 'center';
	headTH.appendChild(document.createTextNode(lang['attendees']));
	headTR.appendChild(headTH);
	tbody.appendChild(headTR);

	// add file rows
	var className = 'listTableTD', fileCount = 0;
	for(var i=0; i<files.length; i++)
	{
		if(files[i] != '')
		{
			var params	= files[i].split(','),
				attID			= params[0],
				attLastName		= params[1],
				attFirstName	= params[2];

			var img = document.createElement('img');
			img.setAttribute('src', tplDir + 'images/li/addr_priv.png');
			img.setAttribute('width', '16');
			img.setAttribute('height', '16');
			img.setAttribute('align', 'absmiddle');
			img.setAttribute('border', '0');
			img.setAttribute('alt', '');

			var td1 = document.createElement('td');
			td1.style.paddingLeft = '4px';
			td1.appendChild(img);
			td1.appendChild(document.createTextNode(' ' + attLastName + ', ' + attFirstName));

			var del = document.createElement('a'),
				delImg = document.createElement('img');
			delImg.setAttribute('src', tplDir + 'images/li/ico_delete.png');
			delImg.setAttribute('width', '16');
			delImg.setAttribute('height', '16');
			delImg.setAttribute('align', 'absmiddle');
			delImg.setAttribute('border', '0');
			delImg.setAttribute('alt', '');
			del.setAttribute('href', 'javascript:deleteAttendee(' + attID + ');');
			del.appendChild(delImg);

			var td2 = document.createElement('td');
			td2.setAttribute('width', '20%');
			td2.style.paddingRight = '4px';
			td2.style.textAlign = 'right';
			td2.appendChild(del);

			var tr = document.createElement('tr');
			tr.className = className;
			tr.appendChild(td1);
			tr.appendChild(td2);
			tbody.appendChild(tr);

			// cycle class name
			if(className == 'listTableTD')
				className = 'listTableTD2';
			else
				className = 'listTableTD';
			fileCount++;
		}
	}

	table.appendChild(tbody);

	if(fileCount > 0)
		attendees.appendChild(table);
}
function deleteAttendee(id)
{
	var attendees = EBID('attendees'),
		attendeeList = attendees.value.split(';'),
		newAttendeeList = '';

	// remove entry from list
	for(var i=0; i<attendeeList.length; i++)
	{
		if(attendeeList[i] != '')
		{
			var params = attendeeList[i].split(',');
			if(params[0] != id)
				newAttendeeList += ';' + attendeeList[i];
		}
	}

	// rebuild table
	attendees.value = newAttendeeList;
	generateAttendeeList();
}
function addAttendee(sid)
{
	openOverlay('organizer.addressbook.php?sid=' + sid + '&action=attendeePopup&attendeeList=' + escape(EBID('attendees').value),
		lang['addattendee'],
		420,
		310,
		true);
}
function toggleRepeatingDiv(c)
{
	EBID('repeatingDiv').style.display = c.checked ? '' : 'none';
}
function checkCalendarDateForm(form)
{
	if(EBID('durationMinutes').value == '')
		EBID('durationMinutes').value = '0';
	if(EBID('durationHours').value == '')
		EBID('durationHours').value = '0';
	if(form.elements['title'].value.length < 2
		|| (EBID('wholeDay_0').checked
			&& ((isNaN(parseInt(EBID('durationHours').value)) || isNaN(parseInt(EBID('durationMinutes').value)))
				|| parseInt(EBID('durationHours').value) + parseInt(EBID('durationMinutes').value) < 1)))
	{
		alert(lang['fillin']);
		return(false);
	}
	return(true);
}
function checkCalendarGroupForm(form)
{
	if(form.elements['title'].value.length < 2)
	{
		alert(lang['fillin']);
		return(false);
	}
	return(true);
}
function updateCalendarViewMode(c, date, sid)
{
	document.location.href = 'organizer.calendar.php?sid=' + sid + '&view=' + c.value + '&date=' + date;
}
function updateCalendarGroup(c, date, sid)
{
	document.location.href = 'organizer.calendar.php?sid=' + sid + '&switchGroup=' + c.value + '&date=' + date;
}
function calendarDateClick(id)
{
	var parts = id.split('_');
	showCalendarDate(parts[1], parseInt(parts[2]), parseInt(parts[3]), true)
}
function initCalendar(resize)
{
	calDayBody = EBID('calendarDayBody');

	for(var i=0; i<calendarDates.length; i++)
	{
		var date = calendarDates[i],
			time = new Date((date[1]/*-getTZOffset()*/)*1000),
			hour = time.getHours(),
			minute = time.getMinutes();
			endTime = new Date((date[2]/*-getTZOffset()*/)*1000),
			endHour = endTime.getHours(),
			endMinute = endTime.getMinutes(),
			concurrentDates = [],
			myPos = 0,
			dayStr = date.length==6 ? date[5]+'_' : '';
		if(minute > 30)
		{
			hour++;
			minute = 0;
		}
		else if(minute > 0)
			minute = 30;
		if(endMinute > 30)
		{
			endHour++;
			endMinute = 0;
		}
		else if(endMinute > 0)
			endMinute = 30;
		var startRow = (hour*2+minute/30),
			endRow = (endHour*2+endMinute/30),
			addHeight = false;
		if(startRow < 0) startRow = 0;
		if(endRow < 0) endRow = 0;
		if(startRow > 47) startRow = 47;
		if(endRow > 47) { addHeight = true; endRow = 47; }

		// concurrent dates?
		for(var j=0; j<calendarDates.length; j++)
		{
			var cDate = calendarDates[j];
			if(i != j)
			{
				if((cDate[1] >= date[1] && cDate[1] <= date[2])
					|| (cDate[1] < date[1] && cDate[2] >= date[1]))
				{
					if(cDate[1] < date[1]
						|| (cDate[1] == date[1] && cDate[0] < date[0]))
						myPos++;
					concurrentDates.push(cDate);
				}
			}
		}

		var dateDivTop = parseInt(EBID('timeRow_' + dayStr + startRow).offsetTop) - 3,
			dateDivHeight = parseInt(EBID('timeRow_' + dayStr + endRow).offsetTop + (addHeight ? parseInt(EBID('timeRow_' + dayStr + endRow).offsetHeight) : 0)) - dateDivTop - 12,
			dateDivWidth = Math.floor(parseInt(EBID('timeRow_' + dayStr + startRow).offsetWidth) / (concurrentDates.length+1)),
			dateDivLeft = parseInt(EBID('timeRow_' + dayStr + startRow).offsetLeft) + myPos*(dateDivWidth);

		if(dayStr == '')
			dateDivWidth -= 20;
		else
			dateDivWidth -= 14;

		var dateDiv = EBID('date_' + date[0] + '_' + date[1] + '_' + date[2]);
		if(!dateDiv)
		{
			var dateDiv = document.createElement('div');
			dateDiv.id = 'date_' + date[0] + '_' + date[1] + '_' + date[2];
			dateDiv.className = 'calendarDate_' + date[4];
			dateDiv.onclick = function() { calendarDateClick(this.id); }
			dateDiv.appendChild(document.createTextNode(date[3]));
			dateDiv.style.position = 'absolute';
			calDayBody.appendChild(dateDiv);
		}

		dateDiv.style.left = dateDivLeft + 'px';
		dateDiv.style.top = dateDivTop + 'px';
		dateDiv.style.height = dateDivHeight + 'px';
		dateDiv.style.width = dateDivWidth + 'px';
	}

	if(!resize)
	{
		if(EBID('timeRow_0_'+calendarDayStart))
		{
			// week view
			var elem = EBID('timeRow_0_'+calendarDayStart);

			EBID('calendarDayBody').scrollTop = getElementMetrics(elem, 'y')
														- getElementMetrics(calDayBody, 'y')
														- 10;
		}
		else
		{
			// day view
			window.scrollBy(0, EBID('timeRow_'+calendarDayStart).offsetTop-10);
		}

		addEvent(window, 'resize', function() {
			initCalendar(true);
		});
	}
}
