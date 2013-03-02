dtdctracker
===========

<h1>Integrate DTDC tracker in your site</h1>
<a href="http://www.mrova.com/?p=1191">http://www.mrova.com/?p=1191</a>
<h3>How To Use?</h3>

inlcude('Sny_Dtdc_Tracker.php');<br/>
$dtdc = new Sny_Dtdc_Tracker(TRACKING ID);<br/>
var_dump($dtdc -> summary);<br/>

<b>OUTPUT:</b><br/>
array(4) { ["AWB / Ref. No."]=> string(30) "   " ["Status"]=> string(9) "DELIVERED" ["Date Time"]=> string(199) " Wed, Feb 27, 2013  2:25 PM " ["Location"]=> string(9) "BANGALORE" }
