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
      $this->endpoint = 'https://api.pwinty.com/v2.3';
    }
    else
    {
      $this->endpoint = 'https://sandbox.pwinty.com/v2.3';
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

    $request = curl_init( $url );

    curl_setopt($request, CURLOPT_VERBOSE, true);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($request, CURLOPT_TIMEOUT, 30);

    if($method == 'GET')
    {
      $url .= '?' . http_build_query($data);
    }
    else
    {
      curl_setopt($request, CURLOPT_POST, true);
      curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);

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
        else
        {
          $data = $this->multipartFormData($data);
        }

        curl_setopt($request, CURLOPT_POSTFIELDS, $data);
      }
    }

    curl_setopt($request, CURLOPT_FAILONERROR   , 0);
    curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($request, CURLOPT_USERAGENT, "PHPPwinty v3");

    curl_setopt($request, CURLOPT_HTTPHEADER, $headers);

    $content = curl_exec($request);
    $response_header = curl_getinfo($request);
    curl_close($request);

    $data = json_decode($content);

    if (floor($response_header["http_code"] / 200) != 1)
    {
      switch($response_header["http_code"])
      {
        case 400:
          // Do Nothing
        break;
        case 401:
          throw new \Exception('Un-Authorized: Check your Merchant ID and API key.');
        case 403:
          throw new \Exception('Forbidden: Invalid Request for Resource');
        case 404:
          throw new \Exception('Not Found');
        case 500:
          throw new \Exception('Server Error');
        default:
          throw new \Exception('Unknown Error');
      }
    }

    return $data;
  }

  private function multipartFormData($array, $parent = null)
  {
    $parent = $parent ?: array();
    $final  = array();

    foreach($array as $name => $value)
    {
      $naming = array_merge($parent, array($name));

      if(is_array($value))
      {
        $final = array_merge($final, $this->multipartFormData($value, $naming));
      }
      else
      {
        $name  = '';
        $first = true;

        for($n = 0; $n < count($naming); $n++)
        {
          $name .= $first ? $naming[ $n ] : '[' . $naming[ $n ] . ']';
          $first = false;
        }

        $final[ $name ] = $value;
      }
    }

    return $final;
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
