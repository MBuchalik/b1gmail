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

function loadMoreMails(folder, page) {
  $.ajax({
    type: 'POST',
    url: 'email.php',
    data: { sid: currentSID, listOnly: 'true', folder: folder, page: page },
    cache: false,
    dataType: 'text',
  }).done(function (msg) {
    $('#mailList').append(msg);
    $('#mailList').listview('refresh');
    if (msg.indexOf('<!-- hideMoreMailsLink -->') >= 0)
      $('#moreMailsLink').remove();
  });
}
