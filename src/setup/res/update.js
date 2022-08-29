'use strict';

/*
 * b1gMail update client scripts
 * (c) 2021 Patrick Schlangen et al
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

const steps = [
  'prepare',
  'struct2',
  'config',
  'struct3',
  'resetcache',
  'optimize',
  'complete',
];
let step = -1;
let args = '';
let pos = 0;
let allQ = -1;

function EBID(f) {
  return document.getElementById(f);
}

function Log(txt) {
  const log = EBID('log');

  if (log.style.display == 'none') {
    log.style.display = '';
  }

  log.value = txt + '\n' + log.value;
}

function MakeXMLRequest(url, callback) {
  const xmlHTTP = new XMLHttpRequest();

  xmlHTTP.open('GET', url, true);
  xmlHTTP.onreadystatechange = function () {
    callback(xmlHTTP);
  };
  xmlHTTP.send(null);
}

function _stepStep(e) {
  if (e.readyState < 0 || e.readyState > 4) {
    Log('Error in HTTP-Request: ' + e.readyState + ' - Trying again in 10s');
    window.setTimeout(stepStep, 10000);
    return;
  }

  if (e.readyState !== 4) {
    return;
  }

  if (e.responseText.startsWith('ERROR:')) {
    Log(`An error has occurred: "${e.responseText}"`);
    return;
  }

  if (!e.responseText.startsWith('OK:')) {
    Log('An unknown error has occurred');
    return;
  }

  const response = e.responseText.substr(3);

  if (response == 'DONE') {
    stepInit(step + 1);
    return;
  }
  const numbers = response.split('/');
  if (numbers.length == 2) {
    if (steps[step] == 'struct2' && allQ == -1) {
      allQ = parseInt(numbers[1]);
    }

    if (steps[step] == 'struct2') {
      numbers[1] = '' + allQ;
    }

    pos = parseInt(numbers[0]);
    EBID('step_' + steps[step] + '_progress').innerHTML =
      '<b>' +
      Math.round((pos / parseInt(numbers[1])) * 100) +
      '%</b> <small>(' +
      pos +
      ' / ' +
      parseInt(numbers[1]) +
      ')</small>';
    stepStep();
  } else {
    Log('Unexpected response - skipping position ' + pos);
    pos++;
    stepStep();
  }
}

function stepStep() {
  MakeXMLRequest(
    'update.php?' + args + '&step=4&do=' + steps[step] + '&pos=' + pos,
    _stepStep,
  );
}

function stepInit(theStep) {
  if (step != -1) {
    EBID('step_' + steps[step] + '_status').innerHTML =
      '<img src="../admin/templates/images/ok.png" border="0" alt="" width="16" height="16" />';
    EBID('step_' + steps[step] + '_progress').innerHTML = '<b>100%</b>';
  }

  if (theStep < steps.length) {
    step = theStep;
    EBID('step_' + steps[step] + '_text').innerHTML =
      '<b>' + EBID('step_' + steps[step] + '_text').innerHTML + '</b>';
    EBID('step_' + steps[step] + '_status').innerHTML =
      '<img src="../admin/templates/images/load_16.gif" border="0" alt="" width="16" height="16" />';

    pos = 0;
    stepStep();
  } else {
    if (EBID('done')) {
      EBID('done').style.display = '';
    }
    if (EBID('next_button')) {
      EBID('next_button').disabled = false;
    }
  }
}

function beginUpdate() {
  stepInit(0);
}
