<?php include("open_database.php"); ?> 

<?php
$this_page = basename($_SERVER['REQUEST_URI']);
$embassy_cocode = strtoupper(substr($this_page,0,2));

// $embassy_cocode = $_GET["cocode"];
$result = mysql_query("SELECT * FROM embassy
      where cocode = '$embassy_cocode'", $connection);
 $count = @mysql_num_rows($result);
   if ($count == 0) { 
       die("Database query failed.");
   }
$row = mysql_fetch_assoc($result);

   $page_title = "Embassy.org: " . $row['formal_name'];

   $address_text = " ";
   if ($row['street_address']) { $address_text = $row['street_address'] . ', ' . $row['city'] . ' ' . $row['state'] . ' ' . $row['zip']; }
   $address_url = 
           '<dt>Map:</dt> <dd><a href="http://maps.google.com/?q='
           . $address_text . 
           '" target="_new"><span class="glyphicon glyphicon-map-marker"></span></a></dd>';
   $phone_text = " ";
   if ($row['telephone']) { $phone_text = '<dt>Phone:</dt> <dd>' . $row['telephone'] . '</dd>'; }
   $fax_text = " ";
   if ($row['fax']) { $fax_text = '<dt>Fax:</dt> <dd>' . $row['fax'] . '</dd>'; }
$email_text = " ";
   if ($row['email']) $email_text = '<dt>Email:</dt> <dd><a href="mailto:' .
           $row['email'] . '">' . $row['email'] . '</a></dd>'; 
   $url_text = " ";
   if ($row['url']) $url_text = '<dt>Web:</dt> <dd><a href="' .
           $row['url'] . '">' . $row['url'] . '</a></dd>';
   $fb_text = " ";
   if ($row['facebook']) $fb_text = '<dt><a href="' .
           $row['facebook'] . 
           '"><img src="/images/facebook-32px.png" align=middle ' . 
           'width=32 height=32 vspace=2 alt="Facebook"></a></dt> <dd>' .
           'Embassy of ' . $row['short_name'] . ' on Facebook</dd>';
   $twitter_text = " ";
   if ($row['twitter']) $twitter_text = '<dt><a href="' .
           $row['twitter'] . 
           '"><img src="/images/twitter-32px.png" align=middle ' . 
           'width=32 height=32 vspace=2 alt="Twitter"></a></dt> <dd>' .
           'Embassy of ' . $row['short_name'] . ' on Twitter</dd>';
  
include("mobile-header.php"); 

echo <<<EMBP1
<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-8">
            <h1 class="text-center">
EMBP1;
echo $row['formal_name'];
echo <<<EMBP2
</h1>
            <div class="row">
EMBP2;

// Display embassy image if there is one

if ($row[emb_image] != 0)
   {
    $image_text_result = mysql_query("SELECT * FROM image WHERE image_id = '$row[emb_image]'", $connection);
    $image_text_record = mysql_fetch_assoc($image_text_result);
echo <<<EMBi1
     <div class="col-sm-4 col-md-4">   
         <div class="thumbnail">
              <img src="
EMBi1;
     echo $image_text_record[image_path] . "\" alt=\"" . $image_text_record[caption] . "\" height=\"" .
         $image_text_record[y_dim] . "\" width=\"" . $image_text_record[x_dim] . "\">\n";
     echo "<div class=\"caption\">\n<p><small>" . $image_text_record[caption] . "</small></p>\n";
     echo "</div>\n</div>\n</div>\n";
   }

// Display embassy basics

echo <<<EMBP3
                <div class="col-sm-8 col-md-8 address-listing">   

                    <dl class="dl-horizontal more-space">
                    <dt>Address:</dt>
                        <dd>{$address_text}</dd>
                    {$address_url} 
                    {$phone_text}
                    {$fax_text}
                    {$email_text}
                    {$url_text}
                    {$fb_text}
                    {$twitter_text}
                    </dl>             

EMBP3;

// Display chief of mission

// Link to page of consular and other offices



   $ad_2_text_result = mysql_query("SELECT * FROM ad_index WHERE ad_id = '$row[ad_slot_2]'", $connection);
   $ad_text_record = mysql_fetch_assoc($ad_2_text_result);
   echo $ad_text_record['content'];

echo <<<EMBP4
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Personnel</h3>
                </div>
                <div class="panel-body">
                    <p><strong>Chief of Mission:</strong> 
EMBP4;
echo $row[amb_full_name] . ", " . $row[amb_title];
echo <<<EMBP5
</p>
                </div>
            </div>
EMBP5;

echo "<div class=\"well center-block quotation\">\n";
   $quote_result = mysql_query("SELECT * FROM quotes ORDER BY RAND()", $connection);
   $quote_row = mysql_fetch_assoc($quote_result);
   echo "<p>\"";
   echo $quote_row['quote'] . "\" <br /> - " . $quote_row['author'] . "</p>";
   echo "\n</div>\n";

echo <<<EMBP6
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Other offices</h3>
                </div>
                <div class="panel-body">
                    <p><a href="/embassies/
EMBP6;
echo substr($this_page,0,2);
echo "-other.html\">Consular and other offices</a> of ";
echo $row[formal_name] . " in the United States.</p>";
echo <<<EMBP7
                </div>
            </div>

        </div>
        <div class="col-md-4">
EMBP7;

   $ad_1_text_result = mysql_query("SELECT * FROM ad_index WHERE ad_id = '$row[ad_slot_1]'", $connection);
   $ad_text_record = mysql_fetch_assoc($ad_1_text_result);
   echo $ad_text_record['content'];

echo "\n</div>\n</div>\n";

include("mobile-footer.php"); 
include("close_database.php"); 
?>
