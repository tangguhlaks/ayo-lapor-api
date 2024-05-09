<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\UserController; 
use Illuminate\Http\Request;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function getUserByUsernameSuccess(){

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1:8000/api/user-by-username',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'username=tangguhlaksana',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response, true);
        if ($data === null) {
            $status = false;
        } else {
            $status = $data['status'];
        }
        $this->assertTrue($status);
    }
    /** @test */
    public function getUserByUsernameFailed(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1:8000/api/user-by-username',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'username=wronggg',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response, true);

        if ($data === null) {
            $status = false;
        } else {
            $status = $data['status'];
        }
        $this->assertFalse($status);
    }

}
