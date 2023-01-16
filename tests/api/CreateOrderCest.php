<?php
/**
 * These test cases are writtern using codeception as Testing Tool
 * I am writing REST API Testing 
 * I am creating four methods to test my GET, POST, PUT and DELETE method.
 * These methods are used to GET, ADD, EDIT and Delete my Order Lists
 */
namespace orderUnitTest;
use orderUnitTest\ApiTester;
class CreateOrderCest
{
    // Test Get Order
    public function getOrderViaAPI(ApiTester $I)
    {
        $I->sendGet('/orders.php', ['id', 1]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    // Test Add Order
    public function createOrderViaAPI(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/orders.php', [
          'name' => 'Ashik',
          'state' => 'Karnataka',
          'zip' => 560093,
          'amount' => 15000,
          'qty' => 4,
          'item' => 'AG56JI'
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true,"message":"Order added successfully.","response":[]}');
        
    }

    // Test Edit Order
    public function editOrderViaAPI(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPut('/orders.php/1', [
          'name' => 'Ashik',
          'state' => 'Tamilnadu',
          'zip' => 582256,
          'amount' => 15000,
          'qty' => 4,
          'item' => 'AG56JI'
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true,"message":"Order updated successfully.","response":[]}');
    }

    // Test Delete Order
    public function deleteOrderViaApi(ApiTester $I)
    {
        $orderId = 5;
        $I->sendDelete('/orders.php/'.$orderId);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
