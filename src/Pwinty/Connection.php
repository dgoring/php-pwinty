<?php
/**
 * A PHP implementation of the Pwinty HTTP API  v2- http://www.pwinty.com/Api
 * Originally developed by Brad Pineau for Picisto.com.
 * Updated to API Version 2 by Dan Huddart.
 * Released to public under Creative Commons.
 * Updated to API Version 3 by David L.J Goring.
 *
 * @author v1 Brad Pineau
 * @author v2 Dan Huddart
 * @author v2.1 Andy Wright
 * @author v3 David L.J Goring
 * @see https://github.com/dgoring
 * @version 3.0
 * @access public
 *
 * based on the original version for Pwinty API v1 by Brad Pineau
 *
 * Usage:
 *
 * $options = array(
 *     'api'        => 'sandbox',
 *     'merchantId' => 'xxxxxxxxxxxxxxxxx',
 *     'apiKey'     => 'xxxxxxxxxxxxxxxxx'
 * );
 * $pwinty = new Pwinty\Connection($options);
 * $catalogue = $pwinty->getCatalogue('GB', 'Pro');
 *
 */

namespace Pwinty;

class Connection
{
  private $opt        = array();
  private $endpoint   = '';
  private $last_error = '';

  /**
   * The class constructor
   */

  public function __construct($options)
  {
    $this->opt = $options;

    if ($this->opt['api'] == 'production')
    {
      $this->endpoint = 'https://api.pwinty.com/v2.1';
    }
    else
    {
      $this->endpoint = 'https://sandbox.pwinty.com/v2.1';
    }
  }

  /**
   * Sends a HTTP request to the Pwinty API. This should not be called directly.
   *
   * @param string $call The API call.
   * @return array The response returned from the API call.
   */

  public function call($call, $data, $method = 'GET')
  {
    $url = $this->endpoint . '/' . $call;

    if($method == 'GET')
    {
      $url .= '?' . http_build_query($data);
    }

    $headers = array();
    $headers[] = 'X-Pwinty-MerchantId: '   . $this->opt['merchantId'];
    $headers[] = 'X-Pwinty-REST-API-Key: ' . $this->opt['apiKey'];

    $ch = curl_init( $url );

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    if($method == 'GET')
    {
      $url .= '?' . http_build_query($data);
    }
    else
    {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

      if(count($data))
      {
        $is_json = true;
        foreach($data as $key => $value)
        {
          if($value instanceof \CURLFile)
          {
            $is_json = false;
            break;
          }
        }

        if($is_json)
        {
          $headers[] = 'Content-Type: application/json';
          $data = json_encode($data);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      }
    }

    curl_setopt($ch, CURLOPT_FAILONERROR   , 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "PHPPwinty v3");

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result_text = curl_exec($ch);
    $curl_request_info = curl_getinfo($ch);
    curl_close($ch);

    if ($curl_request_info["http_code"] == 401)
    {
      $this->last_error = "Authorization unsuccessful. Check your Merchant ID and API key.";
      return array();
    }

    $data = json_decode($result_text, true);
    return $data;
  }

  /**
   * Retrieves Order Resource
   *
   * @access public
   */

  public function orders()
  {
    return new Resource($this, Order::class);
  }

  /**
   * Retrieves information about the Catalogue
   *
   * @access public
   */

  public function catalogue($countryCode, $qualityLevel)
  {
    return $this->call('Catalogue/' . $countryCode . '/' . $qualityLevel, array(), 'GET');
  }



  /**
   * Retrieves information about the Countries
   *
   * @access public
   */

  public function countries()
  {
    return $this->call('Country', array(), 'GET');
  }
}
