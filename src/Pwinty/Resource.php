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

class Resource
{
  private $connection;
  private $endpoint;
  private $class;

  /**
   * The class constructor
   */

  public function __construct(&$connection, $class, $endpoint = '')
  {
    $this->connection = $connection;

    $this->class      = $class;

    $this->endpoint   = $endpoint . $class::$endpoint;
  }

  public function get($data = array())
  {
    $result = $this->connection->call($this->endpoint, $data, 'GET');

    return $this->cast($result);
  }

  public function find($id, $load = true)
  {
    if($load)
    {
      $result = $this->connection->call($this->endpoint . '/' . $id, array(), 'GET');
    }
    else
    {
      $class = $this->class;

      $result = array($class::$key => $id);
    }

    $records = $this->cast(array($result));

    return reset($records);
  }

  public function create($data)
  {
    $class = $this->class;

    $data = $class::saveData($data);

    $result = $this->connection->call($this->endpoint, $data, 'POST');

    $records = $this->cast(array($result));

    return reset($records);
  }

  protected function cast($data)
  {
    if(!is_array( $data ))
    {
      $data = array( $data );
    }

    $class = $this->class;

    $records = array();

    foreach($data as $attributes)
    {
      $records[] = new $class($this->connection, $attributes, $this->endpoint);
    }

    return $records;
  }
}
