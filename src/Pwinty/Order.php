<?php
/**
 * A PHP implementation of the Pwinty HTTP API  v2- http://www.pwinty.com/Api
 * Originally developed by Brad Pineau for Picisto.com. Updated to API Version 2 by Dan Huddart. Released to public under Creative Commons.
 *
 *
 * @author v3 David Goring
 * @see https://github.com/dgoring
 * @version 3.0
 *
 *
 */

namespace Pwinty;

class Order extends Record
{
  public static $endpoint = 'Orders';

  public static $resources = array(
    'photos' => Photo::class,
    'issues' => Issue::class,
  );

  public static $readonly = array(
    'shippingInfo',
    'price',
    'status',
    'paymentUrl',
  );

  protected $attributes = array(
    'recipientName'          => null,
    'email'                  => null,
    'address1'               => null,
    'address2'               => null,
    'addressTownOrCity'      => null,
    'stateOrCounty'          => null,
    'postalOrZipCode'        => null,
    'countryCode'            => null,
    'destinationCountryCode' => null,
    'useTrackedShipping'     => null,
    'payment'                => null,
    'qualityLevel'           => null,
  );

  public function validate()
  {
    return $this->connection->call($this->path . '/SubmissionStatus', array(), 'GET');
  }

  public function submit($status)
  {
    return $this->connection->call($this->path . '/Status', compact('status'), 'POST');
  }
}
