<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'key' => 'adminCommission',
                'value' => json_encode([
                    'amount' => '',
                    'isEnabled' => false,
                    'type' => 'fix',
                ]),
            ],
            [
                'key' => 'contact_us',
                'value' => json_encode([
                    'address' => 'Your Address',
                    'email' => 'support@comingbro.in',
                    'subject' => 'Coming Bro Customer Feedback',
                    'phone' => '+919028777184',
                    'supportURL' => 'https://comingbro.in/#contact-us',
                ]),
            ],
            [
                'key' => 'globalKey',
                'value' => json_encode([
                    'serverKey' => '',
                    'googleMapKey' => 'AIzaSyCNuKTh5QflCv7kPbeMWYTYaasmr84nwuI',
                ]),
            ],
            [
                'key' => 'globalValue',
                'value' => json_encode([
                    'distanceType' => 'Km',
                    'minimumAmountToWithdrawal' => '1',
                    'minimumDepositToRideAccept' => '0',
                    'mapType' => 'inappmap',
                    'selectedMapType' => 'google',
                    'orderRingtoneUrl' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/audio%2FI%20am%20coming%20Bro%20MALE.._1750487104099.mp3?alt=media&token=da1e91ea-0f5c-4c69-b939-7e86b458280e',
                    'documentExpiryNotification' => '30',
                    'radius' => '3',
                    'driverLocationUpdate' => '100',
                ]),
            ],
            [
                'key' => 'logo',
                'value' => json_encode([
                    'appLogo' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/images%2FCoB_20241118_231138_0000_1732171546240.png?alt=media&token=987e4fed-3bef-4715-8667-378712b6580a',
                    'appFavIconLogo' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/images%2FCoB_20241118_231138_0000_1732171549756.png?alt=media&token=21799fc7-c3ae-4cff-bec9-be4ad86ea81c',
                ]),
            ],
            [
                'key' => 'notification_setting',
                'value' => json_encode([
                    'senderId' => '541206164526',
                    'serviceJson' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/coming-bro-firebase-adminsdk-ie6t2-10dcad3da4_1732883365690.json?alt=media&token=4593d6e3-98f9-4646-a417-f1a984755e60',
                ]),
            ],
            [
                'key' => 'payment',
                'value' => json_encode([
                    'cash' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fcashondelivery_1722318973961.png?alt=media&token=b35517b7-115d-4bfd-911b-04b725a3f839',
                        'enable' => true,
                        'name' => 'Cash',
                    ],
                    'paypal' => [
                        'braintree_privatekey' => '',
                        'braintree_tokenizationKey' => '',
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fpaypal_1696847455602.png?alt=media&token=c209b404-83a3-453e-b340-470f1db887ed',
                        'paypalpassword' => '',
                        'paypalUserName' => '',
                        'paypalAppId' => '',
                        'name' => 'Paypal',
                        'paypalSecret' => '',
                        'braintree_publickey' => '',
                        'braintree_merchantid' => '',
                        'enable' => false,
                        'isSandbox' => false,
                    ],
                    'paytm' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fpaytm_1696847502484.png?alt=media&token=64e9a29f-9def-41bd-a1b2-5acf98fc0aa9',
                        'name' => 'Paytm',
                        'paytmMID' => '',
                        'isSandbox' => false,
                        'merchantKey' => '',
                        'enable' => false,
                    ],
                    'payfast' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fpayfast_1696847658735.png?alt=media&token=b7fa0f20-2e67-42d7-b560-d2f4c7e5fdd8',
                        'merchantId' => '',
                        'name' => 'PayFast',
                        'return_url' => 'https://yourdomain.com/success',
                        'notify_url' => 'https://yourdomain.com/notify',
                        'cancel_url' => 'https://yourdomain.com/cancel',
                        'merchantKey' => '',
                        'enable' => false,
                        'isSandbox' => false,
                    ],
                    'payStack' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fpaystack_1722319382199.png?alt=media&token=346b310c-3fc0-4f15-aedc-205ac75671f2',
                        'secretKey' => '',
                        'name' => 'PayStack',
                        'callbackURL' => 'https://yourdomain.com/success',
                        'publicKey' => '',
                        'webhookURL' => 'https://yourdomain.com/success',
                        'enable' => false,
                        'isSandbox' => false,
                    ],
                    'flutterWave' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fflutter_wave_1722319539111.png?alt=media&token=fd469284-9901-4a7b-9a80-dc09cd6459f1',
                        'secretKey' => '',
                        'name' => 'FlutterWave',
                        'encryptionKey' => '',
                        'publicKey' => '',
                        'enable' => false,
                        'isSandbox' => false,
                    ],
                    'mercadoPago' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fmarcado_pago_1696847910780.png?alt=media&token=6fb79ae3-ca12-4015-9bef-8c875fc5837f',
                        'name' => 'MercadoPago',
                        'publicKey' => '',
                        'accessToken' => '',
                        'isSandbox' => false,
                        'enable' => false,
                    ],
                    'wallet' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fgoride_wallet1-(1)_1722320078246.png?alt=media&token=ed4fa104-f44f-40d2-a478-c49581aec709',
                        'name' => 'Wallet',
                        'enable' => false,
                    ],
                    'strip' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fstripe_1722318458087.png?alt=media&token=39f4079b-f992-44ad-a98e-a203fe82b6c9',
                        'name' => 'Stripe',
                        'clientpublishableKey' => env('STRIPE_PUBLISHABLE_KEY', ''),
                        'stripeSecret' => env('STRIPE_SECRET_KEY', ''),
                        'enable' => false,
                        'isSandbox' => false,
                    ],
                    'razorpay' => [
                        'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Frazorepay_1722319277105.png?alt=media&token=10b4cd9c-970d-433a-8437-228871e3ad4b',
                        'name' => 'RazorPay',
                        'isSandbox' => false,
                        'razorpayKey' => env('RAZORPAY_KEY', ''),
                        'razorpaySecret' => env('RAZORPAY_SECRET', ''),
                        'enable' => true,
                    ],
                ]),
            ],
            [
                'key' => 'referral',
                'value' => json_encode([
                    'referralAmount' => '0',
                    'referralRide' => '1',
                    'referralCustomer' => '5',
                ]),
            ],
        ];

        foreach ($records as $record) {
            DB::table('settings')->updateOrInsert(['key' => $record['key']], $record);
        }

        // Seed settings with large HTML content from settings.json
        $this->seedFromJson();
    }

    private function seedFromJson(): void
    {
        $jsonPath = base_path('firebase_collection/settings.json');

        if (!file_exists($jsonPath)) {
            return;
        }

        $allRecords = json_decode(file_get_contents($jsonPath), true);

        // Keys with large HTML content that are better loaded from file
        $htmlKeys = ['global', 'footerTemplate', 'headerTemplate', 'landingPageTemplate'];

        foreach ($allRecords as $record) {
            if (!in_array($record['id'], $htmlKeys)) {
                continue;
            }

            $key = $record['id'];
            unset($record['id']);

            // For 'global', load privacyPolicy and termsAndConditions from cms_pages.json
            if ($key === 'global') {
                $cmsPath = base_path('firebase_collection/cms_pages.json');
                if (file_exists($cmsPath)) {
                    $cmsRecords = json_decode(file_get_contents($cmsPath), true);
                    foreach ($cmsRecords as $cms) {
                        if ($cms['slug'] === 'page-privacy-policy') {
                            $record['privacyPolicy'] = $cms['description'] ?? '';
                        }
                    }
                }
            }

            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'key' => $key,
                    'value' => json_encode($record),
                ]
            );
        }
    }
}
