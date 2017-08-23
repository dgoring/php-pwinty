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

class Issue extends Record
{
  public static $endpoint = 'Issues';

  protected $attributes = array(
    'issue'          => null,
    'issueDetail'    => null,
    'action'         => null,
    'actionDetail'   => null,
    'affectedImages' => null,
  );
}
