<?php

namespace Tests\Feature;

use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        //$response = $this->get('/');
        //$response->assertStatus(200);
        Users::where('phone','like','999%')->delete();

        for($i=1;$i<2;$i++) {
            $phone = '9990'.$i;
            $response = $this->json('POST', '/api/user/register', ['phone' => $phone, 'password' => '123456', 'password_comp' => '123456', 'inviter_id' => 99, 'code' => '123456']);
            $msg=json_decode($response->getContent());
            if($msg->code==200) {
                $response = $this->json('POST', '/api/user/login', ['phone' => $phone, 'password' => '123456']);
                $json = json_decode($response->getContent());
                dump($json->token);
                $token=$json->token;
                $authorization='Bearer '.$token;
                $response = $this->withHeaders(['Authorization'=>$authorization])->json('POST', '/api/address/add', ['area_info' => '11,1101,110101', 'address' => '123456','receive_tel'=>$phone,'receive_name'=>$phone,'is_default'=>1]);
                dump($response->getContent());
                $msg=json_decode( $response->getContent());

            }else{
                $response->assertStatus(500);
                break;
            }
        }
        $response->assertStatus(200);

    }
}
