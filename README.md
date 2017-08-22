# PHP Pwinty v3.0

A PHP implementation of the Pwinty HTTP API v2.1

Based on an implementation of API v1 by Brad Pineau

## Installation

### Composer

Add the following to the require section of your composer.json file:

    "dgoring/php-pwinty": "dev-master"

Declare a new instance of php-pwinty

    $config = array(
        'api'        => 'sandbox',
        'merchantId' => 'xxxxxxxxxxxxxxxxx',
        'apiKey'     => 'xxxxxxxxxxxxxxxxx'
    );
    $pwinty = new Pwinty\Connnection($config);

## Example Usage

Catalogue

    $catalogue = $pwinty->catalogue(
        "GB",               //country code
        "Pro"               //quality
    );

Countries

    $countries = $pwinty->countries();

Orders
    //gets all orders
    $orders = $pwinty->orders()->get();

    //gets one order
    $order = $pwinty->orders()->find( 123 );

    //creates a new order
    $order = $pwinty->orders()->create(
        'name'                   => 'Chuck Norris',
        'email'                  => 'chuck@norris.com',
        'address_1'              => '123 Some Road',
        'address_2'              => 'Some place',
        'town'                   => 'Some town',
        'state'                  => 'Some state',
        'postalOrZipCode'        => '12345',
        'countryCode'            => 'GB',
        'destinationCountryCode' => 'GB',
        'useTrackedShipping'     => true,
        'payment'                => 'InvoiceMe',
        'qualityLevel'           => 'Pro'
    );

    //updates an order
    $order = $pwinty->orders()->find( 123 );

    $order->name = 'Bob';

    $order = $pwinty->assign(array(
        'name'                   => 'Chuck Norris',
        'email'                  => 'chuck@norris.com',
        'address_1'              => '123 Some Road',
        'address_2'              => 'Some place',
        'town'                   => 'Some town',
        'state'                  => 'Some state',
        'postalOrZipCode'        => '12345',
    ));

    $order->save();

    //change order status
    $order = $pwinty->orders()->find( 123 );

    $pwinty->submit(
        'Cancelled'  //status
    );

Photos

    //gets information about photos for an order
    $order = $pwinty->orders()->find( 123 );

    $photos = $order->photos()->get();

    //gets information about a single photo
    $order = $pwinty->orders()->find( 123 );

    $photo = $order->photos()->find( 123 );

    //adds a photo
    $order = $pwinty->orders()->find( 123 );

    $photo = $order->photos()->create(array(
        'type'         => 'fridge_magnet',
        'url'          => 'http://example.com/photo.jpg',
        'file'         => './path/to/file,
        'copies'       => 1,
        'sizing'       => 'ShrinkToFit',
        'price'        => 200,
        'priceToUser'  => 240,
        'attributes'   => array(),
    ));

    //delete a photo
    $order = $pwinty->orders()->find( 123 );

    $photo = $order->photos()->find( 123 );

    $photo->destroy();
