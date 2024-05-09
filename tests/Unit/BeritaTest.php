<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BeritaTest extends TestCase
{
    /** @test */
    public function getAllNews()
    {

        $curl = curl_init();        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://127.0.0.1:8000/api/news',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
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
    public function createNews(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1:8000/api/news',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        // CURLOPT_POSTFIELDS => array('title' => 'TES','image'=> new CURLFILE('postman-cloud:///1eef4e6d-d121-47f0-9697-ea58357d39f2'),'description' => 'lorem ipsum'),
        CURLOPT_POSTFIELDS => array('title' => 'TES','image'=> '','description' => 'lorem ipsum'),
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
    
    /** @test */
    public function getNewsById(){        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1:8000/api/news/4',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
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
    public function deleteNews(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1:8000/api/news/7',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'Cookie: XSRF-TOKEN=eyJpdiI6Imlabkc0aW5yTzNFS0lqaU8zR0VzTkE9PSIsInZhbHVlIjoiSm93aXJJRi8xMktQclY0d3pqa3hyMUdVY3hTcEI5RnVIM2l2d0dvU3BGTmNRbG41Z0ZiMUpkTE81Z1BhNUVuWjdiNTkwU2RvdTJseXI1b2l4T0pMbDN4cFN6YTJiUjNMVGlLMWQzVnBwL3QycnlXV0Y5ZjlKSEd4SGdoNktQMjMiLCJtYWMiOiI1YTUyYjZiYjRmNmM0NmJmOWJlZDViZDkwOGUxZTRmYzI0MzdhNzNlNTM2MDUzMzkyN2ZjNjY4MWIzMmViYzI4IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6InE3R0hwalN1MSs5ckFlTnVwdTFDeFE9PSIsInZhbHVlIjoiUXV6emxQNFlSL29IUmNZaWJ0U3kvck5wMzUvRm9ieWdMRHphZ2w5RHQrL3pLUXEzZWQ5SDNHSzF4UUFMQkhjcjFheng3WlFvZGMycmVPYlNjc2hFTFZGaTU1UU54bFNiU1BIcVNvVm5pVmJRWEdoNk15OGFiaW53MCtZWERPemsiLCJtYWMiOiJhYzU5MDJjMzRmOTY2YjllYTdiOWE3NzMyODg1NmQ2MGY2YmZlMDE4NmZlMTU4ZjViZGVjMGJhZGM1OWJiNjAwIiwidGFnIjoiIn0%3D'
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
}
