<?php
/**
 * Integrate DTDC tracker in your site
 *
 * GNU Public License 3.0
 * Copyright (C) 2013  Sunny Luthra
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Requirements
 * 1. PHP :)
 * 2. CURL
 */


include(dirname(__FILE__) . '/simple_html_dom.php');

class Sny_Dtdc_Tracker{
    private $tracking_summary_url;
    private $tracking_details_url;
    private $user_agent;
    private $cookie;
    private $tracking_id_type;
    private $curl_config;
    private $tracking_no;

    private $html;
    private $curl;

    public $details;
    public $summary;

    /**
     * Constructor Function
     * @param $tracking_no
     * @since  1.0.0
     * @return  void
     */
    public function __construct($tracking_no){

        $this -> tracking_no = $tracking_no;
        if(empty($this -> tracking_no))
            die("Dude! I can't read your mind, please provide tracking id");

        $this -> tracking_id_type = "awb_no";

        //Initialize our dom parsing class
        $this -> html = new simple_html_dom();
        //Initialize curl
        $this -> curl = curl_init();

        //DTDC tracking url
        $this -> tracking_summary_url = "http://www.dtdc.in/dtdc-corporate-web_liferay/trackingAction.do?method=submitTrackingIds";
        $this -> tracking_details_url = "";
        $this -> user_agent   = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";

        //File to save cookies returned by dtdc
        //Make sure that script have read write capabilities on cookie file
        $this -> cookie = dirname(__FILE__) . "/cookie.txt";

        //Curl Settings
        $this -> curl_config = array(
            CURLOPT_USERAGENT      => $this -> user_agent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR      => $this -> cookie,
            CURLOPT_COOKIEFILE     => $this -> cookie
        );

        //Array to save details and summary data
        $this -> details = array();
        $this -> summary = array();

        //rock n roll
        $this -> init();
    }

    private function init(){
        $summary_curl_config = $this -> curl_config + array(
                 CURLOPT_URL        => $this -> tracking_summary_url,
                 CURLOPT_POST       => true,
                 CURLOPT_POSTFIELDS => array(
                 'to.awbNo'       => $this -> tracking_no,
                 'to.trackIdType' => $this -> tracking_id_type,
            )
        );

        curl_setopt_array($this -> curl, $summary_curl_config);
        $result = curl_exec($this -> curl);

        $this -> html -> load($result);

        $e              = $this -> html -> find("table",1);
        $summary_header = $e ->children(0)->children(0);
        $summary_body   = $e ->children(1)->children(0);

        $no             = $summary_header -> children(0)->innertext;
        $status         = $summary_header -> children(1) -> innertext;
        $datetime         = $summary_header -> children(2) -> innertext;
        $location         = $summary_header -> children(3) -> innertext;

        $no_value             = $summary_body -> children(0)->plaintext;
        $status_value         = $summary_body -> children(1) -> innertext;
        $datetime_value         = $summary_body -> children(2) -> innertext;
        $location_value         = $summary_body -> children(3) -> innertext;

        $this -> summary = array(
            $no => $no_value,
            $status => $status_value,
            $datetime => $datetime_value,
            $location => $location_value);



        curl_close($this -> curl);

    }
}
$dtdc = new Sny_Dtdc_Tracker('X02147203');
var_dump($dtdc -> summary);