# PHP Pwinty v3.0

A PHP implementation of the Pwinty HTTP API v2.3

## Installation

### Composer

Add the following to the require section of your composer.json file:

    "dgoring/php-pwinty": "^3.0"

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
    $order = $pwinty->orders()->create(array(
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
    ));

    //edit an order
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

    //edit a photo
    $photo->assign(array(
        'copies'       => 1,
        'sizing'       => 'ShrinkToFit',
    ));

    $photo->save();

    //delete a photo
    $order = $pwinty->orders()->find( 123 );

    $photo = $order->photos()->find( 123 );

    $photo->destroy();

Issues

    //gets information about issues for an order
    $order = $pwinty->orders()->find( 123 );

    $issues = $order->issues()->get();

    //gets information about a single issue
    $order = $pwinty->orders()->find( 123 );

    $issue = $order->issues()->find( 123 );

    //adds a issue
    $order = $pwinty->orders()->find( 123 );

    $issue = $order->issues()->create(array(
        'issue'          => 'WrongFrameColour',
        'issueDetail'    => 'It wasn\'t pink!',
        'action'         => 'Reprint',
        'actionDetail'   => 'Needs to be PINK!',
        'affectedImages' => '123,673,123',
    ));

    //edit a issue
    $issue->assign(array(
       'comment' => 'Still not Pink',
    ));

    $issue->save();

    //delete a issue
    $order = $pwinty->orders()->find( 123 );

    $issue = $order->issues()->find( 123 );

    $issue->destroy();
