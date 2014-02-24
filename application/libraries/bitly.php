<?php
class bitly
{
  /*
   * variable declarations
  */
  private $API_URL  = 'http://api.bit.ly';
  private $API_KEY  = 'R_2c93b451ae1d563b15b9bc6680b939c0'; //change this to your API key, get one from http://bit.ly/account/your_api_key
  private $login  = 'hsinyu'; //change this to your login name
  private $action  = array('shorten'=>'longUrl','stats'=>'shortUrl','expand'=>'shortUrl');
  private $query_string,$current_action;
  private $URL  = '';
  private $version= '2.0.1';
  var $result = '';
 
 
  function __construct($display=false)
  {
    $this->return=$display;
 
  }
  function stats($short_url)
  {
    $this->current_action='stats';
    return $this->handle_request($short_url);
  }
  function expand($short_url)
  {
    $this->current_action='expand';
    return $this->handle_request($short_url);
  }
  function shorten($short_url)
  {
    $this->current_action='shorten';
    return $this->handle_request($short_url);
  }
  function handle_request($url)
  {
    $URL = urldecode(trim($url));
    $this->URL=$this->API_URL."/$this->current_action".$this->make_query_string(array($this->action[$this->current_action] => $url));
    $results=json_decode($this->makeCurl());
    return $results->results;
  }
  private function make_query_string($extra_param = array())
  {
 
    $this->query_string='';
    $this->URL='';
    $this->query_string.='?apiKey='.$this->API_KEY;
    $this->query_string.='&version='.$this->version;
    $this->query_string.='&login='.$this->login;
 
    if(count($extra_param)>0)
    {
      foreach($extra_param as $key=>$value)
        $this->query_string.='&'.$key.'='.$value;
    }
    return $this->query_string;
 
  }
  private function makeCurl()
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $this->URL);
    $this->result = curl_exec($curl);
    curl_close($curl);
    if($this->return)
      echo $this->result ;
    else
      return $this->result;
 
  }
 
} //class ends here
?>


<?
/*
$url='http://www.digimantra.com/contests/free-domain-contest-digimantra/'; //specify your own url to be shortened 
 
$bit=new bitly(); //create object
$result=$bit->shorten($url); //shortens a long URL
$result=$result->$url; //stores result in the array
$short_url=$result->shortUrl; //extract the short url from result array
print_r($bit->expand($short_url)); //expand the shorten url
print_r($bit->stats($short_url)); //get stats from bit.ly

*/
?>