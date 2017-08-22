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

class Photo extends Record
{
  public static $endpoint = 'Photos';

  public static $cast = array(
    'file' => 'file',
  );

  protected $attributes = array(
    'type'         => null,
    'url'          => null,
    'file'         => null,
    'md5Hash'      => null,
    'copies'       => null,
    'sizing'       => null,
    'price'        => null,
    'priceToUser'  => null,
    'previewUrl'   => null,
    'thumbnailUrl' => null,
    'attributes'   => array(),
  );
}
