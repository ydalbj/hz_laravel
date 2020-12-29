<?php

namespace Tests\Feature;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $data = [
            'results' => [
                22 => ['72', '73'],
                32 => ['130'],
            ],
            'basic' => [
                'wechat_name' => 'aa',
                'telephone' => '',
                'who_take_care' => '妈妈',
                'region' => ['山东', '济南'],
                'birthday' => '2018-06-12',
                'birth_situations' => [
                    'aaa', 'bbb'
                ],
                'birth_info' => 'sc',
                'birth_order' => 'bb',
                'occupation' => '',
                'sex' => '男孩',
            ]
        ];
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/api/subject/1/answer', $data);

        dump($response->content());
    }
}
