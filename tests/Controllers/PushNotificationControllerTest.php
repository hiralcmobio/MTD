<?php

namespace Tests\Controllers;

use Auth;
use App\User;
use Tests\TestCase;
use Edujugon\PushNotification\PushNotification;

class PushNotificationControllerTest extends TestCase
{

    /**
     * Test For Push Notification Without Any Argument Using GCM.
     *
     * @return void
     */
    /** @test */
    public function pushNotificationWithoutArgumentWithGcm()
    {
        $push = new PushNotification();
        $this->assertInstanceOf('Edujugon\PushNotification\Gcm', $push->service);
    }

    /**
     * Test For Unregistered Device Token.
     *
     * @return void
     */
    /** @test */
    public function unregisteredDeviceTokens()
    {
        $push = new PushNotification();
        $push->setApiKey('wefwef23f23fwef')
            ->setDevicesToken([
                'asdfasdfasdfasdfXCXQ9cvvpLMuxkaJ0ySpWPed3cvz0q4fuG1SXt40-oasdf3nhWE5OKDmatFZaaZ',
                'asfasdfasdf_96ssdfsWuhabpZO9Basvz0q4fuG1SXt40-oXH4R5dwYk4rQYTeds3nhWE5OKDmatFZaaZ'
            ])
            ->setConfig(['dry_run' => true])
            ->setMessage(['message' => 'hello world'])
            ->send();
        $this->assertIsArray($push->getUnregisteredDeviceTokens());
    }

    /**
     * Test For Set Message Data.
     *
     * @return void
     */
    /** @test */
    public function setMessageData()
    {
        $push = new PushNotification();
        $push->setMessage(['message' => 'hello world']);
        $this->assertArrayHasKey('message', $push->message);
        $this->assertEquals('hello world', $push->message['message']);
    }

     /**
     * Test For Send Notification With Topic Using FCM.
     *
     * @return void
     */
    /** @test */
    public function sendNotificationByTopicWithFcm()
    {
        $push = new PushNotification('fcm');
        $response = $push->setMessage(['message' => 'Hello World'])
            ->setApiKey('asdfasdffasdfasdfasdf')
            ->setConfig(['dry_run' => false])
            ->sendByTopic('test')
            ->getFeedback();
        $this->assertInstanceOf('stdClass', $response);
    }
}
