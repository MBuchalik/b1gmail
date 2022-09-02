<?php
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

// b1gMailLang::English::en_US.UTF-8|en_US|en_GB.UTF-8|en_GB|english|en|us::en

/**
 * Client phrases
 */
$lang_client['certmailwarn'] =
    "You're trying to send a certified email.\n\nCertified emails do not support the following options\nyou chose.\n\nThese options will be disabled if you decide to continue.\n\n";
$lang_client['certmailsign'] = 'Sign';
$lang_client['certmailencrypt'] = 'Encrypt';
$lang_client['certmailconfirm'] = 'Request receipt';
$lang_client['addravailable'] = 'Address available!';
$lang_client['addrtaken'] = 'Address unavailable!';
$lang_client['addrinvalid'] = 'Address invalid!';
$lang_client['fillin'] = 'Please fill in all required fields!';
$lang_client['selecttext'] = 'Please select a text first!';
$lang_client['fillinname'] =
    'Please fill in at least first name and family name of the contact!';
$lang_client['reallyreset'] =
    'Are you sure you want to reset the form? Entries may be lost!';
$lang_client['addressbook'] = 'Addressbook';
$lang_client['browse'] = 'Browse';
$lang_client['importvcf'] = 'Import VCF';
$lang_client['import'] = 'Import';
$lang_client['userpicture'] = 'User picture';
$lang_client['addattach'] = 'Add attachment';
$lang_client['saveattach'] = 'Save attachment';
$lang_client['attachments'] = 'Attachments';
$lang_client['date'] = 'Date';
$lang_client['pricewarning'] =
    'To send this SMS you must have a balance of at least %1 Credit(s). Your current balance of %2 Credit(s) is insufficient. Please top up your account.';
$lang_client['switchwarning'] =
    'Switching from HTML-Mode to Text-Mode, all formatting will be lost. Are you sure you want to continue?';
$lang_client['folderprompt'] =
    'Please give the name of the folder to be created:';
$lang_client['newfolder'] = 'New folder';
$lang_client['foldererror'] = 'Folder could not be created.';
$lang_client['attendees'] = 'Attendees';
$lang_client['addattendee'] = 'Add attendees';
$lang_client['newpatches'] =
    'New patches are available via the automatic update system.';
$lang_client['protectedfolder'] = 'Protected folder';
$lang_client['source'] = 'Source';
$lang_client['sendwosubject'] =
    'You did not enter a subject for your email. Click "Cancel", to enter a subject or click "OK" to send the email without a subject.';
$lang_client['movemail'] = 'Move email';
$lang_client['certificate'] = 'Certificate';
$lang_client['addcert'] = 'Import certificate';
$lang_client['exportcert'] = 'Export certificate';
$lang_client['unknown'] = 'Unknown';
$lang_client['version'] = 'Version';
$lang_client['prefs'] = 'Preferences';
$lang_client['realdel'] = 'Do you really want to delete these datasets?';
$lang_client['nomailsselected'] = 'No email selected';
$lang_client['mailsselected'] = 'emails selected';
$lang_client[
    'attwarning'
] = 'The text of your email indicates that you wanted to add an attachment, but your email does not contain any attachment.

In case you forgot to add the attachment, please click \"Cancel\" and add the attachments.

Otherwise, please click \"OK\" to continue to send the email.';
$lang_client['viewoptions'] = 'View options';
$lang_client['compose'] = 'Compose';
$lang_client['uploading'] = 'Upload';
$lang_client['export'] = 'Export';
$lang_client['groups'] = 'Groups';
$lang_client['nwslttrtplwarn'] =
    'Are your sure you want to apply a new template? All your input will be lost!';
$lang_client['items'] = 'Items';
$lang_client['cancel'] = 'Cancel';
$lang_client['nocontactselected'] = 'No contact selected';
$lang_client['contactsselected'] = 'contacts selected';
$lang_client['checkingaddr'] = 'Checking availability...';
$lang_client['showsuggestions'] = 'Show suggestions';
$lang_client['pleasewait'] = 'Please wait...';
$lang_client['deliverystatus'] = 'Delivery status';
$lang_client['taxnote'] = 'incl. %1% VAT';
$lang_client['decsep'] = ',';
$lang_client['lastsavedat'] = 'Last saved at %1:%2.';
$lang_client['statement'] = 'Account statement';

/**
 * Customizable phrases
 */
$lang_custom['welcome_sub'] = 'Thank you for your registration!';
$lang_custom['welcome_text'] =
    'Dear %%vorname%% %%nachname%%,' .
    "\n\n" .
    'thank you for registering with our service.' .
    "\n" .
    'Should you have any further questions, please do not hesitate to contact us. A list of frequently asked questions and answers is available by clicking on the"?" icon in the top right-hand corner.' .
    "\n\n" .
    '(This email has been generated automatically)';
$lang_custom['tos'] =
    'Please customize the Terms of Service (TOS).<br /><br />You will find it in the <a href="./admin/">administration panel</a> under <i>"Settings" - "Languages" - "Customizable Texts"</i>. This can be done for any language installed.';
$lang_custom['privacy_policy'] =
    'Please customize the Privacy Policy.<br /><br />You will find it in the <a href="./admin/">administration panel</a> under <i>"Settings" - "Languages" - "Customizable Texts"</i>. This can be done for any language installed.';
$lang_custom['imprint'] =
    'Please customize the imprint.<br /><br />You will find it in the <a href="./admin/">administration panel</a> under <i>"Settings" - "Languages" - "Customizable Texts"</i>. This can be done for any language installed.';
$lang_custom['maintenance'] =
    'We are currently undergoing some scheduled maintanance to our system in order to improve our service. Unfortunately we are currently not available for that reason. We apologize for any inconvenience.';
$lang_custom['selfcomp_n_sub'] = 'Addressbook entry completed';
$lang_custom['selfcomp_n_text'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    '%%vorname%% %%nachname%% has just accepted your invitation to complete his/her addressbook entry himself/herself. The updated contact details have been copied into your addressbook.' .
    "\n\n" .
    '(This message has been generated automatically.)';
$lang_custom['selfcomp_sub'] =
    'Your entry in %%vorname%% %%nachname%%\'s addressbook';
$lang_custom['selfcomp_text'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    '%%vorname%% %%nachname%% added you to his/her addressbook and is asking you to complete your contact details.' .
    "\n\n" .
    'Please click the following link to confirm and complete your contact details in %%vorname%% %%nachname%%\'s addressbook.' .
    "\n\n" .
    '%%link%%' .
    "\n\n" .
    'Thank you in advance!' .
    "\n\n" .
    '(This message has been generated automatically on behalf of %%vorname%% %%nachname%%)';
$lang_custom['passmail_sub'] = 'Forgot password';
$lang_custom['passmail_text'] =
    'Dear %%vorname%% %%nachname%%,' .
    "\n\n" .
    'a password request has been requested for your account %%mail%%.' .
    "\n\n" .
    'Your new password is: %%passwort%%' .
    "\n\n" .
    'Please click the following link to activate your new password:' .
    "\n\n" .
    '%%link%%' .
    "\n\n" .
    'After clicking the link you can log in using the password given above.' .
    "\n\n" .
    'CAUTION: When resetting your password, all saved private key passwords will become invalid. You will have to re-import all your private certificates!' .
    "\n\n" .
    '(This message has been generated automatically)';
$lang_custom['certmail'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    '%%user_name%% (%%user_mail%%) sent you a certified message. To get the message and forward it to your email account, please click the following link or paste it into your browser.' .
    "\n\n" .
    '%%url%%' .
    "\n\n" .
    'Please not that this message will only be stored until %%date%% after which time it will expire.' .
    "\n\n" .
    '(This message has been generated automatically)';
$lang_custom['mail2sms'] = 'New email from %%abs%%: %%betreff%%';
$lang_custom['cs_subject'] = 'Delivery receipt';
$lang_custom['cs_text'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    'the certified message you sent to %%an%% with the subject %%subject%% has just been read (%%date%%).' .
    "\n\n" .
    '(This message has been generated automatically)';
$lang_custom['clndr_subject'] = 'Calendar reminder: %%title%%';
$lang_custom['clndr_date_msg'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    'we would like to remind you of the following event: "%%title%%".' .
    "\n" .
    'It is scheduled for %%date%% at %%time%% o\'clock.' .
    "\n" .
    'Notification: %%message%%' .
    "\n\n" .
    '(This message has been generated automatically)';
$lang_custom['clndr_sms'] = '%%date%% %%time%% - %%subtitle%%';
$lang_custom['receipt_text'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    'I have just read your message with the subject heading "%%subject%%" (%%date%%).' .
    "\n\n" .
    '(This message has been generated automatically)';
$lang_custom['alias_sub'] = 'Confirm alias setup';
$lang_custom['alias_text'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    '%%email%% has just added your email address %%aliasemail%% as sender in his email account.' .
    "\n" .
    'The setup must be confirmed by clicking the following link. After clicking the link the you will be able to use your email address as the sender\'s address of the following account: %%email%% ' .
    "\n" .
    'If you do not want to use your email address as the sender\'s address, DO NOT click the link and erase this message.' .
    "\n\n" .
    'Confirmation Link:' .
    "\n" .
    '	%%link%%' .
    "\n\n" .
    '(This message has been generated automatically)';
$lang_custom['snotify_sub'] = 'New b1gMail sign-up (%%datum%%)';
$lang_custom['snotify_text'] =
    'Someone just signed up with your b1gMail-based mail service:' .
    "\n\n" .
    'Email: %%email%%' .
    "\n" .
    'Domain: %%domain%%' .
    "\n" .
    'Name: %%name%%' .
    "\n" .
    'Street Address: %%strasse%%' .
    "\n" .
    'Postcode/City: %%plzort%%' .
    "\n" .
    'Country: %%land%%' .
    "\n\n" .
    'Phone: %%tel%%' .
    "\n" .
    'Fax: %%fax%%' .
    "\n" .
    'Alternative email: %%altmail%%' .
    "\n\n" .
    'Details: %%link%%';
$lang_custom['validationsms'] =
    'Thank you for signing up! Your unlock code: %%code%% - just enter it when requested!';
$lang_custom['validationsms2'] =
    'Please enter the following unlock code at "SMS" in order to finish the change of your number: %%code%%';
$lang_custom['activationmail_sub'] = 'Your registration (%%email%%)';
$lang_custom['activationmail_text'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    'thank you for signing up with our service. In order to activate your new email address %%email%%, just click the following link or enter the following activation code when requested during your first login.' .
    "\n\n" .
    '	Link: %%url%%' .
    "\n" .
    '	Code: %%activationcode%%' .
    "\n\n" .
    '(This message has been generated automatically)';
$lang_custom['paynotify_sub'] = 'Order activated';
$lang_custom['paynotify_text'] =
    'An order has just been activated:' .
    "\n\n" .
    'Order: #%%order_id%%' .
    "\n" .
    'User: #%%user_id%%' .
    "\n" .
    'Invoice no: %%invoice_no%%' .
    "\n" .
    'Customer no: %%customer_no%%' .
    "\n" .
    'Payment method: %%payment_method%%' .
    "\n" .
    'Order amount: %%order_amount%%' .
    "\n" .
    'Paid amount: %%paid_amount%%' .
    "\n" .
    'Transaction code: %%txn_id%%';
$lang_custom['orderconfirm_sub'] = 'Yor order (%%invoice_no%%)';
$lang_custom['orderconfirm_text'] =
    'Dear Sir or Madam,' .
    "\n\n" .
    'thank you for your order. We have gratefully received your payment and activated the order.' .
    "\n" .
    'You can find the details of your order (including you invoice, if available) at \"Preferences\" -> \"Orders\".' .
    "\n" .
    '(This message has been generated automatically)';
$lang_custom['share_sub'] = 'Webdisk share';
$lang_custom['share_text'] =
    'Hi,' .
    "\n\n" .
    'you can find my Webdisk share under the following address:' .
    "\n" .
    "\t" .
    '%%url%%' .
    "\n\n" .
    'Best Regards,' .
    "\n\n" .
    '%%firstname%% %%lastname%%';
$lang_custom['ap_autolock_sub'] =
    'User %%email%% locked because of suspicious activity';
$lang_custom['ap_autolock_text'] =
    'The user %%email%% (#%%id%%) has been locked automatically because the abuse protect point limit has been exceeded.' .
    "\n\n" .
    'Email: %%email%% (#%%id%%)' .
    "\n" .
    'Points: %%pointsum%%' .
    "\n\n" .
    'Point statement:' .
    "\n" .
    '------------------------------------------------------------------------' .
    "\n" .
    '%%points%%' .
    "\n" .
    '------------------------------------------------------------------------' .
    "\n\n" .
    'Details: %%link%%';
$lang_custom['contact_subjects'] =
    'Question about the offer' .
    "\n" .
    'Question about the signup process' .
    "\n" .
    'Question about logging in' .
    "\n" .
    'Lost password' .
    "\n" .
    'Other questions';
$lang_custom['notify_date'] = 'Appointment: <strong>%s</strong>';
$lang_custom['notify_newemail'] = '<strong>%d</strong> new email(s): %s';
$lang_custom['notify_email'] = 'Email received from <strong>%s</strong>: %s';
$lang_custom['notify_birthday'] =
    'Today <strong>%s</strong> turned <strong>%d years</strong>!';

/**
 * User phrases
 */
$lang_user['weekdays'] = 'SMTWTFS'; // sunday through saturday
$lang_user['weekdays_long'] = ['So', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']; // sunday through saturday
$lang_user['full_weekdays'] = [
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
];

$lang_user['pleasechose'] = 'Please choose';
$lang_user['suggestions'] = 'Suggestions';
$lang_user['nosuggestions'] = 'We could not find any suggestions.';
$lang_user['suggestions_desc'] =
    'We have determined the following suggestions for your new email address. All shown addresses are still available:';
$lang_user['choose'] = 'Choose';
$lang_user['nothanks'] = 'No thanks';
$lang_user['contactform'] = 'Contact form';
$lang_user['message'] = 'Message';
$lang_user['cform_senderror'] =
    'Your message could not be sent. Please try again later.';
$lang_user['cform_sent'] =
    'We have received your inquiry and will get back to you as soon as possible.';
$lang_user['nonotifications'] = 'no current notifications';
$lang_user['notifications'] = 'Notifications';
$lang_user['relevance'] = 'Relevance';
$lang_user['minchars'] = 'min. %d chars';
$lang_user['deliverystatus'] = 'Delivery status';
$lang_user['mds_delivered'] = 'Delivered to <strong>%d</strong> recipient(s).';
$lang_user['mds_deferred'] =
    'Delivery to <strong>%d</strong> recipient(s) delayed.';
$lang_user['mds_failed'] =
    'Delivery to <strong>%d</strong> recipient(s) failed.';
$lang_user['mds_processing'] = 'Delivering';
$lang_user['mds_recp_processing'] = 'Delivering';
$lang_user['mds_recp_delivered'] = 'Delivered';
$lang_user['mds_recp_deferred'] = 'Delivering (delayed)';
$lang_user['mds_recp_failed'] = 'Delivery failed';
$lang_user['recipient'] = 'Recipient';
$lang_user['newgroup'] = 'New group';
$lang_user['associatewith'] = 'Associate with';
$lang_user['mails_del'] = 'Delete emails';
$lang_user['auto_save_drafts'] = 'Automatically save drafts';
$lang_user['notify_sound'] = 'Play sound';
$lang_user['notify_types'] = 'Notify me about';
$lang_user['notify_email'] = 'new emails';
$lang_user['notify_birthday'] = 'birthdays of my contacts';
$lang_user['auto'] = 'Automatic';
$lang_user['details_default'] = 'Directly show detailed results';
$lang_user['statement'] = 'Account statement';
$lang_user['description'] = 'Description';
$lang_user['current'] = 'current';
$lang_user['balance'] = 'Balance';
$lang_user['dynamicbalance'] = 'Remaining monthly credits';
$lang_user['startingbalance'] = 'Starting balance';
$lang_user['tx_charge'] = 'Account charge (%s)';
$lang_user['tx_coupon'] = 'Voucher (%s)';
$lang_user['tx_sms'] = 'SMS';
$lang_user['langCode_editor'] = 'en';
$lang_user['sendnotify'] = 'Show notification';
$lang_user['readonly'] = 'read only';
$lang_user['sharedfolders'] = 'Shared folders';
$lang_user['years'] = 'year(s)';
$lang_user['loaddraft'] = 'Load draft';
$lang_user['drafttext'] =
    'Do you want to load the auto-saved draft of your last email?';
$lang_user['exceededsendlimit'] =
    'Sending this email would exceed your limit of %d email(s) in %d minute(s). Your email could not be sent.';
$lang_user['bynotify'] = 'by notification';

// phrases for new nli layout
$lang_user['home'] = 'Home';
$lang_user['plans'] = 'Plans';
$lang_user['required'] = 'required';
$lang_user['street'] = 'Street';
$lang_user['nr'] = 'No';
$lang_user['wishaddressandpw'] = 'Name, preferred email address and password';
$lang_user['accepttos'] = 'I agree to the';
$lang_user['completesignup'] = 'Complete signup';
$lang_user['next'] = 'Next';
$lang_user['pleasewait'] = 'Please wait...';
$lang_user['readcertmail'] = 'Read certified email';

// misc
$lang_user['menu'] = 'Menu';
$lang_user['fetching'] = 'Fetching';
$lang_user['nocontactselected'] = 'No contacts selected';
$lang_user['showmore'] = 'Show more';
$lang_user['paused'] = 'Paused';
$lang_user['langCode'] = 'EN';
$lang_user['skrill'] = 'Skrill (Moneybookers)';
$lang_user['pn_skrill'] =
    'The invoice amount (%.02f %s) has been gratefully received using Skrill (Moneybookers).';
$lang_user['pn_custom'] =
    'The invoice amount (%.02f %s) will be paid using %s.';
$lang_user['pn_customtext'] =
    'Thank you for your order. You have chosen to pay using %s. After our team has reviewed your order, it will be activated immediately.';
$lang_user['pop3server'] = 'Inbox server (POP3)';
$lang_user['undonetasks'] = 'Pending tasks';
$lang_user['donetasks'] = 'Done tasks';
$lang_user['tasklist'] = 'Task list';
$lang_user['tasklists'] = 'Task lists';
$lang_user['nodatesin31d'] = 'No appointments in the next 31 days.';
$lang_user['desktopversion'] = 'Desktop version';
$lang_user['new'] = 'New';
$lang_user['right'] = 'Right';
$lang_user['bottom'] = 'Bottom';
$lang_user['notice'] = 'Notice';
$lang_user['from2'] = 'from';
$lang_user['note'] = 'Note';
$lang_user['task'] = 'Task';
$lang_user['addraddtext'] =
    'At least one of the recipients of the email is not in your address book.  You can easily add these recipients to your address book. Just select the recipients you want to add to your address book, complete their names and click &quot;Save&quot.';
$lang_user['addradddone'] =
    'The address(es) has/have been added to your address book successfully.';
$lang_user['nomailsselected'] = 'No email selected';
$lang_user['markdone'] = 'Done';
$lang_user['unmarkdone'] = 'Not done';
$lang_user['marked'] = 'Marked';
$lang_user['unread'] = 'Unread';
$lang_user['markallasread'] = 'Mark all as read';
$lang_user['markallasunread'] = 'Mark all as unread';
$lang_user['downloadall'] = 'Download all';
$lang_user['folderactions'] = 'Folder actions';
$lang_user['mailsfromab'] = 'Mail sender in address book';
$lang_user['att_keywords'] = 'attached,attachment';
$lang_user['attcheck'] = 'Attachment notice';
$lang_user['attcheck_desc'] = 'Display notice in case of forgotten attachments';
$lang_user['sendmail3'] = 'Send';
$lang_user['maintenance'] = 'Maintenance';
$lang_user['search'] = 'Search';
$lang_user['nothingfound'] = 'No match.';
$lang_user['jswarning'] =
    'Please activate JavaScript. Otherwise you will not be able to use this service!';
$lang_user['imprint'] = 'Imprint';
$lang_user['tos'] = 'TOS';
$lang_user['privacy'] = 'Privacy';
$lang_user['privacy_policy'] = 'Privacy Policy';
$lang_user['mobilepda'] = 'Mobile';
$lang_user['mobiledenied'] =
    'You are not authorized to use the mobile interface.';
$lang_user['workgroup'] = 'Workgroup';
$lang_user['workgroups'] = 'Workgroups';
$lang_user['groups'] = 'Groups';
$lang_user['search2'] = 'Search';
$lang_user['searchfor'] = 'Search for';
$lang_user['searchin'] = 'Search in';
$lang_user['datefrom'] = 'from';
$lang_user['dateto'] = 'to';
$lang_user['details'] = 'Details';
$lang_user['sess_expired'] = 'Session expired';
$lang_user['sess_expired_desc'] =
    'Your session has been closed because of inactivity for security reasons. Please re-login.';
$lang_user['doublealtmail'] =
    'There is already an account using this alternative email address!';
$lang_user['doublecellphone'] =
    'There is already an account using this cellphone number!';
$lang_user['realdel_order'] =
    'Do you really want to delete the order? If you have already paid for the order, we will not be able to assign your payment to you. Please delete orders only if you have not paid yet!\n\nIf you like to cancel an order you already paid for, please contact our support team.\n\nClick OK if you really want to cancel the order or Cancel to keep the order.';
$lang_user['hiddenelements'] = 'Hidden elements';
$lang_user['hide'] = 'Hide';
$lang_user['atreply'] = 'At reply';
$lang_user['insertquote'] = 'Quote original email';
$lang_user['invoiceaddress'] = 'Invoice address';
$lang_user['paymentmethod'] = 'Payment method';
$lang_user['finalamount'] = 'Payment amount';
$lang_user['placeorder'] = 'Submit order';
$lang_user['banktransfer'] = 'Wire transfer';
$lang_user['taxnote'] = 'incl. %.02f%% VAT';
$lang_user['pn_paypal'] =
    'The invoice amount (%.02f %s) has been gratefully received using PayPal.';
$lang_user['pn_banktransfer'] =
    'Please transfer the invoice amount (%.02f %s) to our bank account (see below). Please use the following code in the subject - otherwise we cannot process your payment: <b>VK-%s</b>.<br />As soon as we receive your payment, your order will be activated.';
$lang_user['pn_sofortueberweisung'] =
    'The invoice amount (%.02f %s) has been gratefully received using sofortueberweisung.de.';
$lang_user['orders'] = 'Orders';
$lang_user['order'] = 'Order';
$lang_user['thankyou'] = 'Thank you';
$lang_user['paymentreturn_txt'] =
    '<p>Thank you for your payment.</p><p>In case your payment was successful, your order will be activated as soon as the payment is confirmed. In most cases this takes only a few seconds.</p><p>You can find your current order status at \"<a href=\"prefs.php?action=orders&sid=%s\">Orders</a>\" at \"Preferences\" anytime.';
$lang_user['prefs_d_orders'] =
    'Review your orders and download or print your invoices.';
$lang_user['orderno'] = 'Order no';
$lang_user['amount'] = 'Amount';
$lang_user['invoice'] = 'Invoice';
$lang_user['printinvoice'] = 'Print invoice';
$lang_user['orderalreadypaid'] =
    'This order has already been paid or has been cancelled.';
$lang_user['yourinvoice'] = 'Your invoice';
$lang_user['dearsirormadam'] = 'Dear Sir or Madam';
$lang_user['pos'] = 'Position';
$lang_user['descr'] = 'Description';
$lang_user['ep'] = 'Unit price';
$lang_user['gp'] = 'Price';
$lang_user['gb'] = 'Total amount';
$lang_user['vat'] = 'VAT';
$lang_user['net'] = 'net';
$lang_user['gross'] = 'gross';
$lang_user['kindregards'] = 'Kind regards';
$lang_user['invtext'] = 'please find your invoice below';
$lang_user['invoiceno'] = 'Invoice no';
$lang_user['customerno'] = 'Customer no';
$lang_user['bankacc'] = 'Bank account';
$lang_user['invfooter'] =
    'This invoice has been generated automatically and is valid without a signature.';
$lang_user['kto_inh'] = 'Account owner';
$lang_user['kto_nr'] = 'Account no';
$lang_user['kto_blz'] = 'Bank code';
$lang_user['kto_inst'] = 'Bank name';
$lang_user['kto_iban'] = 'IBAN';
$lang_user['kto_bic'] = 'BIC/SWIFT code';
$lang_user['kto_subject'] = 'Subject';
$lang_user['pay'] = 'Pay';
$lang_user['completed'] = 'Completed';
$lang_user['setmailcolor'] = 'Set color';
$lang_user['hotkeys'] = 'Hotkeys';
$lang_user['to3'] = 'to';
$lang_user['with'] = 'with';
$lang_user['selectdraft'] = 'Select draft';
$lang_user['pop3ownerror'] = 'You cannot fetch the mailbox into itself.';
$lang_user['hidecustomfolders'] = 'Hide user defined folders';
$lang_user['hideintellifolders'] = 'Hide intelligent folders';
$lang_user['hidesystemfolders'] = 'Hide system folders';
$lang_user['sendmail2'] = 'Send email';
$lang_user['color_0'] = 'None';
$lang_user['color_1'] = 'Blue';
$lang_user['color_2'] = 'Green';
$lang_user['color_3'] = 'Red';
$lang_user['color_4'] = 'Orange';
$lang_user['color_5'] = 'Purple';
$lang_user['color_6'] = 'Violet';
$lang_user['colors'] = 'Colors';
$lang_user['sendsms2'] = 'Send SMS';
$lang_user['targetfolder'] = 'Destination folder';
$lang_user['existingfiles'] = 'Existing files';
$lang_user['zipfile'] = 'ZIP file';
$lang_user['deleteafterextract'] = 'Delete after extraction';
$lang_user['keep'] = 'Retain';
$lang_user['overwrite'] = 'Overwrite';
$lang_user['extract'] = 'Extract';
$lang_user['altmaillocked'] =
    'The email address you entered is not authorized for signing up with our service. Please use another email address.';

// webdisk share
$lang_user['badshare'] = 'Share path not found.';
$lang_user['protected_desc'] =
    'The folder is password protected. Please enter the password and click &quot;OK&quot; to continue.';
$lang_user['folder_wrongpw'] = 'The password you entered is not correct.';

// faq
$lang_user['faq'] = 'FAQ';
$lang_user['faqtxt'] =
    'Here you find answers to frequently asked questions. Often this is a convenient way to find answers to your questions. Should your question not be answered, <a href="index.php?action=imprint"> please do not hesitate to contact us</a>.';

// lost password
$lang_user['lostpw'] = 'Lost password';
$lang_user['requestpw'] = 'Password request';
$lang_user['pwresetfailed'] =
    'The user with the given email address was not found or did not give an alternative email address necessary to be assigned a new password. Please check your entry and try again.<br /><br /> In case of doubt, please <a href="index.php?action=imprint"> contact us to have your password restored.';
$lang_user['pwresetsuccess'] =
    'A new password for your account has been generated and sent to the alternative email address you stated in your profile. To complete the activation of your new password, please click the link in the email message. After clicking the link, you will be able to use the new password.';
$lang_user['pwresetfailed2'] =
    'The new password could not be activated because the confirmation link was not opened correctly or because the new password had already been activated. Please open the link exactly as you received it in the email message.<br /><br />In case of doubt <a href="index.php?action=imprint"> please contact us to have your password restored</a>.';
$lang_user['pwresetsuccess2'] =
    'Your new password has been activated successfully. You can now log in with your email address and your new password <a href="index.php"> </a>.';

// sign up
$lang_user['wishaddress'] = 'Preferred address';
$lang_user['signup'] = 'Sign up';
$lang_user['signuptxt'] =
    'Please fill out the following form to register for a free email address.';
$lang_user['signuptxt_code'] =
    'If you have voucher codes, enter them in the field provided - if not, just leave the field blank.';
$lang_user['notmember'] = 'Not a member yet';
$lang_user['notmembertxt'] =
    'Get your free email address and benefit from our special features. <a href="index.php?action=signup"> Click here</a> to sign-up free of charge!';
$lang_user['contactinfo'] = 'Contact Details';
$lang_user['firstname'] = 'First name';
$lang_user['surname'] = 'Surname';
$lang_user['streetnr'] = 'Street / no';
$lang_user['zipcity'] = 'Zip / city';
$lang_user['zip'] = 'Zip';
$lang_user['city'] = 'City';
$lang_user['phone'] = 'Phone';
$lang_user['fax'] = 'Fax';
$lang_user['altmail'] = 'Email';
$lang_user['altmail2'] = 'Alternative email';
$lang_user['repeat'] = 'Repeat';
$lang_user['security'] = 'Security';
$lang_user['code'] = 'Voucher code';
$lang_user['submit'] = 'Submit';
$lang_user['iprecord'] =
    'Your IP address will be recorded to prevent fraudulent requests.';
$lang_user['safecode'] = 'Safe code';
$lang_user['notreadable'] =
    'Unreadable? Click the safe code to generate a new one.';
$lang_user['misc'] = 'Miscellaneous';
$lang_user['tosaccept'] = 'I accept the terms of use';
$lang_user['tosnaccept'] = 'I do not accept the terms of use';
$lang_user['country'] = 'Country';
$lang_user['checkfields'] = 'Please check the fields marked red and try again.';
$lang_user['pwerror'] =
    'Your password is too short (&lt; 4 characters), is too similar to your user name or does not match the password repeat.';
$lang_user['plzerror'] = 'The postcode does not match the city.';
$lang_user['toserror'] =
    'You have to accept the terms of use before you can register.';
$lang_user['regerror'] =
    'Registration failed for unknown reasons. Please try again later.';
$lang_user['regdone'] =
    'Congratulations, you have registered successfully! You can now log in with your new email address <i>%s</i> and your password <a href="index.php"> </a> and use your account.';
$lang_user['regdonelocked'] =
    'Congratulations, you have registered successfully! Your account with the email address <i>%s</i> has now to be activated by an administrator before you can <a href="index.php"> log in </a> and use your account. This may take one or two workdays.';
$lang_user['reglock'] =
    'We are sorry but you cannot register at the moment because there was a registration from your IP address a short time ago. Please try again later.';
$lang_user['reglockdnsbl'] =
    'We are sorry but you cannot register at the moment because your IP address is blocked. Please try again later or contact our support team.';
$lang_user['regdisabled'] =
    'We are sorry but we cannot accept new registrations at the moment. Please try again later.';
$lang_user['signupcouponerror'] =
    'The voucher code is invalid or it has expired. Please correct your entry or leave the field blank.';

// address book completion
$lang_user['addrselfcomplete'] = 'addressbook completion';
$lang_user['completeerr'] =
    'You have already completed your addressbook entry or the link is invalid.';
$lang_user['completeintro'] =
    'Thank you for taking the time to check the following information and completing it (if necessary). Once you have finished, please click the &quot;Speichern&quot;-Button to save the information in the user\'s address book.<br /><i>Hinweis:</i> Changing/Completing the information at a later point of time will only be possilbe after a new invitation from the user.';
$lang_user['completeok'] =
    'Thank you for completing the information! The user has been informed of the update.';

// cert mails
$lang_user['certmailerror'] =
    'The link for the certified message is invalid or the certified message has expired or the certified message has been deleted.';

// login
$lang_user['pwcrypted'] =
    'The transmission of your password is safely encoded.';
$lang_user['welcomeback'] = 'Welcome back, <i>%s</i>.';
$lang_user['otheruser'] = 'Change account';
$lang_user['loginblocked'] =
    'Due to too many failed log-in attempts, your account will remain locked until <i>%s</i>. If you have any questions, please contact us.';
$lang_user['badlogin'] =
    'The password you entered does not match the password stored in our system. Please try again. This is your <b>%d.</b> failed log-in attempt in succession. After <b>5</b> failed log-in attempts your account will be locked for a short period of time for safety reasons.';
$lang_user['baduser'] =
    'The email address you entered is not known to our system. Please try again and make sure the spelling of the email-address is correct.';
$lang_user['userlocked'] =
    'Your email address has been locked or has not been activated yet. If you have any questions, please contact us.';
$lang_user['login'] = 'Login';
$lang_user['email'] = 'Email';
$lang_user['password'] = 'Password';
$lang_user['language'] = 'Language';
$lang_user['savelogin'] = 'Remember login';
$lang_user['ssl'] = 'Secure SSL login';
$lang_user['smsvalidation'] = 'Activate account';
$lang_user['smsvalidation_text'] =
    'Welcome! We sent a email / SMS containing your activation code to the email address / cellphone number you entered during sign up. Please enter the activation code to proceed.';
$lang_user['validationcode'] = 'Activation code';
$lang_user['validation_ok'] =
    'Your account has been activated successfully. You can now <a href="index.php">log in</a>.';
$lang_user['validation_err'] =
    'We could not activate your account. Either the account is already activated or you did not call the activation link properly.';
$lang_user['didnotgetcode'] = 'Did not get your activation code?';
$lang_user['resendcode'] = 'Re-send code';
$lang_user['coderesent'] = 'Your activation code has been re-sent.';
$lang_user['validation_resend_text'] =
    'You have %d activation code re-sends left.';
$lang_user['validation_count_limit'] =
    'You already requested resending of your activation code %d times. Please contact our support team if you encounter problems during account activation.';
$lang_user['validation_time_limit'] =
    'You can request a re-send of your activation code in %d:%02d minutes.';

// folders
$lang_user['folders'] = 'Folders';
$lang_user['inbox'] = 'Inbox';
$lang_user['outbox'] = 'Sent';
$lang_user['drafts'] = 'Drafts';
$lang_user['spam'] = 'Spam';
$lang_user['trash'] = 'Trash';

// modes
$lang_user['email'] = 'Email';
$lang_user['organizer'] = 'Organizer';
$lang_user['webdisk'] = 'Webdisc';
$lang_user['prefs'] = 'Settings';
$lang_user['start'] = 'Start page';

// mail th
$lang_user['mails'] = 'Emails';
$lang_user['from'] = 'From';
$lang_user['subject'] = 'Subject';
$lang_user['date'] = 'Date';
$lang_user['size'] = 'Size';

$lang_user['unknown'] = 'Unknown';
$lang_user['back'] = 'Back';
$lang_user['customize'] = 'Customize';
$lang_user['today'] = 'Today';
$lang_user['yesterday'] = 'Yesterday';
$lang_user['lastweek'] = 'Last week';
$lang_user['later'] = 'Later';
$lang_user['ok'] = 'OK';
$lang_user['error'] = 'Error';

// webdisk
$lang_user['dragfileshere'] = 'Simply drag and drop your files into this area.';
$lang_user['list'] = 'List';
$lang_user['icons'] = 'Icons';
$lang_user['viewmode'] = 'View';
$lang_user['dnd_upload'] = 'Drag&amp;Drop';
$lang_user['foldererror'] =
    'The folder could not be created. Either a folder by this name already exists or the name is invalid (minimum of 1 character).';
$lang_user['createfolder'] = 'Create folder';
$lang_user['uploadfiles'] = 'Upload files';
$lang_user['iteminfo'] = 'Details';
$lang_user['pleaseselectitem'] = 'Select a folder or a file.';
$lang_user['actions'] = 'Action';
$lang_user['count'] = 'Number';
$lang_user['droptext'] =
    'To upload files please drag them from the file manager and drop them into the folder.';
$lang_user['filename'] = 'File name';
$lang_user['size'] = 'Size';
$lang_user['created'] = 'Created';
$lang_user['internalerror'] = 'Internal Error - Please again later.';
$lang_user['success'] = 'Successful';
$lang_user['fileexists'] =
    'A file by this name already exists or this type of file is invalid.';
$lang_user['nospace'] = 'Not enough webspace';
$lang_user['nospace2'] =
    'Not enough webspace or internal error- the folder may not have been copied completely';
$lang_user['space'] = 'Space';
$lang_user['used'] = 'used';
$lang_user['unlimited'] = 'unlimited';
$lang_user['copy'] = 'Copy';
$lang_user['rename'] = 'Rename';
$lang_user['download'] = 'Download';
$lang_user['move'] = 'Move';
$lang_user['cut'] = 'Cut';
$lang_user['open'] = 'Open';
$lang_user['delete'] = 'Delete';
$lang_user['paste'] = 'Paste';
$lang_user['realdel'] = 'Delete entry irrevocably?';
$lang_user['realempty'] = 'Are you sure you want to empty the folder?';
$lang_user['sourcenex'] = 'Source was not found.';
$lang_user['notraffic'] = 'Not enough traffic for this operation';
$lang_user['traffic'] = 'Traffic';
$lang_user['sharing'] = 'Sharing';
$lang_user['shared'] = 'Shared';
$lang_user['share'] = 'Share';
$lang_user['folder'] = 'Folder';
$lang_user['save'] = 'Save';
$lang_user['saveas'] = 'Save as';
$lang_user['cancel'] = 'Cancel';
$lang_user['modified'] = 'Modified';
$lang_user['sharednote'] =
    'You currently share this folder. It is accessible under the following address:';

// organizer
$lang_user['calendar'] = 'Calendar';
$lang_user['overview'] = 'Overview';
$lang_user['todolist'] = 'To do';
$lang_user['addressbook'] = 'Addressbook';
$lang_user['notes'] = 'Notes';

// notes
$lang_user['edit'] = 'Edit';
$lang_user['text'] = 'Text';
$lang_user['priority'] = 'Priority';
$lang_user['prio_-1'] = 'Low';
$lang_user['prio_0'] = 'Normal';
$lang_user['prio_1'] = 'High';
$lang_user['clicknote'] =
    'Please click the text preview of a note in the list of notes to view the complete text.';
$lang_user['selaction'] = 'Action';
$lang_user['addnote'] = 'Add note';
$lang_user['editnote'] = 'Edit note';
$lang_user['reset'] = 'Reset';
$lang_user['markasdone'] = 'Mark as done';

// todo list
$lang_user['more'] = 'More';
$lang_user['tasks'] = 'Tasks';
$lang_user['addtask'] = 'Add task';
$lang_user['edittask'] = 'Edit task';
$lang_user['begin'] = 'Begin';
$lang_user['due'] = 'Due';
$lang_user['status'] = 'Status';
$lang_user['done'] = 'Done';
$lang_user['title'] = 'Title';
$lang_user['taskst_16'] = 'Not begun';
$lang_user['taskst_32'] = 'In Progress';
$lang_user['taskst_64'] = 'Done';
$lang_user['taskst_128'] = 'Deferred';
$lang_user['comment'] = 'Comment';

// addressbook
$lang_user['send_anyhow'] = 'Send anyhow';
$lang_user['convfolder'] = 'Create convers. folder';
$lang_user['addtogroup'] = 'Add to group';
$lang_user['addcontact'] = 'Add contact';
$lang_user['editcontact'] = 'Edit contact';
$lang_user['company'] = 'Company';
$lang_user['addcontact'] = 'Add contact';
$lang_user['editcontact'] = 'Edit contact';
$lang_user['all'] = 'All';
$lang_user['group'] = 'Group';
$lang_user['export_csv'] = 'Export (CSV)';
$lang_user['web'] = 'Web';
$lang_user['userpicture'] = 'User picture';
$lang_user['userpicturetext'] =
    'Please select the file (JPG, PNG oder GIF) you want to use as your user picture. Note that if you have already stored a picture in our system, that picture wil be replaced.';
$lang_user['changepicbyclick'] =
    'You can change your picture by clicking on it.';
$lang_user['groupmember'] = 'Group membership';
$lang_user['nogroups'] = 'No groups extant.';
$lang_user['mobile'] = 'Cellphone';
$lang_user['priv'] = 'Private';
$lang_user['work'] = 'Work';
$lang_user['address'] = 'Address';
$lang_user['common'] = 'Common';
$lang_user['default'] = 'Default';
$lang_user['salutation'] = 'Salutatory Address';
$lang_user['mrs'] = 'Mrs';
$lang_user['mr'] = 'Mr';
$lang_user['position'] = 'Position';
$lang_user['orderpos'] = 'Position';
$lang_user['features'] = 'Features';
$lang_user['birthday'] = 'Date of Birth';
$lang_user['importvcf'] = 'Import VCF';
$lang_user['exportvcf'] = 'Export VCF';
$lang_user['complete'] = 'Autocomplete';
$lang_user['importvcftext'] =
    'Please select the vCard (VCF-file) you want to import. Please note that by importing the file any data that you may have entered into the &quot;Kontakt hinzuf&uuml;gen&quot;-Mask will be replaced.';
$lang_user['localfile'] = 'Local file';
$lang_user['webdiskfile'] = 'Webdisc file';
$lang_user['completetext'] =
    'Using this feature, your contact can complete his contact details himself/herself. He/She will receive a message containing a link that will lead him/her to a website where he/she can enter his/her contact details. The information will be copied into your addressbook.<br /><br /> Please select the email address you want to send the link to.';
$lang_user['complete_noemail'] =
    'For this addressbook entry there is no email address stored in our system. Please give at least one valid email address to use this feature.';
$lang_user['complete_invited'] =
    'For this contact an update of the contact details has already been requested. You cannot send a new request before the old one has been accepted.';
$lang_user['complete_error'] =
    'The request could not be sent. Please check if the email address is valid and try again later.';
$lang_user['complete_ok'] =
    'The request has been sent. The user can now correct or complete his/her addressbook entry himself/herself. You will receive a message as soon as the user has responded to your request.';
$lang_user['members'] = 'Members';
$lang_user['add'] = 'Add';
$lang_user['import'] = 'Import';
$lang_user['export'] = 'Export';
$lang_user['groupexists'] = 'There is a group by that name already.';
$lang_user['editgroup'] = 'Edit group';
$lang_user['doexport'] = 'Export';
$lang_user['invalidpicture'] =
    'The picture you selected is invalid. Please use a JPG-, PNG-, or GIF-picture no bigger than %.02f KB.';
$lang_user['semicolon'] = 'Semicolon';
$lang_user['comma'] = 'Comma';
$lang_user['tab'] = 'Tab';
$lang_user['double'] = 'Double';
$lang_user['single'] = 'Single';
$lang_user['linebreakchar'] = 'Line break';
$lang_user['sepchar'] = 'Separator';
$lang_user['quotechar'] = 'Quotation marks';
$lang_user['advanced'] = 'Advanced';
$lang_user['type'] = 'Type';
$lang_user['csvfile'] = 'CSV-file';
$lang_user['encoding'] = 'Encoding';
$lang_user['vcfzipfile'] = 'ZIP-file with .VCF-files';
$lang_user['addrimporttext'] =
    'Please select the file you want to import into your addressbook. Make sure that the file is the same format as you selected under &quot;Typ&quot;.';
$lang_user['invalidformat'] =
    'The format of the file is unknown or the file is too big. Please try again.';
$lang_user['file'] = 'File';
$lang_user['association'] = 'Field Assignment';
$lang_user['existingdatasets'] = 'Existing datasets';
$lang_user['update'] = 'Update';
$lang_user['ignore'] = 'Ignore';
$lang_user['datasets'] = 'Datasets';
$lang_user['putingroups'] = 'Add new contacts to the following groups';
$lang_user['importdone'] =
    'Import completed. wurde abgeschlossen. %d datasets have been imported.';
$lang_user['pages'] = 'Pages';
$lang_user['contacts'] = 'Contacts';

// email
$lang_user['confirmationsent'] =
    'A read confirmation has been sent for this email.';
$lang_user['thisisadraft'] = 'This is email is a draft.';
$lang_user['editsend'] = 'Edit / send &raquo;';
$lang_user['conversation'] = 'Conversation';
$lang_user['conversationview'] = 'Conversation view';
$lang_user['unknownmessage'] = 'Unknown message';
$lang_user['bodyskipped'] =
    '(The body of the message cannot be displayed because the message is bigger than 64 KB)';
$lang_user['showsource'] = 'Show source';
$lang_user['moveto'] = 'move to';
$lang_user['sendmail'] = 'Send email';
$lang_user['folderadmin'] = 'Folder administration';
$lang_user['previewpane'] = 'Preview';
$lang_user['preview'] = 'Preview';
$lang_user['mail_read'] = 'Read email';
$lang_user['mail_del'] = 'Delete email';
$lang_user['mail_menu'] = 'Email options';
$lang_user['print'] = 'Print';
$lang_user['mail_menu'] = 'Email options';
$lang_user['reply'] = 'Reply';
$lang_user['replyall'] = 'Reply all';
$lang_user['forward'] = 'Forward';
$lang_user['redirect'] = 'Redirect';
$lang_user['flags'] = 'Flags';
$lang_user['markspam'] = 'Mark as spam';
$lang_user['marknonspam'] = 'Mark as non-spam';
$lang_user['markread'] = 'Mark as read';
$lang_user['markunread'] = 'Mark as unread';
$lang_user['mark'] = 'Mark';
$lang_user['unmark'] = 'Unmark';
$lang_user['to'] = 'To';
$lang_user['cc'] = 'Cc';
$lang_user['bcc'] = 'Bcc';
$lang_user['replyto'] = 'Reply to';
$lang_user['quotesel'] = 'Quote slected text';
$lang_user['searchsel'] = 'Search the internet for selected text';
$lang_user['attachments'] = 'Attachments';
$lang_user['approx'] = 'approx.';
$lang_user['savetowebdisk'] = 'Save to webdisc';
$lang_user['view'] = 'View';
$lang_user['toaddr'] = 'Add to addressbook';
$lang_user['read'] = 'Read';
$lang_user['flagged'] = 'Flagged';
$lang_user['answered'] = 'Answered';
$lang_user['forwarded'] = 'Forwarded';
$lang_user['attachment'] = 'Attachment';
$lang_user['group_mode'] = 'Group mode';
$lang_user['yes'] = 'Yes';
$lang_user['no'] = 'No';
$lang_user['props'] = 'Properties';
$lang_user['viewoptions'] = 'Display options';
$lang_user['mails_per_page'] = 'Messages per page';
$lang_user['htmlavailable'] = 'A HTML-Version of this message is available.';
$lang_user['noexternal'] =
    'Loading external/active content of this message has been blocked for safety reasons.';
$lang_user['showexternal'] = 'Enable external content';
$lang_user['emptyfolder'] = 'Empty folder';
$lang_user['refresh'] = 'Update';
$lang_user['spamtext'] = 'This message has been identified as spam.';
$lang_user['spamquestion'] = 'Is this message spam?';
$lang_user['isnotspam'] = 'Message is not spam &raquo;';
$lang_user['infectedtext'] =
    'The following virus has been detected in this email';
$lang_user['elapsed_seconds'] = ' (%d seconds ago)';
$lang_user['elapsed_minutes'] = ' (%d minutes ago)';
$lang_user['elapsed_hours'] = ' (%d hours ago)';
$lang_user['elapsed_days'] = ' (%d days ago)';
$lang_user['elapsed_second'] = ' (%d second ago)';
$lang_user['elapsed_minute'] = ' (%d minute ago)';
$lang_user['elapsed_hour'] = ' (%d hour ago)';
$lang_user['elapsed_day'] = ' (%d day ago)';
$lang_user['sendconfirmation'] = 'Send confirmation';
$lang_user['senderconfirmto'] =
    'The sender has requested a receiving confirmation.';
$lang_user['nomails'] = 'No messages.';
$lang_user['mailsent'] = 'The messages has been sent successfully.';
$lang_user['certmailinfo'] =
    'This is a certified message. If you delete it, the certified message will no longer be legible!';
$lang_user['signed'] = 'Digitally signed';
$lang_user['badsigned'] = 'Invalid digital signature';
$lang_user['noverifysigned'] = 'Not trustworthy signed';
$lang_user['encrypted'] = 'Encrypted';
$lang_user['decryptionfailed'] = 'Decryption failed';
$lang_user['movemailto'] = 'Move email to';

// prefs
$lang_user['autosend'] = 'Send automatically';
$lang_user['software_intro'] =
    'Here you can download %s &mdash; our software package for your PC or Mac. %s contains an email checker and several other tools to integrate our services into your desktop.';
$lang_user['software_win'] =
    'Here you can download our software for your Windows PC. Windows XP or higher is required. After downloading, you can start the installation by double-clicking the downloaded file.';
$lang_user['software_mac'] =
    'Here you can download our software for your Mac. OS X 10.7 or newer is required. After downloading, just extract the downloaded file and move the program to your Applications folder.';
$lang_user['defaults'] = 'Defaults';
$lang_user['options'] = 'Options';
$lang_user['nospamoverride'] = 'Override spam filter';
$lang_user['name'] = 'Name';
$lang_user['validto'] = 'Valid until';
$lang_user['contact'] = 'Contact';
$lang_user['antivirus'] = 'Anti-Virus';
$lang_user['antispam'] = 'Anti-Spam';
$lang_user['filters'] = 'Filters';
$lang_user['signatures'] = 'Signatures';
$lang_user['aliases'] = 'Aliases';
$lang_user['autoresponder'] = 'Autoresponder';
$lang_user['extpop3'] = 'POP3 accounts';
$lang_user['coupons'] = 'Vouchers';
$lang_user['software'] = 'Software';
$lang_user['membership'] = 'Membership';
$lang_user['validity'] = 'Validity';
$lang_user['certificate'] = 'Certificate';
$lang_user['chaincerts'] = 'Chain cert.';
$lang_user['cert_subject'] = 'Owner';
$lang_user['cert_issuer'] = 'Issuer';
$lang_user['organization'] = 'Organization';
$lang_user['organizationunit'] = 'Org. unit';
$lang_user['commonname'] = 'Common name';
$lang_user['state'] = 'State/Province';
$lang_user['version'] = 'Version';
$lang_user['serial'] = 'Serial number';
$lang_user['publickey'] = 'Public key';
$lang_user['bits'] = 'Bits';
$lang_user['keyring'] = 'Keyring';
$lang_user['prefs_d_keyring'] =
    'Manage your keys and certificates for email signing and crypting.';
$lang_user['prefs_d_faq'] =
    'Here you find answers to frequently asked questions.';
$lang_user['prefs_d_common'] =
    'Edit general settings of your account (e.g. reading and quoting options).';
$lang_user['prefs_d_contact'] =
    'Edit your contact details, which will also be used for your vCard.';
$lang_user['prefs_d_filters'] =
    'Sort incoming messages using self-defined filter rules.';
$lang_user['prefs_d_signatures'] =
    'Edit your email signatures or add new signatures.';
$lang_user['prefs_d_antivirus'] =
    'Protect your mailbox and your files using the integrated anti-virus-system.';
$lang_user['prefs_d_antispam'] =
    'Only allow messages you are sure you want to receive.';
$lang_user['prefs_d_aliases'] = 'Get more email addresses for this account.';
$lang_user['prefs_d_autoresponder'] =
    'Have your email responded to automatically, e.g. when you are not available.';
$lang_user['prefs_d_extpop3'] =
    'You can have external POP3-accounts collect your messages into your email-account.';
$lang_user['prefs_d_software'] =
    'Download client-software for your computer, e.g. a mail checker.';
$lang_user['prefs_d_membership'] =
    'View information on your membership or terminate it if desired.';
$lang_user['prefs_d_coupons'] =
    'Redeem the vouchers you may have obtained from special promotions.';
$lang_user['alias'] = 'Alias';
$lang_user['addalias'] = 'Add alias';
$lang_user['aliastype_1'] = 'Sender';
$lang_user['aliastype_2'] = 'Recipient';
$lang_user['aliastype_4'] = 'Not activated yet';
$lang_user['typ_1_desc'] =
    'Once you have created your alias, we will send a confirmation message to the address. As soon as the link in that message has been clicked, the alias can be used.';
$lang_user['aliasusage'] = '<b>%d</b> of <b>%d</b> alias(es) configured.';
$lang_user['addresstaken'] =
    'The email address you have given is taken or invalid. Please try again.';
$lang_user['toomanyaliases'] =
    'You have reached the maximum number of aliases. You have to delete an alias before you can configure a new one.';
$lang_user['addressinvalid'] =
    'The email address is invalid. Please try again.';
$lang_user['addressmustexist'] =
    'The email address does not exist. Addresses that have been configured as alias must be valid. Please try again.';
$lang_user['confirmalias'] =
    'The alias has been configured and a confirmation message has been sent to <b>%s</b>. Please open the link contained in the message within 7 days in order to activate the alias.';
$lang_user['confirmaliastitle'] = 'Confirm alias';
$lang_user['confirmaliasok'] =
    'The alias has been confirmed successfully and can now be used.';
$lang_user['confirmaliaserr'] =
    'The alias has already been confirmed, could not be found, or is invalid.';
$lang_user['credits'] = 'Credits';
$lang_user['charge'] = 'Charge account';
$lang_user['buynow'] = 'Buy now';
$lang_user['pay_using'] = 'Payment method';
$lang_user['paypal'] = 'PayPal';
$lang_user['su'] = 'Wire transfer (sofort&uuml;berweisung.de)';
$lang_user['charge_return'] =
    'Thank you for topping up your user account. As soon as the payment has been confirmed by the payment service provider, the credits you bought will be added to your balance. Usually that will only take a few seconds. On rare occasions, however, it may take longer(e.g. when your bank account is insufficiently funded).';
$lang_user['charge_min'] =
    'The minimum amount of credit required to be selected to top up your account is <b>%.02f %s</b>.';
$lang_user['charge_min_err'] =
    'The minimum amount of credit required to be selected to top up your account is <b>%.02f %s</b>. The amount you selected is only <b>%.02f %s</b>. Select at least <b>%d</b>.';
$lang_user['charge_desc'] =
    'Here you can top up your credit account. In the following field please give the number of credits you would like to purchase. Please click &quot;OK&quot;. Once you have checked the total price, click &quot;Jetzt kaufen&quot; to pay. The credits will be added to your balance shortly after you have paid.';
$lang_user['creditseach'] = 'Credits each <b>%.02f %s</b>';
$lang_user['charge2'] = 'Top up';
$lang_user['chargeitemname'] = 'Top up account (%d Credits)';
$lang_user['wgmembership'] = 'Memberships of workgroups';
$lang_user['membersince'] = 'Member since';
$lang_user['cancelmembership'] = 'Cancel membership';
$lang_user['canceltext'] =
    'Are you sure you want to cancel your membership? All data stored in our system will be lost and your email address will no longer be valid! This step is irreversible!';
$lang_user['cancelledtext'] =
    'You have cancelled your membership. Your account has been deactivated. Thank you for your interest in our service. We would be happy to welcome you again soon.';
$lang_user['enable'] = 'Enable';
$lang_user['enablebydefault'] = 'Enable by default';
$lang_user['spamfilter'] = 'Spam filter';
$lang_user['defensive'] = 'Defensive';
$lang_user['aggressive'] = 'Aggressive';
$lang_user['bayesborder'] = 'Filtering policy';
$lang_user['spamaction'] = 'Spam action';
$lang_user['block'] = 'Block';
$lang_user['spamindex'] = 'Training database';
$lang_user['entries'] = 'Entries';
$lang_user['resetindex'] = 'Reset database';
$lang_user['resetindextext'] =
    'Reset the database if the spam filter does not block incoming messages conveniently or if it blocks too many non-spam messages. After that you can re-train the spam filter by marking incoming messages as either spam or non-spam.';
$lang_user['unspamme'] = 'Selbst gesendete Mails';
$lang_user['applied'] = 'Applied';
$lang_user['addfilter'] = 'Add filter';
$lang_user['active'] = 'Active';
$lang_user['filterrequiredis'] = 'Apply filter to emails meeting';
$lang_user['editfilter'] = 'Edit filter';
$lang_user['stoprules'] = 'Skip other filters';
$lang_user['attachmentlist'] = 'List of attachments';
$lang_user['inboxrefresh'] = 'Refresh inbox';
$lang_user['every'] = 'every';
$lang_user['seconds'] = 'Seconds';
$lang_user['insthtmlview'] = 'Prefer HTML';
$lang_user['weekstart'] = 'Start of the week';
$lang_user['dateformat'] = 'Date format';
$lang_user['composeprefs'] = 'Sending otptions';
$lang_user['retext'] = 'Reply prefix';
$lang_user['fwdtext'] = 'Forward prefix';
$lang_user['defaultsender'] = 'Default sender';
$lang_user['sendername'] = 'Sender name';
$lang_user['receiveprefs'] = 'Receiving optionen';
$lang_user['forwarding'] = 'Forwarding';
$lang_user['to2'] = 'to';
$lang_user['deleteforwarded'] = 'Delete forwarded messages';
$lang_user['mail2sms'] = 'Mail-to-SMS';
$lang_user['redeemcoupon'] = 'Redeem voucher';
$lang_user['couponerror'] =
    'The voucher could not be redeemed. The voucher code may be incorrect or you may already have redeemed the voucher.';
$lang_user['couponok'] = 'The voucher was redeemed successfully.';
$lang_user['virusfilter'] = 'Virus filter';
$lang_user['virusaction'] = 'Virus action';
$lang_user['changepw'] = 'Change password';
$lang_user['addsignature'] = 'Add signature';
$lang_user['editsignature'] = 'Edit signature';
$lang_user['question'] = 'Question';
$lang_user['addpop3'] = 'Add POP3 account';
$lang_user['editpop3'] = 'Edit POP3 account';
$lang_user['toomanypop3'] =
    'You have reached the maximum number of POP3-Accounts. You have to delete an account before you can create a new one.';
$lang_user['pop3usage'] = '<b>%d</b> of <b>%d</b> account(s) created.';
$lang_user['username'] = 'Username';
$lang_user['host'] = 'Hostname';
$lang_user['lastfetch'] = 'Last fetch';
$lang_user['port'] = 'Port';
$lang_user['pop3target'] = 'POP3 target';
$lang_user['never'] = 'never';
$lang_user['keepmails'] = 'Keep messages on server';
$lang_user['pop3loginerror'] =
    'With the given login, no connection to the POP3 server could be established.';
$lang_user['sendsmsnotify'] = 'Send SMS notification';
$lang_user['plaintextcourier'] = 'Plaintext mails';
$lang_user['usecourier'] = 'Use font with fixed-width';
$lang_user['subscribe'] = 'Subscribe';
$lang_user['newsletter'] = 'Newsletter';
$lang_user['val24error'] =
    'You can change your cellphone number once each 24 hours only!';
$lang_user['addcert'] = 'Import certificate';
$lang_user['publiccerts'] = 'Public certificates';
$lang_user['owncerts'] = 'Own certificates';
$lang_user['addcerttext'] =
    'Please chose the public certificate (PEM format) that you would like to import. You can either upload a file from your local computer or chose a file from your webdisk (if available).';
$lang_user['certstoreerr'] =
    'The certificate cannot be imported. It is either invalid, not in PEM/PKCS12 format, not suitable for email purposes or is already existing in your keyring.';
$lang_user['requestcert'] = 'Request certificate';
$lang_user['addprivcerttext'] =
    'Please chose the private certificate (PEM format) you would like to import and the appropriate private key and enter the private key password in case the key is encrypted (recommended).';
$lang_user['addprivcert12text'] =
    'Please chose the private certificate / private key bundle (PKCS12 format; *.p12 / *.pfx) and enter the import password of the file.';
$lang_user['exportprivcerttext'] =
    'Please enter a password you would like to encrypt the exported data with.';
$lang_user['pkcs12file'] = 'PKCS12 file';
$lang_user['certexportpwerror'] =
    'The password is too short (< 4 characters) or the repitition does not match the password.';
$lang_user['certexporterror'] =
    'The certificate cannot be exported. Please try again later.';
$lang_user['key'] = 'Key';
$lang_user['privcertstoreerr'] =
    'The certificate cannot be imported. Either the password is wrong or ther certificate/key is invalid, do not fit, are not in PEM format or ar not suitable for email purposes or are already existing in your keyring.';
$lang_user['issuecert_noaddr'] =
    'Certificates for all of your email addresses / aliases are already available. We can only issue certificates for addresses you do not already have a certificate for.';
$lang_user['issuecert_addrdesc'] =
    'Please chose the email address the certificate should be issued for. The certificate is only usable with the chosen email address. You cannot chose email addresses for which you already have a certificate.';
$lang_user['issuecert_passdesc'] =
    'Please review your certificate request and enter your password to proceed.';
$lang_user['issuecert_wrongpw'] =
    'The password you entered is incorrect. Please use the same password you also use to log-in.';
$lang_user['issuecert_err'] =
    'Sorry, we cannot issue a certificate for you at this time for unknown reasons. Please try again later and contact us in case the problem persists.';

// start
$lang_user['welcome'] = 'Welcome';
$lang_user['welcometext'] = '<b>Welcome</b> to %s, %s!';
$lang_user['newmailtext'] = '<b>%d</b> unread messages';
$lang_user['datetext'] = '<b>%d</b> dates';
$lang_user['tasktext'] = '<b>%d</b> undone tasks';
$lang_user['newmailtext1'] = '<b>%d</b> unread message';
$lang_user['datetext1'] = '<b>%d</b> date';
$lang_user['tasktext1'] = '<b>%d</b> undone task';
$lang_user['websearch'] = 'Web search';
$lang_user['activewidgets'] = 'Active widgets';
$lang_user['quicklinks'] = 'Quick links';
$lang_user['logout'] = 'Logout';
$lang_user['logoutquestion'] = 'Are you sure you want to log out?';

// folders
$lang_user['sysfolders'] = 'System folders';
$lang_user['ownfolders'] = 'Own folders';
$lang_user['parentfolder'] = 'Parent folder';
$lang_user['subscribed'] = 'Subscribed';
$lang_user['storetime'] = 'Data retention period';
$lang_user['intelligent'] = 'Intelligent';
$lang_user['addfolder'] = 'Add new folder';
$lang_user['editfolder'] = 'Edit folder';
$lang_user['days'] = 'day(s)';
$lang_user['weeks'] = 'week(s)';
$lang_user['months'] = 'month(s)';
$lang_user['conditions'] = 'Condition(s)';
$lang_user['requiredis'] = 'Show email if it meets';
$lang_user['ofatleastone'] = 'at least one';
$lang_user['ofevery'] = 'every';
$lang_user['oftheseconditions'] = 'condition(s).';
$lang_user['isequal'] = 'is equal to';
$lang_user['isnotequal'] = 'is not equal to';
$lang_user['contains'] = 'contains';
$lang_user['notcontains'] = 'does not contain';
$lang_user['startswith'] = 'starts with';
$lang_user['endswith'] = 'ends with';

// compose
$lang_user['deletedraft'] = 'Delete draft';
$lang_user['linesep'] = 'Line boundary';
$lang_user['linesep_desc'] = 'Show line boundary when composing text mails';
$lang_user['blockedrecipients'] =
    'The following recipient(s) is/are blocked: <b>%s</b>. Please correct the recipient list and try again.';
$lang_user['invalidcode'] = 'invalid code.';
$lang_user['attachvc'] = 'Visiting card';
$lang_user['certmail'] = 'Certified mail';
$lang_user['mailconfirmation'] = 'Request receipt';
$lang_user['savecopy'] = 'Save copy to';
$lang_user['fromaddr'] = 'from address book';
$lang_user['plaintext'] = 'Plain text';
$lang_user['htmltext'] = 'HTML';
$lang_user['srcmsg'] = 'original message';
$lang_user['addattach'] = 'add attachment';
$lang_user['addattachtext'] =
    'Please select the file you would like to attach to your email and click &quot;OK&quot;. You can upload a file from your computer or use a file from your webdisc (if available).';
$lang_user['toobigattach'] =
    'The file you selected is too big. Please limit the total size of all email attachments to under %.02f KB.';
$lang_user['smartattach'] = 'smart attachment';
$lang_user['savedraft'] = 'Save as draft';
$lang_user['waituntil1'] = 'Please wait';
$lang_user['waituntil2'] = 'seconds to send the email.';
$lang_user['waituntil3'] =
    'To send another email, please wait <b>%d</b> seconds.';
$lang_user['norecipients'] =
    'You did not enter a valid recipient address. Please go back and try again.';
$lang_user['toomanyrecipients'] =
    'There is a maximum number of <b>%d</b> recipients per email. Selecting <b>%d</b> recipients for your email, you exceeded the limit. Please correct the number of recipients and try again.';
$lang_user['sendfailed'] =
    'email could not be sent. An unknown error occurred. Please try again later.';
$lang_user['sign'] = 'Sign';
$lang_user['encrypt'] = 'Encrypt';
$lang_user['smimeerr0'] = 'You did not enter a valid recipient.';
$lang_user['smimeerr1'] =
    'You have chosen to sign this message but you do not have a private certificate for the chosen sender address in your keyring.' .
    "\n\n" .
    'Please add a suitable certificate to your keyring (at "Preferences") and try again.';
$lang_user['smimeerr2'] =
    'You have chosen to encrypt this message but you do not have public certificates of one or more recipients in your keyring.' .
    "\n\n" .
    'Please add public certificates of the following recipients to your keyring and try again:';

// calendar
$lang_user['nodatesin6m'] = 'No appointments in the next 6 months.';
$lang_user['day'] = 'Day';
$lang_user['week'] = 'Week';
$lang_user['month'] = 'Month';
$lang_user['adddate'] = 'Add date';
$lang_user['nocalcat'] = '(no group)';
$lang_user['date2'] = 'Date';
$lang_user['close'] = 'Close';
$lang_user['attendees'] = 'Attendees';
$lang_user['none'] = 'none';
$lang_user['end'] = 'End';
$lang_user['location'] = 'Location';
$lang_user['repeating'] = 'Repeating';
$lang_user['reminder'] = 'Reminder';
$lang_user['editgroups'] = 'Edit groups';
$lang_user['mailattendees'] = 'Email to attendees';
$lang_user['btr'] = 'Concerning';
$lang_user['wholeday'] = 'whole day';
$lang_user['thisevent'] = 'original';
$lang_user['color'] = 'Color';
$lang_user['addgroup'] = 'Add group';
$lang_user['dates'] = 'Date(s)';
$lang_user['dates2'] = 'Dates';
$lang_user['duration'] = 'Duration';
$lang_user['hours'] = 'hour(s)';
$lang_user['minutes'] = 'minute(s)';
$lang_user['byemail'] = 'by email';
$lang_user['bysms'] = 'by SMS';
$lang_user['timeframe'] = 'Timeframe';
$lang_user['timebefore'] = 'before';
$lang_user['repeatoptions'] = 'Repeating options';
$lang_user['until'] = 'until';
$lang_user['times'] = 'times';
$lang_user['endless'] = 'endless';
$lang_user['repeatcount'] = 'Repeat';
$lang_user['interval'] = 'Interval';
$lang_user['besides'] = 'except for';
$lang_user['at'] = 'on';
$lang_user['ofthemonth'] = 'of the month';
$lang_user['first'] = 'first day';
$lang_user['second'] = 'second day';
$lang_user['third'] = 'third day';
$lang_user['fourth'] = 'fourth day';
$lang_user['last'] = 'last day';
$lang_user['editdate'] = 'edit day';
$lang_user['cw'] = 'calendar week';

// sms
$lang_user['sms'] = 'SMS';
$lang_user['sendsms'] = 'Send SMS';
$lang_user['smsoutbox'] = 'SMS outbox';
$lang_user['accbalance'] = 'Account balance';
$lang_user['price'] = 'Price (credits)';
$lang_user['chars'] = 'Characters';
$lang_user['smssent'] = 'Your SMS has been sent successfully.';
$lang_user['smssendfailed'] =
    'Your SMS could not be sent. You may not have enough balance to send this message or there may be a temporary internal error. Please try again later.';
$lang_user['smsvalidation2'] = 'Activate SMS functions';
$lang_user['smsvalidation2_text'] =
    'Please enter the activation code we sent to your cellphone.';
$lang_user['pleasevalidate'] =
    'Please enter your cellphone number at your contact details (at &quot;Preferences&quot;) in order to activate the SMS functions.';
$lang_user['invalidsmscode'] =
    'The activation code is invalid. Please double-check your input and try again.';

/**
 * Admin phrases
 */
$lang_admin['disabled'] = 'disabled';
$lang_admin['republickey'] = 'Site key';
$lang_admin['reprivatekey'] = 'Secret key';
$lang_admin['splashimage'] = 'Splash image';
$lang_admin['login_bg_1'] = 'Envelope tree';
$lang_admin['addtransaction'] = 'Add transaction';
$lang_admin['edittransaction'] = 'Edit transaction';
$lang_admin['booked'] = 'Booked';
$lang_admin['cancelled'] = 'Cancelled';
$lang_admin['transactions'] = 'Transactions';
$lang_admin['description'] = 'Description';
$lang_admin['cancel'] = 'Cancel';
$lang_admin['uncancel'] = 'Un-cancel';
$lang_admin['mail_groupmode'] = 'Email grouping';
$lang_admin['props'] = 'Properties';
$lang_admin['flags'] = 'Flags';
$lang_admin['read'] = 'Read';
$lang_admin['answered'] = 'Answered';
$lang_admin['forwarded'] = 'Forwarded';
$lang_admin['flagged'] = 'Flagged';
$lang_admin['done'] = 'Done';
$lang_admin['attachment'] = 'Attachment';
$lang_admin['color'] = 'Color';
$lang_admin['min_draft_save'] = 'Min. draft save interval';
$lang_admin['auto_save_drafts'] = 'Automaticaly save drafts';
$lang_admin['mail_send_code'] = 'Captcha code when sending mails';
$lang_admin['sms_send_code'] = 'Captcha code when sending SMS';
$lang_admin['timeframe'] = 'Timeframe';
$lang_admin['last7d'] = 'last 7 days';
$lang_admin['sendstats'] = 'Email send statistics';
$lang_admin['recvstats'] = 'Email receive statistics';
$lang_admin['pfrulenote'] =
    '(only for text fields (regular expressions) or date fields (e.g. &quot;&gt;= 18y&quot;))';
$lang_admin['vatratenotice'] = 'The VAT rate is configurable per country at';
$lang_admin['eucountry'] = 'EU country';
$lang_admin['registered'] = 'Registered';
$lang_admin['max'] = 'max.';
$lang_admin['onlyfor'] = 'only for';
$lang_admin['deliverystatus'] = 'Delivery status';
$lang_admin['acpiconsfrom'] = 'ACP icons by';
$lang_admin['acpbgfrom'] = 'ACP background by';
$lang_admin['addservices'] = 'Additional services';
$lang_admin['mailspace_add'] = 'Add. email space';
$lang_admin['diskspace_add'] = 'Add. webdisk space';
$lang_admin['traffic_add'] = 'Add. webdisk traffic';
$lang_admin['notifications'] = 'Notifications';
$lang_admin['notifyinterval'] = 'Check for notifications every';
$lang_admin['notifylifetime'] = 'Delete notifications after';
$lang_admin['days2'] = 'day(s)';
$lang_admin['after'] = 'after';
$lang_admin['nosignupautodel'] = 'Automatic deletion when never logged in';
$lang_admin['blobcompress'] = 'Compress user database';
$lang_admin['userdbvacuum'] = 'Optimize blob databases';
$lang_admin['userdbvacuum_desc'] =
    'When saving multiple objects in one file per user, it can happen that disk space is not released immediately after deleting large numbers of objects.  This feature will compact the database sizes and optimize access speed.';
$lang_admin['rebuildblobstor'] = 'Convert storage format';
$lang_admin['rebuildblobstor_desc'] =
    'When changing the storage method for a data type, all objects which have already been stored remain in their format. You can use this feature to convert objects in your old storage method to your new storage method.';
$lang_admin['rbbs_email'] = 'Convert emails';
$lang_admin['rbbs_webdisk'] = 'Convert webdisk files';
$lang_admin['separatefiles'] = 'one file per object';
$lang_admin['userdb'] = 'one file per user';
$lang_admin['nliarea'] = 'Not logged-in area';
$lang_admin['contactform'] = 'Contact form';
$lang_admin['contactform_name'] = 'Name field in contact form';
$lang_admin['contactform_subject'] = 'Subject field in contact form';
$lang_admin['cfs_note'] =
    'The subject choices can be customized per language at';
$lang_admin['captchaprovider'] = 'Captcha provider';
$lang_admin['privatekey'] = 'Private key';
$lang_admin['publickey'] = 'Public key';
$lang_admin['theme'] = 'Theme';
$lang_admin['write_xsenderip'] = 'Write X-Sender-IP header';
$lang_admin['fts_bg_indexing'] = 'Automatic background indexing';
$lang_admin['signupsuggestions'] = 'Offer address suggestions';
$lang_admin['buildindex'] = 'Build index';
$lang_admin['buildindex_desc'] =
    'You can use this feature to add non-indexed mails to the full text search index.<br /><br />This may be necessary when you have just enabled the full text search feature for users who already exist and did not have a search index so far.';
$lang_admin['optimizeindex'] = 'Optimize index';
$lang_admin['optimizeindex_desc'] =
    'This feature can be used to optimize the search index databases of your users. Optimization may release unused space, compact the databases and increase the search performance in case databases became fragmented over time.';
$lang_admin['organizerdav'] = 'CalDAV/CardDAV';
$lang_admin['ftsearch'] = 'Full text search';
$lang_admin['ftsindex'] = 'Full text index';
$lang_admin['showlist'] = 'Show list';
$lang_admin['lastactivity'] = 'Last activity';
$lang_admin['never'] = 'never';
$lang_admin['ap_medium_limit'] = 'Warn limit';
$lang_admin['ap_hard_limit'] = 'Lock limit';
$lang_admin['ap_expire_time'] = 'Points expire after';
$lang_admin['ap_expire_mode'] = 'Expiration plan';
$lang_admin['ap_dynamic'] =
    'All points expire as soon as the user did not get any new points in the specified timeframe';
$lang_admin['ap_static'] =
    'Points expire individually as soon as they reach the specified age';
$lang_admin['ap_autolock'] = 'Automatically lock accounts';
$lang_admin['ap_athardlimit'] = 'When exceeding the lock limit';
$lang_admin['hours'] = 'Hour(s)';
$lang_admin['pointtypes'] = 'Point types';
$lang_admin['ap_warn_medium'] =
    '<b>%d</b> active user(s) has/have exceeded the abuse protect warn limit.';
$lang_admin['ap_warn_hard'] =
    '<b>%d</b> active user(s) has/have exceeded the abuse protect lock limit.';
$lang_admin['ap_autolock_log'] =
    "\n" .
    '[%s] User locked by abuse protection system after exceeding the lock limit (%d >= %d).';
$lang_admin['ap_autolock_notify'] = 'Lock notification';
$lang_admin['limit_amount_count'] = 'Max. amount';
$lang_admin['limit_amount_mb'] = 'Max. traffic (MB)';
$lang_admin['limit_interval_m'] = 'Period (minutes)';
$lang_admin['ap_type1'] = 'Attempt to exceed the recipient count limit';
$lang_admin['ap_comment_1'] = 'Compose form, %d recipients';
$lang_admin['ap_comment_1_m'] = 'Mobile compose form, %d recipient';
$lang_admin['ap_type2'] = 'Attempt to exceed the sending frequency limit';
$lang_admin['ap_type3'] = 'Attempt to send to blocked recipients';
$lang_admin['ap_comment_3'] = 'Compose form, to %s';
$lang_admin['ap_comment_3_m'] = 'Mobile compose form, to %s';
$lang_admin['ap_comment_7'] =
    'Time between opening form and sending: %d seconds';
$lang_admin['ap_comment_21'] = 'Received %d mails in last %d minutes';
$lang_admin['ap_comment_22'] = 'Received %.02f MB in last %d minutes';
$lang_admin['ap_type4'] =
    'Attempt to send to existing domain, but non-existing recipient';
$lang_admin['ap_type5'] = 'Attempt to send to non-existing domain';
$lang_admin['ap_type6'] = 'SMTP submission without prior POP3/IMAP login';
$lang_admin['ap_type7'] =
    'Sending of an email too quickly after opening the compose form';
$lang_admin['ap_type21'] = 'High email receiving frequency';
$lang_admin['ap_type22'] = 'High incoming email traffic';
$lang_admin['workgroup'] = 'Workgroup';
$lang_admin['noaccess'] = 'No access';
$lang_admin['readonly'] = 'Read only';
$lang_admin['readwrite'] = 'Read / Write';
$lang_admin['sharedfolders'] = 'Email folders';
$lang_admin['recover'] = 'Recover';
$lang_admin['min_resend_interval_s'] = 'Minimum time (seconds)';
$lang_admin['minpasslength'] = 'Minimum password length';
$lang_admin['text_notify_date'] = 'Appointment notification';
$lang_admin['text_notify_newemail'] = 'Email notification';
$lang_admin['text_notify_email'] = 'Email notification from filter';
$lang_admin['text_notify_birthday'] = 'Birthday notification';
$lang_admin['text_contact_subjects'] = 'Contact form subjects';
$lang_admin['text_ap_autolock_sub'] = 'Abuse protect lock email subject';
$lang_admin['text_ap_autolock_text'] = 'Abuse protect lock email text';
$lang_admin['ssl_signup_enable'] = 'Signup via SSL';
$lang_admin['showcheckboxes'] = 'Multi-select using checkboxes';
$lang_admin['domaindisplay'] = 'Domain display';
$lang_admin['ddisplay_normal'] = '@ char in dropdown';
$lang_admin['ddisplay_separate'] = '@ char separated';
$lang_admin['signupdnsbl'] = 'Signup DNSBL filter';
$lang_admin['blocksignup'] = 'Reject signup';
$lang_admin['activatemanually'] = 'Activate manually';
$lang_admin['details'] = 'Details';
$lang_admin['compresspages'] = 'Compress page output';
$lang_admin['comment'] = 'Comment';
$lang_admin['resetstats'] = 'Reset statistics';
$lang_admin['reallyresetstats'] = 'Do you really want to reset the statistics?';
$lang_admin['payment'] = 'Payment';
$lang_admin['waitingorders'] =
    '<b>%d</b> orders with custom payment methods are awaiting activation.';
$lang_admin['disable'] = 'Disable';
$lang_admin['enablechrgskrill'] = 'Payments by Skrill';
$lang_admin['skrillacc'] = 'Skrill account';
$lang_admin['skrillsecret'] = 'Secret word';
$lang_admin['skrill'] = 'Skrill (Moneybookers)';
$lang_admin['paymentmethod'] = 'Payment method';
$lang_admin['addpaymethod'] = 'Add payment method';
$lang_admin['invoice'] = 'Invoice';
$lang_admin['at_activation'] = 'Create at activation time';
$lang_admin['at_order'] = 'Create at order time';
$lang_admin['sync'] = 'Synchronization';
$lang_admin['syncml'] = 'Synchronization';
$lang_admin['hotkeys'] = 'Hotkeys';
$lang_admin['log_autodelete'] = 'Auto archiving';
$lang_admin['enableolder'] = 'Enable for entries older than';
$lang_admin['week'] = 'Week';
$lang_admin['calendarviewmode'] = 'Calendar view';
$lang_admin['month'] = 'Month';
$lang_admin['points'] = 'Points';
$lang_admin['lastpoint'] = 'Last point';
$lang_admin['abuseprotect'] = 'Abuse protection';
$lang_admin['toolbox'] = 'Toolbox';
$lang_admin['test'] = 'Test';
$lang_admin['release'] = 'Release';
$lang_admin['preparing'] = 'Preparing';
$lang_admin['releasedone'] =
    'The version has been released. Your users can download it in their accounts at &quot;Preferences&quot; &raquo; &quot;Software&quot;. Users of older version will receive an update notification.';
$lang_admin['reallyrelease'] = 'Are you sure you want to release this version?';
$lang_admin['releaseversion'] = 'Test and release version';
$lang_admin['toolboxrelease'] =
    'You can now test and release the version. Click &quot;Test (Windows/Mac)&quot; to download a test copy of the version. After testing, you can release the version to your users by clicking &quot;Release&quot;.';
$lang_admin['toolboxonlinenote'] =
    'When clicking &quot;Test&quot; or &quot;Release&quot;, the configuration of this release will be transmitted to the b1gMail project server in order to create the release files. Creating the test or release versions may take a few minutes.';
$lang_admin['keepcurrentimg'] = 'Keep current image';
$lang_admin['toolboxfileerr'] =
    'The following images could not be saved. Please ensure that the image matches the required size and is in PNG format.';
$lang_admin['versions'] = 'Versions';
$lang_admin['addversion'] = 'Add version';
$lang_admin['baseversion'] = 'Base version';
$lang_admin['created'] = 'Created';
$lang_admin['released'] = 'Released';
$lang_admin['tbx_welcome1'] =
    'The Toolbox is an application that can be installed by your cutomers on their desktop PCs. It contains an email checker, a tool to send SMS, a fax printer, Webdisk synchronization and more.';
$lang_admin['tbx_welcome2'] =
    'In order to offer the Toolbox download to your users, you must create a Toolbox version and release it. Just click at &quot;Add&quot; at the bottom of this page, configure your Toolbox version and test and release it. Your users can find the Toolbox downloads in their account at &quot;Preferences&quot; &raquo; &quot;Software&quot; afterwards.';
$lang_admin['branding'] = 'Branding';
$lang_admin['apptitle'] = 'Application title';
$lang_admin['serviceurl'] = 'Service URL';
$lang_admin['servicetitle'] = 'Service title';
$lang_admin['applogo'] = 'Application logo';
$lang_admin['tbbranding'] = 'Toolbar branding';
$lang_admin['wizardleft'] = 'Setup banner left';
$lang_admin['wizardhead'] = 'Setup banner top';
$lang_admin['style'] = 'Style';
$lang_admin['native'] = 'Native';
$lang_admin['stylesheet'] = 'Stylesheet';
$lang_admin['names'] = 'Names';
$lang_admin['bmtoolbox'] = 'b1gMail Toolbox';
$lang_admin['tbx_enable'] = 'Offer Toolbox';
$lang_admin['tbx_webdisk'] = 'Webdisk sync';
$lang_admin['tbx_smsmanager'] = 'SMS Manager';
$lang_admin['notrecommended'] = 'NOT recommended';
$lang_admin['prefslayout'] = 'Preferences overview';
$lang_admin['onecolumn'] = 'One column';
$lang_admin['twocolumns'] = 'Two columns';
$lang_admin['navpos'] = 'Main navigation';
$lang_admin['left'] = 'Left';
$lang_admin['top'] = 'Top';
$lang_admin['deprecated'] = 'deprecated';
$lang_admin['defaultemplate'] = 'Default template';
$lang_admin['colorscheme'] = 'Color scheme';
$lang_admin['orange'] = 'Orange';
$lang_admin['blue'] = 'Blue';
$lang_admin['tabmode'] = 'Tab mode';
$lang_admin['complete'] = 'Complete';
$lang_admin['icons'] = 'Icons';
$lang_admin['hidesignup'] = 'Hide signup when disabled';
$lang_admin['showuseremail'] = 'Show user email address';
$lang_admin['templates'] = 'Templates';
$lang_admin['show_at'] = 'Show at';
$lang_admin['adddomain'] = 'Add domain';
$lang_admin['account'] = 'Account';
$lang_admin['admins'] = 'Administrators';
$lang_admin['download'] = 'Download';
$lang_admin['phpinfo'] = 'PHP info';
$lang_admin['redirectmobile'] = 'Mobile interface redirect';
$lang_admin['maxlength'] = 'Max. length';
$lang_admin['repeat'] = 'repeat';
$lang_admin['admin'] = 'Administrator';
$lang_admin['superadmin'] = 'Super administrator';
$lang_admin['loggedinas'] = 'Logged in as';
$lang_admin['pwerror'] =
    'The passwords you entered do not match or have less then 6 characters.';
$lang_admin['addadmin'] = 'Add administrator';
$lang_admin['adminexists'] =
    'An administrator with this username already exists. Please chose another username.';
$lang_admin['permissions'] = 'Permissions';
$lang_admin['editadmin'] = 'Edit administrator';
$lang_admin['areas'] = 'Areas';
$lang_admin['lockedaltmails'] = 'Forbidden alt. email addresses';
$lang_admin['altmailsepby'] =
    '(one entry per line, \'*\' usable as wild card, e.g. \'*@evil-domain.xy\')';
$lang_admin['yourinvoice'] = 'Your invoice';
$lang_admin['dearsirormadam'] = 'Dear Sir or Madam';
$lang_admin['descr'] = 'Description';
$lang_admin['ep'] = 'Unit price';
$lang_admin['gp'] = 'Price';
$lang_admin['gb'] = 'Total amount';
$lang_admin['vat'] = 'VAT';
$lang_admin['net'] = 'net';
$lang_admin['gross'] = 'gross';
$lang_admin['kindregards'] = 'Kind regards';
$lang_admin['invtext'] = 'please find your invoice below';
$lang_admin['invoiceno'] = 'Invoice no';
$lang_admin['customerno'] = 'Customer no';
$lang_admin['bankacc'] = 'Bank account';
$lang_admin['invfooter'] =
    'This invoice has been generated automatically and is valid without a signature.';
$lang_admin['kto_inh'] = 'Account owner';
$lang_admin['kto_nr'] = 'Account no';
$lang_admin['kto_blz'] = 'Bank code';
$lang_admin['kto_inst'] = 'Bank name';
$lang_admin['kto_iban'] = 'IBAN';
$lang_admin['kto_bic'] = 'BIC/SWIFT code';
$lang_admin['kto_subject'] = 'Subject';
$lang_admin['sender_aliases'] = 'Sender aliases';
$lang_admin['attachments'] = 'Attachments';
$lang_admin['flexspans'] = 'Auto subject length';
$lang_admin['paidonly'] = 'Paid only';
$lang_admin['account_debit'] = 'Debit account';
$lang_admin['account_credit'] = 'Credit account';
$lang_admin['accentries'] = 'Accounting entries';
$lang_admin['orderno'] = 'Order no';
$lang_admin['export2'] = 'Export';
$lang_admin['vkcode'] = 'Wire transfer code';
$lang_admin['activatepayment'] = 'Activate payment';
$lang_admin['amount'] = 'Amount';
$lang_admin['activate_ok'] = 'Payment activated successfully!';
$lang_admin['activate_err'] = 'Not found / already activated / wrong amount!';
$lang_admin['activate_desc'] =
    'Here you can activate payments you received on your bank account.';
$lang_admin['returnpathcheck'] = 'Return path check';
$lang_admin['licensedetails'] = 'License details';
$lang_admin['updateaccess'] = 'Update access';
$lang_admin['pleasewait'] = 'Please wait...';
$lang_admin['startwidgets'] = 'Start dashboard';
$lang_admin['defaultlayout'] = 'Default layout';
$lang_admin['default'] = 'Default';
$lang_admin['layout_addremove'] = 'Add/remove widgets';
$lang_admin['layout_resetdesc'] =
    'Reset the widget layout of all users belonging to one of the following groups to the default layout:';
$lang_admin['organizerwidgets'] = 'Organizer dashboard';
$lang_admin['pos'] = 'Position';
$lang_admin['widgetlayouts'] = 'Widget layouts';
$lang_admin['taborder'] = 'Tab order';
$lang_admin['payments'] = 'Payments';
$lang_admin['invalidselffolder'] =
    'The configured absolute path to b1gMail (<code>%s</code>) does not exist. Please correct the path!';
$lang_admin['auto_tz'] = 'Timezone auto detect';
$lang_admin['check_double_altmail'] = 'Check for double alt. mail';
$lang_admin['check_double_cellphone'] = 'Check for double cellphone no';
$lang_admin['orphansfound'] =
    '%d orphaned email(s) were found (%.02f KB). We recommend to delete the orphaned objects.';
$lang_admin['orphans_desc'] =
    'This function deletes all orphaned mails irrevocably.<br /><br />Orphaned mails are mails which do not belong to an user anymore. They can occur when an user deletion process aborts unexpectedly.';
$lang_admin['orphans_done'] =
    '%d orphaned object(s) have been found and deleted (%.02f KB).';
$lang_admin['orphans'] = 'Orphans';
$lang_admin['diskorphansfound'] =
    '%d orphaned webdisk file(s) have been found (%.02f KB). We recommend to delete the orphaned objects.';
$lang_admin['diskorphans_desc'] =
    'This function deletes all orphaned webdisk files irrevocably.<br /><br />Orphaned webdisk files are files which do not belong to an user anymore. They can occur when an user deletion process aborts unexpectedly.';
$lang_admin['mailorphans'] = 'Orphaned emails';
$lang_admin['diskorphans'] = 'Orphaned webdisk files';
$lang_admin['text_paynotify_sub'] = 'Payment notification subject';
$lang_admin['text_paynotify_text'] = 'Payment notification text';
$lang_admin['text_orderconfirm_sub'] = 'Order confirmation subject';
$lang_admin['text_orderconfirm_text'] = 'Order confirmation text';
$lang_admin['text_share_sub'] = 'Webdisk share mail subject';
$lang_admin['text_share_text'] = 'Webdisk share mail text';
$lang_admin['selffolder'] = 'Abs. path to b1gMail';
$lang_admin['dynnorecvrules'] =
    'Recipient detemination is set to &quot;Use receive rules&quot; but no receive rule exists. This way, receiving emails is impossible. Please set the recipient determination method to &quot;Automatic&quot; or add receive rules.';
$lang_admin['disablesender'] = 'Disable sender field';
$lang_admin['salutation'] = 'Salutation';
$lang_admin['mrs'] = 'Mrs';
$lang_admin['mr'] = 'Mr';
$lang_admin['greeting'] = 'Greeting';
$lang_admin['greeting_mr'] = 'Dear Mr %s';
$lang_admin['greeting_mrs'] = 'Dear Mrs %s';
$lang_admin['greeting_none'] = 'Dear Sir or Madam';
$lang_admin['sunotifypass'] = 'Notification password';
$lang_admin['suinputcheck'] = 'Input check';
$lang_admin['invoices'] = 'Invoices';
$lang_admin['pay_notification'] = 'Payment notifications';
$lang_admin['vat'] = 'VAT';
$lang_admin['vatrate'] = 'VAT rate';
$lang_admin['vat_add'] = 'add to prices';
$lang_admin['vat_enthalten'] = 'included in prices';
$lang_admin['vat_nomwst'] = 'no VAT';
$lang_admin['enablebanktransfer'] = 'Wire transfer payment';
$lang_admin['kto_inh'] = 'Account owner';
$lang_admin['kto_nr'] = 'Account no';
$lang_admin['kto_blz'] = 'Bank code';
$lang_admin['kto_inst'] = 'Bank name';
$lang_admin['kto_iban'] = 'IBAN';
$lang_admin['kto_bic'] = 'BIC/SWIFT code';
$lang_admin['banktransfer'] = 'Wire transfer';
$lang_admin['sendrg'] = 'Generate invoices';
$lang_admin['rgnrfmt'] = 'Invoice no format';
$lang_admin['kdnrfmt'] = 'Customer no format';
$lang_admin['rgtemplate'] = 'Invoice template';
$lang_admin['enablesmscharge'] = 'Enable SMS charging';
$lang_admin['paypal'] = 'PayPal';
$lang_admin['su'] = 'sofort&uuml;berweisung.de';
$lang_admin['paymentmethods'] = 'Payment methods';
$lang_admin['orderstatus_0'] = 'Created';
$lang_admin['orderstatus_1'] = 'Activated';
$lang_admin['downloadinvoices'] = 'Download invoices';
$lang_admin['showinvoice'] = 'Show invoice';
$lang_admin['acp'] = 'Administrator Control Panel (ACP)';
$lang_admin['password'] = 'Password';
$lang_admin['login'] = 'Login';
$lang_admin['dattempt'] =
    'This is your %d. attempt to log in, after 5 attempts log-in will be blocked for 5 minutes.';
$lang_admin['dattempt2'] =
    'Due to too many failed login attempts login for this account will be blocked until %s .';
$lang_admin['loginerror'] = 'Invalid password. Please try again.';
$lang_admin['welcome'] = 'Welcome';
$lang_admin['usersgroups'] = 'Users &amp; Groups';
$lang_admin['users'] = 'Users';
$lang_admin['user'] = 'User';
$lang_admin['domain'] = 'Domain';
$lang_admin['groups'] = 'Groups';
$lang_admin['activity'] = 'Activity';
$lang_admin['edittemplate'] = 'Edit template';
$lang_admin['addtemplate'] = 'Add template';
$lang_admin['newsletter'] = 'Newsletter';
$lang_admin['newsletter_done'] =
    'The newsletter has been sent successfully to <b>%d</b> users. <b>%d</b> attempts to send the newsletter have failed.';
$lang_admin['prefs'] = 'Preferences';
$lang_admin['common'] = 'Common';
$lang_admin['profilefields'] = 'Profile fields';
$lang_admin['languages'] = 'Languages';
$lang_admin['webdiskicons'] = 'Webdisc icons';
$lang_admin['sms'] = 'SMS';
$lang_admin['ads'] = 'Advertisements';
$lang_admin['faq'] = 'FAQ';
$lang_admin['coupons'] = 'Vouchers';
$lang_admin['tools'] = 'Tools';
$lang_admin['optimize'] = 'Optimize';
$lang_admin['stats'] = 'Statistics';
$lang_admin['backup'] = 'Backup';
$lang_admin['logs'] = 'Log files';
$lang_admin['receivesys'] = 'Receiving';
$lang_admin['updates'] = 'Updates';
$lang_admin['plugins'] = 'Plugins';
$lang_admin['logout'] = 'Logout';
$lang_admin['logoutquestion'] = 'Are you sure you want to log out?';
$lang_admin['license'] = 'License';
$lang_admin['overview'] = 'Overview';
$lang_admin['notes'] = 'Notes';
$lang_admin['notices'] = 'Notifications';
$lang_admin['about'] = 'About b1gMail';
$lang_admin['version'] = 'Version';
$lang_admin['nonotices'] = 'Currently there are no new notifications.';
$lang_admin['save'] = 'Save';
$lang_admin['notactivated'] = 'Not activated';
$lang_admin['locked'] = 'Locked';
$lang_admin['emails'] = 'Emails';
$lang_admin['folders'] = 'Folders';
$lang_admin['disksize'] = 'Webdisc size';
$lang_admin['files'] = 'Files';
$lang_admin['phpversion'] = 'PHP version';
$lang_admin['webserver'] = 'Webserver';
$lang_admin['mysqlversion'] = 'MySQL version';
$lang_admin['dbsize'] = 'Database size';
$lang_admin['load'] = 'Server load';
$lang_admin['db'] = 'Database';
$lang_admin['cache'] = 'Cache';
$lang_admin['filesystem'] = 'File system';
$lang_admin['optimizedb'] = 'Optimize database';
$lang_admin['tables'] = 'Tables';
$lang_admin['action'] = 'Action';
$lang_admin['op_optimize'] = 'Optimize';
$lang_admin['op_optimize_desc'] =
    'Will release unused space in the tables and clean them up for quicker access.';
$lang_admin['op_repair'] = 'Repair';
$lang_admin['op_repair_desc'] =
    'Will check tables for errors and repair them as good as possible.';
$lang_admin['op_struct'] = 'Check structure';
$lang_admin['op_struct_desc'] =
    'Will check the structure of the tables and repair them if requested. This operation is not dependent on the table selection.';
$lang_admin['execute'] = 'Execute';
$lang_admin['back'] = 'Back';
$lang_admin['success'] = 'Success';
$lang_admin['error'] = 'Error';
$lang_admin['couldfree'] =
    'An optimization of the database can improve the database performance and free up %.02f MB of space.';
$lang_admin['emailsize'] = 'Email size';
$lang_admin['debugmode'] =
    'b1gMail is currently in debug mode. You should disable the debug mode when being in production use.';
$lang_admin['rebuildcaches'] = 'Rebuild caches';
$lang_admin['rebuild_desc'] =
    'Here you can rebuild intermediately stored data (caches) to provide the best possible data integrity. However, this will usually only be necessary if you are experiencing any problems with the data in question.';
$lang_admin['heavyop'] =
    'This operation may be very heavy and take a long time. You should not cancel the operation.';
$lang_admin['emailsizes_cache'] = 'Recalculate email sizes';
$lang_admin['emailsizes_desc'] =
    'Will recalculate the size of every email stored in the system (in practice, this operation is very rarely necessary).';
$lang_admin['usersizes_cache'] = 'Recalculate user space usage';
$lang_admin['usersizes_desc'] =
    'Will recalculate the space usage of the your users.';
$lang_admin['disksizes_cache'] = 'Recalculate the sizes of webdisc files';
$lang_admin['disksizes_desc'] =
    'Will recalculate the size of every webdisc file saved in the system (in practice, this operation is very rarely necessary).';
$lang_admin['opsperpage'] = 'Operations per instance';
$lang_admin['nopostmaster'] =
    'The postmaster user (<code>%s</code>) does not exist. You are strongly recommended to create that user to avoid problems with email processing.';
$lang_admin['cachesizesdiffer'] =
    'The user space cache seems to be out of sync with the user data. You should rebuild the cache.';
$lang_admin['unknown'] = 'unknown';
$lang_admin['dataperms'] =
    'Cannot write to the b1gMail data directory (<code>%s</code>). Please check the access rights!';
$lang_admin['invaliddata'] =
    'The b1gMail data directory (<code>%s</code>) could not be found. Please check the path and try again!';
$lang_admin['email'] = 'Email';
$lang_admin['receive'] = 'Receive';
$lang_admin['send'] = 'Send';
$lang_admin['antispam'] = 'Anti spam';
$lang_admin['antivirus'] = 'Anti virus';
$lang_admin['recvmethod'] = 'Receiving method';
$lang_admin['miscprefs'] = 'Miscellaneous preferences';
$lang_admin['rules'] = 'Rules';
$lang_admin['gateways'] = 'Gateways';
$lang_admin['types'] = 'Types';
$lang_admin['texts'] = 'Texts';
$lang_admin['calendar'] = 'Calendar';
$lang_admin['signup'] = 'Sign-up';
$lang_admin['optimizedb'] = 'Optimize database';
$lang_admin['tempfiles'] = 'Temporary files';
$lang_admin['count'] = 'Count';
$lang_admin['size'] = 'Size';
$lang_admin['tempdesc'] =
    'Temporary files will usually be deleted by b1gMail if they are no longer required. You may also start the cleanup process manually. Please note that only those files will be deleted that have reached a certain age.';
$lang_admin['cleanup'] = 'Clean up';
$lang_admin['mailstorage'] = 'Email storage';
$lang_admin['storage_desc'] =
    'Here you can transfer emails to the current storage.';
$lang_admin['file2db'] = 'Files -&gt; database';
$lang_admin['db2file'] = 'Database -&gt; files';
$lang_admin['file2db_desc'] =
    'Will transfer emails that are stored in files to the database.';
$lang_admin['db2file_desc'] =
    'Emails stored in the database will be re-stored as files.';
$lang_admin['installedplugins'] = 'Installed plugins';
$lang_admin['installplugin'] = 'Install plugin';
$lang_admin['inactive'] = 'Inactive';
$lang_admin['active'] = 'Active';
$lang_admin['type'] = 'Type';
$lang_admin['title'] = 'Title';
$lang_admin['author'] = 'Author';
$lang_admin['info'] = 'Info';
$lang_admin['status'] = 'Status';
$lang_admin['installed'] = 'Installed';
$lang_admin['notinstalled'] = 'Not installed';
$lang_admin['current'] = 'Latest';
$lang_admin['module'] = 'Module';
$lang_admin['widget'] = 'Widget';
$lang_admin['widgets'] = 'Widgets';
$lang_admin['acdeactivate'] = 'Activate / deactivate';
$lang_admin['reallyplugin'] =
    'Are you sure you want to activate / deactivate the plugin? Activating faulty plugins may compromise system integrity; deactivating plugins may permanently erase the data associated with the plugin!';
$lang_admin['plugpackage'] = 'Plugin package (.bmplugin file)';
$lang_admin['install_desc'] =
    'You can easily install new plugins if they are available as a .bmplugin package. Just upload the plugin package here and it will be installed automatically.';
$lang_admin['install'] = 'Install';
$lang_admin['plugin_formaterr'] =
    'The file you uploaded is damaged or it is not a valid plugin package in .bmplugin format!';
$lang_admin['sourcewarning'] =
    'Please make sure you install files from trustworthy sources only - they might contain dangerous code!';
$lang_admin['archiving'] = 'Archiving';
$lang_admin['entry'] = 'Entry';
$lang_admin['date'] = 'Date';
$lang_admin['export'] = 'Export';
$lang_admin['filter'] = 'Filter';
$lang_admin['show'] = 'Show';
$lang_admin['from'] = 'From';
$lang_admin['to'] = 'To';
$lang_admin['logarc_desc'] =
    'Here you can erase all logs that have been generated before a certain date (e.g. in order to accelerate the system and to save storage space). Optionally you can save a copy in the archive (&quot;logs&quot; folder).';
$lang_admin['savearc'] = 'Save copy to the archive';
$lang_admin['reallynotarc'] =
    'Are you sure you want to erase the logs permanently WITHOUT saving them to the archive? There might be a certain minimum retention period required by law!';
$lang_admin['notactnotice'] =
    '<b>%d</b> user account(s) has/have the status &quot;Not activated&quot; and is/are waiting for you to activate it/them.';
$lang_admin['deletenotice'] =
    '<b>%d</b> user account(s) has/have the Status &quot;Erased&quot; and is/are waiting for you to erase it/them permanently.';
$lang_admin['maxsizewarning'] =
    'The mail size limit (incoming) of the group <b>%s</b> is %d KB and thus bigger than the mail size limit of %d KB configured at Preferences &raquo; Email &raquo; Incoming!';
$lang_admin['manylogs'] =
    'The log table contains over 250.000 entries. You should archive your old entries.';
$lang_admin['mbstring'] =
    'Neither the <code>mbstring</code> nor the <code>iconv</code> PHP extension is available. In order to achieve optimal results as far as special characters are concerned, you should install one of the extensions if possible.';
$lang_admin['gdlib'] =
    'The <code>gd</code> PHP extension is not available. Some of the graphics-related features of b1gMail require this extension. If possible, you should install it.';
$lang_admin['idnlib'] =
    'Support for internationalized domain names (IDN) is not available. If you want to use IDN, please install the PECL extension <code>intl</code> or <code>idn</code>.';
$lang_admin['domdocument'] =
    'The <code>dom</code>/<code>xml</code> PHP extension is not available. To improve security when reading HTML emails, it is strongly recommended to install these extension(s).';
$lang_admin['create'] = 'Create';
$lang_admin['id'] = 'ID';
$lang_admin['name'] = 'Name';
$lang_admin['deleted'] = 'Deleted';
$lang_admin['apply'] = 'Apply';
$lang_admin['perpage'] = 'Per page';
$lang_admin['search'] = 'Search';
$lang_admin['group'] = 'Group';
$lang_admin['missing'] = '(MISSING)';
$lang_admin['pages'] = 'Pages';
$lang_admin['actions'] = 'Actions';
$lang_admin['move'] = 'Move';
$lang_admin['moveto'] = 'Move to';
$lang_admin['delete'] = 'Delete';
$lang_admin['lock'] = 'Lock';
$lang_admin['unlock'] = 'Unlock';
$lang_admin['restore'] = 'Restore';
$lang_admin['edit'] = 'Edit';
$lang_admin['loginwarning'] =
    'Are you sure you want to log on to the account you have selected? Please pay attention to the data privacy regulations applying in your country and respect your user\'s privacy!';
$lang_admin['spaceusage'] = 'Space usage';
$lang_admin['webdisk'] = 'Webdisc';
$lang_admin['used'] = 'used';
$lang_admin['usage'] = 'Usage';
$lang_admin['aliases'] = 'Aliases';
$lang_admin['firstname'] = 'First name';
$lang_admin['lastname'] = 'Last name';
$lang_admin['streetno'] = 'Street / no.';
$lang_admin['zipcity'] = 'Zip / city';
$lang_admin['tel'] = 'Phone';
$lang_admin['fax'] = 'Fax';
$lang_admin['altmail'] = 'Alternative email';
$lang_admin['profile'] = 'Profile';
$lang_admin['country'] = 'Country';
$lang_admin['re'] = 'Re';
$lang_admin['fwd'] = 'Fwd';
$lang_admin['cellphone'] = 'Cellphone';
$lang_admin['misc'] = 'Miscellaneous';
$lang_admin['lastlogin'] = 'Last login';
$lang_admin['ip'] = 'IP address';
$lang_admin['regdate'] = 'Registration date';
$lang_admin['lastpop3'] = 'Last POP3 access';
$lang_admin['lastimap'] = 'Last IMAP access';
$lang_admin['lastsmtp'] = 'Last SMTP access';
$lang_admin['newpassword'] = '(New) password';
$lang_admin['assets'] = 'Assets';
$lang_admin['credits'] = 'Credits';
$lang_admin['alias'] = 'Alias';
$lang_admin['realdel'] =
    'Are you sure you want to erase the entry permanently?';
$lang_admin['wdtraffic'] = 'Webdisc traffic';
$lang_admin['used2'] = 'used';
$lang_admin['used3'] = 'Used';
$lang_admin['ok'] = 'OK';
$lang_admin['sendmail'] = 'Send email';
$lang_admin['emptytrash'] = 'Empty trash';
$lang_admin['monthasset'] = 'Assets per month';
$lang_admin['yes'] = 'Yes';
$lang_admin['no'] = 'No';
$lang_admin['notconfirmed'] = 'Unconfirmed';
$lang_admin['mail2sms'] = 'Mail-to-SMS';
$lang_admin['forward'] = 'Forwarding';
$lang_admin['forwardto'] = 'Forward to';
$lang_admin['dateformat'] = 'Date format';
$lang_admin['sendername'] = 'Sender name';
$lang_admin['addressinvalid'] =
    'The email address you have entered is invalid. Please try again.';
$lang_admin['addresstaken'] =
    'The email address you have entered is no longer available. Please try again.';
$lang_admin['accountcreated'] =
    'The account has been created successfuly.<br /><br /><a href="users.php?do=edit&id=%d&sid=%s">&raquo; Edit user</a>';
$lang_admin['enablereg'] = 'Enable sign-up';
$lang_admin['stateafterreg'] = 'Status after sign-up';
$lang_admin['smsvalidation_signup'] = 'SMS signup activation';
$lang_admin['smsvalidation'] = 'SMS sender validation';
$lang_admin['reg_validation'] = 'Signup validation';
$lang_admin['max_resend_times'] = 'Max. re-send requests';
$lang_admin['min_resend_interval'] = 'Minimum interval';
$lang_admin['byemail'] = 'by email';
$lang_admin['bysms'] = 'by SMS';
$lang_admin['resend_val_email'] = 'Resend validation email';
$lang_admin['resend_val_sms'] = 'Resend validation SMS';
$lang_admin['val_code_resent'] = 'The validation code has been re-sent.';
$lang_admin['stdgroup'] = 'Default group';
$lang_admin['scsf'] = 'Security code interference factor';
$lang_admin['domain_combobox'] = 'Login domain combo box';
$lang_admin['fields'] = 'Fields';
$lang_admin['field'] = 'Field';
$lang_admin['oblig'] = 'Obligatory';
$lang_admin['available'] = 'Available';
$lang_admin['notavailable'] = 'Not available';
$lang_admin['safecode'] = 'Security code';
$lang_admin['datavalidation'] = 'Data validation';
$lang_admin['sessioniplock'] = 'Session IP lock';
$lang_admin['sessioncookielock'] = 'Session cookie lock';
$lang_admin['regiplock'] = 'Sign-up IP lock';
$lang_admin['seconds'] = 'seconds';
$lang_admin['plzcheck'] = 'Check if zip code and city match';
$lang_admin['altcheck'] = 'Check alternative email';
$lang_admin['usercountlimit'] = 'User count limit';
$lang_admin['minaddrlength'] = 'Minimum username length';
$lang_admin['to2'] = 'To';
$lang_admin['regnotify'] = 'Sign-up notification';
$lang_admin['recvrules'] = 'Receive rules';
$lang_admin['autodetection'] = 'Auto detection';
$lang_admin['expression'] = 'Regular expression';
$lang_admin['value'] = 'Value';
$lang_admin['isrecipient'] = 'Set definite recipient';
$lang_admin['setrecipient'] = 'Set exclusive recipient';
$lang_admin['addrecipient'] = 'Add possible recipient(s)';
$lang_admin['receiverule'] = 'Receive rule';
$lang_admin['custom'] = 'Custom';
$lang_admin['addrecvrule'] = 'Add receive rule';
$lang_admin['add'] = 'Add';
$lang_admin['bounce'] = 'Bounce';
$lang_admin['markspam'] = 'Mark as spam';
$lang_admin['markinfected'] = 'Mark as infected';
$lang_admin['setinfection'] = 'Set infection';
$lang_admin['markread'] = 'Mark as read';
$lang_admin['import'] = 'Import';
$lang_admin['ruledesc'] =
    'Here you can import a .bmrecvrules-file with receive rules.';
$lang_admin['rulefile'] = 'Rule file (.bmrecvrules file)';
$lang_admin['validityrule'] = 'Validity rule';
$lang_admin['validitytime'] = 'Period of validity';
$lang_admin['checkbox'] = 'Checkbox';
$lang_admin['dropdown'] = 'Dropdown';
$lang_admin['radio'] = 'Radio button';
$lang_admin['text'] = 'Text';
$lang_admin['customfieldsat'] = 'Custom fields can be configured at';
$lang_admin['addprofilefield'] = 'Add profile field';
$lang_admin['options'] = 'Options';
$lang_admin['onlyfortext'] = '(for text fields only)';
$lang_admin['optionsdesc'] =
    '(only for radio button or dropdown field, use comma to seperate)';
$lang_admin['brokenperms'] =
    'The following files and folders are not writeable: <code>%s</code>. Please check the access rights!';
$lang_admin['brokenhtaccess'] =
    'The following .htaccess files do not exist: <code>%s</code>. Please upload the files again - without these files some of the data files are not protected from unauthorized access!';
$lang_admin['maintenance'] = 'Maintenance';
$lang_admin['inactiveusers'] = 'Inactive users';
$lang_admin['trash'] = 'Trash';
$lang_admin['pop3gateway'] = 'POP3 gateway';
$lang_admin['pop3fetch_desc'] =
    'Here you can check the CatchAll POP3 account of the POP3 gateway manually in case too many emails exist in it. If the process aborts unexpectedly, please check that your mail size limits are not set to a too large value.';
$lang_admin['help'] = 'Help';
$lang_admin['none'] = 'None';
$lang_admin['persistent'] = 'Persistent connections';
$lang_admin['servers'] = 'Server';
$lang_admin['memcachesepby'] =
    '(one server per line, format: &quot;hostname:port,weight&quot;)';
$lang_admin['parseonly'] = 'Only email objects';
$lang_admin['caching'] = 'Caching';
$lang_admin['cachemanager'] = 'Cache manager';
$lang_admin['ce_disable'] = 'Disable';
$lang_admin['ce_disable_desc'] =
    'Will disable the caching of objects (not recommended).';
$lang_admin['ce_b1gmail'] = 'b1gMail cache manager';
$lang_admin['ce_b1gmail_desc'] =
    'Will use the b1gMail cache manager to cache objects (recommended if memcached is not available).';
$lang_admin['ce_memcache'] = 'memcached';
$lang_admin['ce_memcache_desc'] =
    'will use memcached as cache manager (recommended if available).';
$lang_admin['filecache'] = 'File cache';
$lang_admin['filecachedesc'] =
    'If file cache is enabled, b1gMail itself will administrate the caching of objects that are CPU-intensive to generate. The cache will usually be administrated automatically and will also be cleaned up automatically if required. Here you can empty the cache manually if required.';
$lang_admin['emptycache'] = 'Empty cache';
$lang_admin['clamintegration'] = 'ClamAV-/clamd-integration';
$lang_admin['host'] = 'Host';
$lang_admin['port'] = 'Port';
$lang_admin['enable'] = 'Enable';
$lang_admin['clamwarning'] =
    'Only enable ClamAV-integration if ClamAV/clamd has been installed on the mentioned server and is enabled.';
$lang_admin['countries'] = 'Countries';
$lang_admin['plzdb'] = 'ZIP DB';
$lang_admin['addcountry'] = 'Add country';
$lang_admin['cachetime'] = 'Cache validity';
$lang_admin['cachesize'] = 'Cache size';
$lang_admin['inactiveonly'] = 'For inactive files only';
$lang_admin['storein'] = 'Store message in';
$lang_admin['language'] = 'Language';
$lang_admin['addlanguage'] = 'Add language';
$lang_admin['addlang_desc'] =
    'Here you can install a new language file (.lang.php file) by uploading it using the following form. Please make sure the language file is applicable for your version of b1gMail.';
$lang_admin['langfile'] = 'Language file';
$lang_admin['pipeetc'] = 'Pipe / transportmap gateway';
$lang_admin['pop3host'] = 'POP3 server';
$lang_admin['pop3user'] = 'POP3 user';
$lang_admin['pop3pass'] = 'POP3 password';
$lang_admin['pop3port'] = 'POP3 port';
$lang_admin['fetchcount'] = 'Emails per fetch process';
$lang_admin['mailmax'] = 'Maximum size of messages';
$lang_admin['errormail'] = 'Enable Non-Delivery Notification';
$lang_admin['errormail_soft'] = 'Only for emails without valid recipients';
$lang_admin['failure_forward'] = 'Forward undeliverable messages to postmaster';
$lang_admin['smtphost'] = 'SMTP server';
$lang_admin['smtpport'] = 'SMTP port';
$lang_admin['smtpauth'] = 'Requires authentification';
$lang_admin['smtpuser'] = 'SMTP user';
$lang_admin['smtppass'] = 'SMTP password';
$lang_admin['sendmethod'] = 'Sending method';
$lang_admin['smtp'] = 'SMTP';
$lang_admin['phpmail'] = 'PHP mail';
$lang_admin['sysmailsender'] = 'System mail sender';
$lang_admin['maxrecps'] = 'Maximum number of recipients';
$lang_admin['blockedrecps'] = 'Forbidden recipients';
$lang_admin['sepby'] = '(one entry per line)';
$lang_admin['dnsbl'] = 'DNSBL filter';
$lang_admin['dnsblservers'] = 'DNSBL servers';
$lang_admin['bayes'] = 'Statistical, trainable filter';
$lang_admin['bayesmode'] = 'Filter database mode';
$lang_admin['bayeslocal'] = 'Local (one database for each user)';
$lang_admin['bayesglobal'] = 'Global (one database for all users)';
$lang_admin['customtexts'] = 'Customizable texts';
$lang_admin['sendmail2'] = 'Sendmail';
$lang_admin['sendmailpath'] = 'Sendmail path';
$lang_admin['text_maintenance'] = 'Maintenance mode note';
$lang_admin['text_welcome_sub'] = 'Welcome mail subject';
$lang_admin['text_welcome_text'] = 'Welcome mail text';
$lang_admin['text_tos'] = 'TOS';
$lang_admin['text_imprint'] = 'Impint';
$lang_admin['text_snotify_sub'] = 'Sign-up note subject';
$lang_admin['text_snotify_text'] = 'Sign-up note text';
$lang_admin['text_selfcomp_n_sub'] = 'Addressbook notify subject';
$lang_admin['text_selfcomp_n_text'] = 'Addressbook notify text';
$lang_admin['text_selfcomp_sub'] = 'Addressbook mail subject';
$lang_admin['text_selfcomp_text'] = 'Addressbook mail text';
$lang_admin['text_passmail_sub'] = 'Password mail subject';
$lang_admin['text_passmail_text'] = 'Password mail text';
$lang_admin['text_certmail'] = 'Certified mail text';
$lang_admin['text_mail2sms'] = 'Mail-to-SMS notification';
$lang_admin['text_cs_subject'] = 'Cert. mail receipt subject';
$lang_admin['text_cs_text'] = 'Cert. mail receipt text';
$lang_admin['text_clndr_subject'] = 'Date nofiy subject';
$lang_admin['text_clndr_date_msg'] = 'Date notify text';
$lang_admin['text_clndr_sms'] = 'Date notify SMS';
$lang_admin['text_receipt_text'] = 'Receipt mail text';
$lang_admin['text_validationsms'] = 'Sign up SMS validation';
$lang_admin['text_validationsms2'] = 'SMS sender validation';
$lang_admin['text_alias_sub'] = 'Alias mail subject';
$lang_admin['text_alias_text'] = 'Alias mail text';
$lang_admin['text_activationmail_sub'] = 'Activation mail subject';
$lang_admin['text_activationmail_text'] = 'Activation mail text';
$lang_admin['projecttitle'] = 'Site title';
$lang_admin['selfurl'] = 'b1gMail URL';
$lang_admin['mobile_url'] = 'Mobile b1gMail URL';
$lang_admin['ssl'] = 'SSL';
$lang_admin['ssl_url'] = 'b1gMail SSL URL';
$lang_admin['ssl_login_enable'] = 'Login using SSL by default';
$lang_admin['ssl_login_option'] = 'SSL login checkbox';
$lang_admin['datafolder'] = 'Data directory';
$lang_admin['hostname'] = 'Hostname';
$lang_admin['template'] = 'Template';
$lang_admin['defaults'] = 'Defaults';
$lang_admin['itemsperpage'] = 'Entries per page';
$lang_admin['censorchar'] = 'Censorship character';
$lang_admin['domains'] = 'Domains';
$lang_admin['allownewsoptout'] = 'Allow newsletter opt-out';
$lang_admin['allow_newsletter_optout'] = 'Allow newsletter opt-out';
$lang_admin['gutregged'] = 'Vouchers for registered users';
$lang_admin['autocancel'] = 'Account deletable by user';
$lang_admin['maintmode'] = 'Maintenance mode';
$lang_admin['maintmodenote'] =
    'Maintenance mode is enabled. The b1gMail installation is not accessible for users.';
$lang_admin['dldate'] = 'Package date';
$lang_admin['invalidserial'] =
    'The serial number you have entered is invalid. Please try again.';
$lang_admin['members'] = 'Members';
$lang_admin['addmember'] = 'Add member';
$lang_admin['storage'] = 'Storage';
$lang_admin['limits'] = 'Limits';
$lang_admin['emailin'] = 'Email (incoming)';
$lang_admin['emailout'] = 'Email (outgoing)';
$lang_admin['services'] = 'Services';
$lang_admin['pop3'] = 'POP3';
$lang_admin['imap'] = 'IMAP';
$lang_admin['webdav'] = 'WebDAV';
$lang_admin['autoresponder'] = 'Autoresponder';
$lang_admin['mobileaccess'] = 'Mobile access';
$lang_admin['mailchecker'] = 'Mail checker';
$lang_admin['issue_certificates'] = 'Issue certificates';
$lang_admin['upload_certificates'] = 'Upload certificates';
$lang_admin['ownfrom'] = 'Own SMS sender';
$lang_admin['wdshare'] = 'Webdisc share';
$lang_admin['wdspeed'] = 'Webdisc speed';
$lang_admin['sharespeed'] = 'Share speed';
$lang_admin['htmlview'] = 'HTML mode by default';
$lang_admin['sendlimit'] = 'Send limit';
$lang_admin['emailsin'] = 'email(s) in';
$lang_admin['minutes'] = 'minute(s)';
$lang_admin['ownpop3'] = 'External POP3 accounts';
$lang_admin['ownpop3interval'] = 'POP3 poll interval';
$lang_admin['selfpop3_check'] = 'Protect against fetching own account';
$lang_admin['smspre'] = 'SMS area codes';
$lang_admin['aliasdomains'] = 'Additional alias domains';
$lang_admin['smsfrom'] = 'SMS sender';
$lang_admin['smssig'] = 'SMS signature';
$lang_admin['mailsig'] = 'Email signature';
$lang_admin['creditprice'] = 'Credit price';
$lang_admin['receivedmails'] = 'Received';
$lang_admin['sentmails'] = 'Sent';
$lang_admin['wdtrafficshort'] = 'WD traffic';
$lang_admin['structstate'] = 'missing / invalid';
$lang_admin['exists'] = 'exists';
$lang_admin['table'] = 'Table';
$lang_admin['query'] = 'Query';
$lang_admin['repairstruct'] = 'Repair structure';
$lang_admin['repairdone'] = 'The database structure has been repaired.';
$lang_admin['addgateway'] = 'Add gateway';
$lang_admin['returnvalue'] = 'Return value';
$lang_admin['getstring'] = 'GET URL';
$lang_admin['defaultgateway'] = 'Default gateway';
$lang_admin['defaulttype'] = 'Default SMS type';
$lang_admin['smsvalidation_type'] = 'SMS type for validations';
$lang_admin['clndr_sms_type'] = 'SMS type for calendar SMS';
$lang_admin['mail2sms_type'] = 'SMS type for Mail-to-SMS';
$lang_admin['gateway'] = 'Gateway';
$lang_admin['gateuser'] = 'Gateway user';
$lang_admin['gatepass'] = 'Gateway password';
$lang_admin['clndrsmsabs'] = 'Calendar SMS sender';
$lang_admin['mail2smsabs'] = 'Mail-to-SMS sender';
$lang_admin['smsreplyabs'] = 'SMS-to-mail sender';
$lang_admin['datastorage'] = 'Data storage';
$lang_admin['structstorage'] = 'Structured storage';
$lang_admin['structsafewarn'] =
    'The PHP safe mode is enabled. Enabling structured data storage may lead to data loss. It is strongly advised that structured data storage is not enabled!';
$lang_admin['structrec'] =
    'The PHP safe mode is disabled. Under these circumstances the structured data storage will enhance performance. Enabling the structured data storage is recommended.';
$lang_admin['dnsblreq'] = 'Required positive tests';
$lang_admin['croninterval'] = 'Minimum cronjob interval';
$lang_admin['logouturl'] = 'Logout URL';
$lang_admin['addtype'] = 'Add type';
$lang_admin['price'] = 'Price';
$lang_admin['setdefault'] = 'Set as default';
$lang_admin['deletegroup'] = 'Delete group';
$lang_admin['groupdeletedesc'] =
    'Please select the group(s) to which the members of those groups are to be assigned that you want to delete.';
$lang_admin['dbwarn'] =
    'It is highly recommended to create a backup before running. Use at own risk.';
$lang_admin['workgroups'] = 'Workgroup';
$lang_admin['collaboration'] = 'Collaboration';
$lang_admin['share_addr'] = 'Shared addresses';
$lang_admin['share_calendar'] = 'Shared calendar';
$lang_admin['share_todo'] = 'Shared todo';
$lang_admin['share_notes'] = 'Shared notes';
$lang_admin['share_webdisk'] = 'Shared webdisc';
$lang_admin['bayesdb'] = 'Filter database';
$lang_admin['reset'] = 'Reset';
$lang_admin['bayesresetq'] =
    'Are you sure you want to reset the filter training database? The filter will not be operational again until re-training.';
$lang_admin['entries'] = 'Entries';
$lang_admin['addcoupon'] = 'Add vouchers';
$lang_admin['codes'] = 'Voucher code(s)';
$lang_admin['unlimited'] = 'unlimited';
$lang_admin['or'] = 'or';
$lang_admin['now'] = 'now';
$lang_admin['generate'] = 'Generate';
$lang_admin['chars'] = 'Characters';
$lang_admin['length'] = 'Length';
$lang_admin['benefit'] = 'Benefit';
$lang_admin['movetogroup'] = 'Move to group';
$lang_admin['addcredits'] = 'Add credits';
$lang_admin['code'] = 'Code';
$lang_admin['createbackup'] = 'Create backup';
$lang_admin['backupitems'] = 'Create backup of';
$lang_admin['approx'] = 'approx.';
$lang_admin['userdata'] = 'User data';
$lang_admin['maildata'] = 'Email data';
$lang_admin['webdiskdata'] = 'Webdisc data';
$lang_admin['statsdata'] = 'Statistics data';
$lang_admin['organizerdata'] = 'Organizer data';
$lang_admin['backupwarn'] =
    'The integrity of backups cannot be guaranteed. Use at own risk.';
$lang_admin['usagebycategory'] = 'Space use by category';
$lang_admin['category'] = 'Category';
$lang_admin['organizer'] = 'Organizer';
$lang_admin['mails'] = 'Emails';
$lang_admin['overall'] = 'Overall';
$lang_admin['usagebygroup'] = 'Memory usage by group';
$lang_admin['useraverage'] = 'User average';
$lang_admin['withoutmeta'] = 'without metadata';
$lang_admin['commonstats'] = 'Common statistics';
$lang_admin['emailstats'] = 'Email statistics';
$lang_admin['view'] = 'View';
$lang_admin['stat_login'] = 'Logins';
$lang_admin['stat_mobile_login'] = 'Mobile logins';
$lang_admin['stat_signup'] = 'Sign-ups';
$lang_admin['stat_sms'] = 'Sent SMS';
$lang_admin['stat_wd'] = 'Webdisc';
$lang_admin['stat_wd_down'] = 'Webdisc (download, MB)';
$lang_admin['stat_wd_up'] = 'Webdisc (upload, MB)';
$lang_admin['stat_receive'] = 'Received emails';
$lang_admin['stat_infected'] = 'Emails identified as infected';
$lang_admin['stat_spam'] = 'Emails identified as spam';
$lang_admin['stat_send'] = 'Sent emails';
$lang_admin['stat_send_intern'] = 'Sent emails (internal)';
$lang_admin['stat_send_extern'] = 'Sent emails (external)';
$lang_admin['stat_sysmail'] = 'System emails';
$lang_admin['day'] = 'Day';
$lang_admin['redeemedby'] = 'Redeemed by...';
$lang_admin['oldcontacts'] = 'Old contact information available.';
$lang_admin['contacthistory'] = 'Contact history';
$lang_admin['savehistory'] = 'Save contact history';
$lang_admin['discarded'] = 'Discarded';
$lang_admin['clearhistory'] = 'Clear history';
$lang_admin['charge'] = 'Charge';
$lang_admin['enablechrgpaypal'] = 'Payments by PayPal';
$lang_admin['enablechrgsu'] = 'Payments by sofort&uuml;berweisung.de';
$lang_admin['sukdnr'] = 'Customer number';
$lang_admin['suprjnr'] = 'Project number';
$lang_admin['suprjpass'] = 'Projekt password';
$lang_admin['su_createnew'] = 'Create sofort&uuml;berweisung.de project';
$lang_admin['enablecharge'] = 'Enable charge';
$lang_admin['currency'] = 'Currency';
$lang_admin['paypalacc'] = 'Paypal account';
$lang_admin['filetypes'] = 'File type(s)';
$lang_admin['addwebdiskicon'] = 'Add webdisc icon';
$lang_admin['icon'] = 'Icon';
$lang_admin['recipients'] = 'Recipients';
$lang_admin['priority'] = 'Priority';
$lang_admin['prio_-1'] = 'Low';
$lang_admin['prio_0'] = 'Normal';
$lang_admin['prio_1'] = 'High';
$lang_admin['subject'] = 'Subject';
$lang_admin['sendletter'] = 'Send newsletter';
$lang_admin['recpdetermined'] = 'Recipient determined';
$lang_admin['sendto'] = 'Send to';
$lang_admin['mailboxes'] = 'Email accounts';
$lang_admin['altmails'] = 'Alternative email addresses';
$lang_admin['mode'] = 'Mode';
$lang_admin['plaintext'] = 'Text';
$lang_admin['htmltext'] = 'HTML';
$lang_admin['team'] = 'Team';
$lang_admin['limitedextensions'] = 'Forbidden file extensions';
$lang_admin['limitedmimetypes'] = 'Forbidden MIME types';
$lang_admin['sendwelcomemail'] = 'Send welcome email';
$lang_admin['searchprovider'] = 'b1gMail search provider';
$lang_admin['includeinsearch'] =
    'Include the following sections in the user search';
$lang_admin['mailsearchwarn'] =
    'Searching a large data stock is very CPU intensive.';
$lang_admin['smsoutbox'] = 'SMS outbox';
$lang_admin['tasks'] = 'Tasks';
$lang_admin['addressbook'] = 'Addressbook';
$lang_admin['all'] = 'All';
$lang_admin['li'] = 'Logged in';
$lang_admin['nli'] = 'Not logged in';
$lang_admin['question'] = 'Question';
$lang_admin['requires'] = 'Requires';
$lang_admin['addfaq'] = 'Add FAQ';
$lang_admin['both'] = 'Both';
$lang_admin['autoperms'] = 'Automatic permission settings';
$lang_admin['autoperms_desc'] =
    'If you give the FTP-details from your b1gMail installation here, the update wizard will be able to give permission for files to be updated automatically. That will make the updating process a lot easier for you. You will not need an FTP client or the like.';
$lang_admin['ftphost'] = 'FTP host';
$lang_admin['ftpport'] = 'FTP port';
$lang_admin['ftpuser'] = 'FTP user';
$lang_admin['ftppass'] = 'FTP password';
$lang_admin['ftpdir'] = 'Directory to b1gMail';
$lang_admin['ftpperms'] = 'Default permissions';
$lang_admin['certmaillife'] = 'Certified mail storage time';
$lang_admin['days'] = 'days';
$lang_admin['searchupdatesnow'] = 'Search for new updates now.';
$lang_admin['updatesdesc'] =
    'Please click the following button to search for updates (e.g. important security updates) for your b1gMail installation. Please note that in the updating process the license number and serial number of your b1gMail license will be transmitted to our update server.';
$lang_admin['upderrordesc'] =
    'An error occurred while trying to update. Please read the following error message.';
$lang_admin['noupdatesfound'] =
    'No updates for your b1gMail installation have been found. Please try again later if necessary.';
$lang_admin['updatesfound'] =
    'The following updates for your b1gMail installation have been found.';
$lang_admin['clicktoupdate'] =
    'Please click &quot;Next &raquo;&quot; to start the update setup process.';
$lang_admin['next'] = 'Next';
$lang_admin['pleasereadme'] =
    'Please read the following information on this update carefully.';
$lang_admin['changedfiles'] =
    'Installing the update, the following files will be overwritten. Therefore they have to be writable (CHMOD 777). If you have specified your correct FTP details and if you have enabled the automatic permission settings, the update wizard will update the permissions automatically and you will not have to do anything.';
$lang_admin['filename'] = 'Filename';
$lang_admin['writeable'] = 'Writable';
$lang_admin['updating'] = 'Updating...';
$lang_admin['updateinstalled'] = 'The update has been installed.';
$lang_admin['moreupdates'] =
    'Further updates are available. Please click &quot;Next &raquo;&quot; to continue.';
$lang_admin['banners'] = 'Banners';
$lang_admin['banner'] = 'Banner';
$lang_admin['weight'] = 'Weight';
$lang_admin['views'] = 'Views';
$lang_admin['paused'] = 'Paused';
$lang_admin['pause'] = 'Pause';
$lang_admin['continue'] = 'Continue';
$lang_admin['addbanner'] = 'Add banner';
$lang_admin['vars'] = 'Variables';
$lang_admin['wddomain'] = 'Webdisc subdomain';
$lang_admin['searchfor'] = 'Search for';
$lang_admin['searchin'] = 'Search in';
$lang_admin['address'] = 'Address';
$lang_admin['searchingfor'] = 'You are searching for';
$lang_admin['detectduplicates'] = 'Detect duplicates';
$lang_admin['activity_desc1'] = 'To all users who';
$lang_admin['notloggedinsince'] = 'have not ben logged in for:';
$lang_admin['activity_desc2'] = 'apply the following operation:';
$lang_admin['undowarn'] = 'This action may perhaps be irreversible.';
$lang_admin['activity_done'] =
    'The action has been performed on <b>%d</b> users.';
$lang_admin['trash_desc'] =
    'Erase all messages from the trash folders of users who belong to the following groups:';
$lang_admin['trash_only'] = 'Only erase messages which';
$lang_admin['trash_daysonly'] = 'are older than:';
$lang_admin['trash_done'] =
    'The action has been completed. <b>%d</b> emails (%.02f MB) have been deleted.';
$lang_admin['trash_sizesonly'] = 'are larger than:';
$lang_admin['whobelongtogrps'] = 'belong to one of the following groups:';
$lang_admin['withoutpackage'] = 'Without package';
$lang_admin['package'] = 'Package';
$lang_admin['realpackage'] =
    'Are you sure you want to disable and erase the entire plugin package? Disabling plugins may permanently erase the data connected to the plugin!';
$lang_admin['install_desc2'] =
    'In the following you will find a summary of the plugin package meta data. Are you sure you want to install the package you have uploaded? Only install plugin packages from a trustworthy source because plugins may contain malicious code!';
$lang_admin['vendor'] = 'Vendor';
$lang_admin['forb1gmail'] = 'For b1gMail';
$lang_admin['yourversion'] = 'Your version';
$lang_admin['checkingsig'] = 'Checking digital signature...';
$lang_admin['sigfailed'] = 'Signature checking failed';
$lang_admin['sigfailed_desc'] =
    'The connection to the signature server for checking the plugin signature failed.';
$lang_admin['sigofficial'] = 'Official plugin package';
$lang_admin['sigofficial_desc'] =
    'This plugin package was officially released by the b1gMail project and has a valid signature. It may be installed without concern.';
$lang_admin['sigver'] = 'Verified plugin package';
$lang_admin['sigver_desc'] =
    'The source of the plugin-package has been verified. The package is not known to cause any damage or errors.';
$lang_admin['sigunknown'] = 'Unknown plugin package';
$lang_admin['sigunknown_desc'] =
    'This plugin and its source are unknown. Only install it if it is from a reliable source.';
$lang_admin['sigmal'] = 'Erroneous/malicious plugin package';
$lang_admin['sigmal_desc'] =
    'This plugin package contains serious errors or malicious code and should not be installed under any circumstances.';
$lang_admin['plugin_insterr'] =
    'The plugin could not be installed. It may already have been installed, it may be erroneous, or it may be incompatible with other packages.';
$lang_admin['plugin_installed'] = 'The plugin has been installed successfully.';
$lang_admin['sendingletter'] = 'Sending newsletter...';
$lang_admin['lockedusernames'] = 'Blocked user names';
$lang_admin['addlockedusername'] = 'Add blocked user name';
$lang_admin['username'] = 'User name';
$lang_admin['startswith'] = 'Starts with';
$lang_admin['endswith'] = 'Ends with';
$lang_admin['contains'] = 'Contains';
$lang_admin['isequal'] = 'Is equal to';
$lang_admin['recpdetection'] = 'Recipient detection';
$lang_admin['rd_static'] = 'Conventional (statically)';
$lang_admin['rd_dynamic'] = 'Use receive rules (dynamically)';
$lang_admin['searchengine'] = 'Search engine';
$lang_admin['activate'] = 'Enable';
$lang_admin['licensekey'] = 'License key';
$lang_admin['features'] = 'Features';
$lang_admin['minamount'] = 'Minimum amount (in the currency stated above)';
$lang_admin['smime'] = 'S/MIME';
$lang_admin['openssl_err'] =
    'The PHP extension <code>openssl</code> is not available. S/MIME support required the PHP <code>openssl</code> extension to be installed. Please install the extension in order to use S/MIME in b1gMail.';
$lang_admin['validity'] = 'Validity';
$lang_admin['rootcerts'] = 'Root certificates';
$lang_admin['addrootcert'] = 'Add root certificate';
$lang_admin['certfile'] = 'Certificate (.pem file)';
$lang_admin['cert_err_noca'] =
    'This certificate cannot be imported because it is not a root certificate for S/MIME purposes.';
$lang_admin['cert_err_format'] = 'The file is not a valid PEM certificate.';
$lang_admin['cert_err_exists'] = 'The certificate already exists.';
$lang_admin['cert_upload_own'] = 'Upload own certificates';
$lang_admin['cert_generate'] = 'Issue certificates';
$lang_admin['cert_ca'] = 'Certificate authority';
$lang_admin['setedit'] = 'Add / edit';
$lang_admin['cert_pleasesetca'] = 'Please add a certificate authority first.';
$lang_admin['cert_ca_info'] =
    'This certificate authority will be used to issue certificates for your users.<br /><br /><b>NOTICE:</b> Both certificate and private key will be stored unencrypted in the database!';
$lang_admin['cert_ca_current'] = 'Current certificate authority';
$lang_admin['cert_noca'] = 'You did not add a certificate authority yet.';
$lang_admin['cert_ca_import'] = 'Import certificate authority';
$lang_admin['cert_ca_file_pem'] = 'CA certificate (.pem file)';
$lang_admin['cert_ca_file_key'] = '<i>and</i> private key (.key file)';
$lang_admin['cert_ca_cert'] = 'CA certificate / private key';
$lang_admin['cert_ca_pass'] = 'Private key password (if necessary)';
$lang_admin['cert_caerr_format'] =
    'Certificate or key file are in an invalid format.';
$lang_admin['cert_caerr_purpose'] =
    'The certificate is not suitable for issuing S/MIME certificates.';
$lang_admin['cert_caerr_pkcheck'] =
    'The private key does not fit to the certificate or the password is wrong.';
$lang_admin['sum'] = 'Sum';
