<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class SendAwsSnsSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sns = App::make('aws')->createClient('sns');

        $sns->publish([
            'Message' => "こんにちは\nアイウエオ",
            'PhoneNumber' => '+81xxxxxxxx',
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType'    => 'String',
                    'StringValue' => '080-xxxx-xxxx'
                ]
            ]
        ]);

        $message = 'This message is sent from a Amazon SNS code sample.';
        $topic = 'arn:〜〜〜';


//        $sns->subscribe([
//            'Protocol'              => 'sms',
//            'Endpoint'              => '+818011111111',
//            'ReturnSubscriptionArn' => true,
//            'TopicArn'              => $topic,
//        ]);

//        $sns->publish([
//            'Message'  => $message,
//            'TopicArn' => $topic,
//        ]);
    }
}
