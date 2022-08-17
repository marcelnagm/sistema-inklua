<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'content_id',
        'order_id',
        'customer_id',
        'charges_id',
        'code',
        'payment_method',
        'amount',
        'customer_delinquent',
        'status',
        'closed',
        'charge_status',
        'paid_at',
        'document_number',
        'due_date',
        'url',
    ];

    private $PUBLIC_URI = 'https://api.pagar.me/core/v5';

    public function content(){
        return $this->belongsTo(\App\Models\Content::class);
    }

    public function auth(){
        $SECRET_KEY = env('PAGARME_API_TEST_KEY');

        return [
            "Authorization: Basic ". base64_encode("$SECRET_KEY:"),      
            "Content-Type: application/json"
        ];
    }

    public function getCurl(){
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $this->auth(),
            CURLOPT_USERPWD =>  env('PAGARME_API_TEST_KEY') . ":" .  env('PAGARME_API_TEST_PASSWRD'),
        ]);

        return $curl;
    }

    public function getOrder(){
        $curl = $this->getCurl();
        curl_setopt_array($curl,[
            CURLOPT_URL => "$this->PUBLIC_URI/charges/$this->charges_id",
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);

        return $response;
    }


    public function createOrder($customer, $payments){

        $curl = curl_init();

        $body = [
            "items" => [
                [
                    "code" => 'vaga',
                    "amount" => env('APP_VALUE_AMOUNT_CONTENT'),
                    "description" => "Anúncio de vaga Inklua",
                    "quantity" => 1
                ]
            ],
            "customer" => $customer,
            "payments" => $payments,
        ];

        $curl = $this->getCurl();
        curl_setopt_array($curl,[
            CURLOPT_URL => "$this->PUBLIC_URI/orders/",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 500,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_SSL_VERIFYHOST => 0
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);

        return $response;
    }

    public static function getAddress(){
        return [
            "line_1" => request()->input('customer_address_line_1','1400, Av. Gal. Carneiro'),
            "line_2" => request()->input('customer_address_line_2','18043003'),
            "zip_code" => request()->input('customer_address_zip_code','18043003'),
            "city" => request()->input('customer_address_city','São Paulo'),
            "state" => request()->input('customer_address_state','SP'),
            "country" => request()->input('customer_address_country','BR'),
        ];
    }

    public static function getCustomer($user){
        return [
            "name" => $user->name,
            "email" => $user->email,
            "document" => request()->input('customer_document'),
            "type" => "individual",
            "document_type" => request()->input('customer_document_type'),
            "phones" => [
                "home_phone" => [
                    "country_code" => request()->input('customer_phone_country_code','55'),
                    "area_code" => request()->input('customer_phone_area_code','55'),
                    "number" => request()->input('customer_phone_number','1155323232')
                ]
            ],
            "address" => Transaction::getAddress()
            
        ];
    }

    public static function getPayments() {
        if(request()->input('payment_method') == 'credit_card') {
            $payment_method = [
                "recurrence" => false,
                "installments" => 1,
                "statement_descriptor" => "Inklua",
                "card" => [
                    "number" => request()->input('card_number'),
                    "holder_name" => request()->input('card_holder_name'),
                    "exp_month" => request()->input('card_exp_month'),
                    "exp_year" => request()->input('card_exp_year'),
                    "cvv" => request()->input('card_cvv'),
                    "billing_address" =>  Transaction::getAddress()
                ]
            ];

        }else {
            $due_date = Carbon::now()->addDays(5)->toISOString();
            $payment_method = [ 
                "instructions" => "Pagar até o vencimento",
                "due_at" => $due_date,
                "document_number" => uniqid(),
                "type" => "DM"
            ];
        }

        return [
            [
                "payment_method" => request()->input('payment_method'),
                request()->input('payment_method') => $payment_method,
            ]
        ];
    }

    public function updateFromGateway($pagarme) {
        $data = [
            'order_id' => $pagarme['id'],
            'customer_id' => $pagarme['customer']['id'],
            'charges_id' => $pagarme['charges'][0]['id'],
            'code' => $pagarme['code'],
            'payment_method' => $pagarme['charges'][0]['payment_method'],
            'amount' => $pagarme['amount'],
            'customer_delinquent' => $pagarme['customer']['delinquent'],
            'status' => $pagarme['status'],
            'closed' => $pagarme['closed'],
            'charge_status' => $pagarme['charges'][0]['status'],
        ];

        if($data['payment_method'] == 'credit_card' && isset($pagarme['charges'][0]['paid_at'])) {
            $data['paid_at'] = $pagarme['charges'][0]['paid_at'];
        }
        
        if($data['payment_method'] == 'boleto') {
            $data['document_number'] = $pagarme['charges'][0]['last_transaction']['document_number'];
            $data['due_date'] = Carbon::parse($pagarme['charges'][0]['last_transaction']['due_at']);
            $data['url'] = $pagarme['charges'][0]['last_transaction']['url'];
        }

        $this->update($data);
    }
}
