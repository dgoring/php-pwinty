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

abstract class Record
{
  protected $connection;

  public static $key = 'id';

  public static $endpoint = 'Records';

  public static $cast = array();

  /**
   * Casts:
   *
   * file
   * integer
   * float
   * string
   * long
   */

  public static $resources = array();

  protected $attributes = array();
  protected $original   = array();

  protected $path;

  /**
   * The class constructor
   */

  public function __construct(&$connection, $attributes, $path)
  {
    $this->connection = $connection;

    $this->assign($attributes);
    $this->original = $attributes;

    $this->path = $path . '/' . $this->{ static::$key };
  }

  public function __get($name)
  {
    if(array_key_exists($name, $this->attributes))
    {
      return $this->attributes[ $name ];
    }
  }

  public function __set($name, $value)
  {
    $this->attributes[ $name ] = static::parse_type( $name,  $value );
  }

  public function __isset($name)
  {
    return array_key_exists($name, $this->attributes) && $this->attributes[ $name ] !== null;
  }

  public function __call($name, $args)
  {
    if(array_key_exists($name, static::$resources))
    {
      return new Resource(
        $this->connection,
        static::$resources[ $name ],
        $this->path . '/'
      );
    }
  }

  public function assign($data)
  {
    $this->attributes = array_merge($this->attributes, $data);

    return $this;
  }

  public function getDirty()
  {
    $dirty = array();

    foreach($this->attributes as $name => $value)
    {
      if(!array_key_exists($name, $this->original) || $this->original[ $name ] === null || $value != $this->original[ $name ])
      {
        $dirty[ $name ] = $value;
      }
    }

    return $dirty;
  }

  public static function saveData($input)
  {
    $data = array();

    foreach($input as $name => $value)
    {
      $data[ $name ] = static::parse_type($name, $value, true);
    }

    return $data;
  }

  public function save()
  {
    if(count($data = self::saveData($this->attributes)))
    {
      return $this->connection->call($this->path, $data, 'PUT');
    }
  }

  public function destroy()
  {
    return $this->connection->call($this->path, array(), 'DELETE');
  }

  public static function parse_type($name, $value, $reverse = false)
  {
    $type = isset(static::$cast[ $name ]) ? static::$cast[ $name ] : '';

    if( $value === null )
    {
      return;
    }

    switch ($type)
    {
      case 'file':
        if(!$value instanceof \CURLFile)
        {
          $value = new \CURLFile($value);
        }
      break;
      case 'float':
        $value = floatval($value);
      break;
      case 'integer':
        $value = intval($value);
      break;
      case 'datetime':
        if(!is_object($value))
        {
          try{
            $value = new \DateTime( $value );
          } catch(\Exception $ex) {
            $value = null;
          }
        }

        $reverse ? $value = $value->formatDB() : null;
      break;
    }

    return $value;
  }

  public function __debugInfo()
  {
    return $this->attributes;
  }
}
