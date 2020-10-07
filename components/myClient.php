<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

class myClient extends Component
{
	public $endpoint = "http://localhost/basic/web/api/product/";

	public $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjEifQ.eyJpc3MiOiJ3ZWJzdG9yZSIsImF1ZCI6InByb2R1Y3QiLCJqdGkiOiIxIiwiaWF0IjoxNTkyOTA0Mjc4LCJuYmYiOjE1OTI5MDQyNzgsImV4cCI6MTU5MjkxMDI3OH0.Adm9zMvP2Ro4V3KTmjcucVtS4k8ANV11uyrZ_0Ysifc";

	public function getRecordList()
	{
		$api = "list-record";
		$url = $this->endpoint.$api;

		$client = new Client();
		$request = $client->createRequest();

		$data = [ 'token' => $this->token ];
		$queryParam = $data;
		array_unshift($queryParam, $url);

		//throw new \Exception(var_export($queryParam, 1), 1);

		// $request->setHeaders([
		// 	'AES-ENCODE' => "test",
		// ]);
		$request->setUrl($queryParam);
		// $request->setUrl([$url, 'token' => $this->token]);
		$request->setMethod("get");
		$request->setFormat(Client::FORMAT_JSON);
		// $request->setData($data);

		$response = $request->send();
		return $response;
	}

	public function getWalletBalance()
	{
		$api = "wallet";
		$url = $this->endpoint.$api;

		$client = new Client();
		$request = $client->createRequest();

		$data = [
			'token' => $this->token,
		];
		$queryParam = $data;
		array_unshift($queryParam, $url);

		// $result = json_encode($data);
		// 		var_dump($result);

		//throw new \Exception(var_dump($queryParam), 1);

		// $request->setHeaders([
		// 	'AES-ENCODE' => "test",
		// ]);
		$request->setUrl($queryParam);
		// $request->setUrl([$url, 'token' => $this->token]);
		$request->setMethod("post");
		$request->setFormat(Client::FORMAT_JSON);
		// $request->setData($data);

		$response = $request->send();
		return $response;
	}
}
