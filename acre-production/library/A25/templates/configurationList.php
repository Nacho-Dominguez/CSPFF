These are the settings for this server.  To edit a setting, please e-mail
Jonathan Albright at <a
href="mailto:jonathan@appdevl.net">jonathan@appdevl.net</a>.
<br/>
<hr/>
<table>
<tr>
<td>Administrator Email Address:</td>
<td><?echo ServerConfig::adminEmailAddress?></td>
</tr>
<tr>
<td>Supply Request Recipient:</td>
<td><?echo ServerConfig::supplyRequestRecipientEmailAddress()?></td>
</tr>
<tr>
<td>Timesheet Recipient:</td>
<td><?echo ServerConfig::timesheetRecipientEmailAddress()?></td>
</tr>
<tr>
<td>State name:</td>
<td><?echo PlatformConfig::STATE_NAME?></td>
</tr>
<tr>
<td>Agency name (displayed on certificate):</td>
<td><?echo PlatformConfig::agency?></td>
</tr>
<tr>
<td>Phone number:</td>
<td><?echo PlatformConfig::phoneNumber?></td>
</tr>
<tr>
<td>Fax number:</td>
<td><?echo PlatformConfig::faxNumber?></td>
</tr>
<tr>
<td>Business hours:</td>
<td><?echo PlatformConfig::businessHours?></td>
</tr>
<tr>
<td>Mailing address name:</td>
<td><?echo PlatformConfig::mailingAddressName?></td>
</tr>
<tr>
<td>Authorize.net Login:</td>
<td><?echo PlatformConfig::AUTHORIZE_NET_LOGIN?></td>
</tr>
<tr>
<td>Authorize.net Transaction Key:</td>
<td><?echo PlatformConfig::AUTHORIZE_NET_TRAN_KEY?></td>
</tr>
<tr>
<td valign="top">Text displayed if student forgets login info:</td>
<td valign="top"><?echo PlatformConfig::forgotLoginContactInfo()?></td>
</tr>
<tr>
<td valign="top">Payment Instructions:</td>
<td><?echo PlatformConfig::paymentInstructions()?></td>
</tr>
</table>
