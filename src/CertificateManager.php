<?php

/**
* @author Constan van Suchtelen van de Haere <constan.vansuchtelenvandehaere@hostingbe.com>
* @copyright 2024 HostingBE
*
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
* files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy,
* modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
* is furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
* BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
* OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*
*/

namespace HostingBE\APIClientCertificateManager;

use Psr\Log\LoggerInterface;

use HostingBE\APIClientCertificateManager\coreAPI;

class CertificateManager extends coreAPI {

protected $codes = array('404','500','501','502','503');

public function __construct(LoggerInterface $logger) {
    parent::__construct($logger);
}

/**
 *  Execute the login command with username and password
 */
public function login($username, $password) {
    $form = array('form_params' => array('username' => $username, 'password' => $password));
    $response = $this->getUri('POST','/login',$form);
    /** 
    * Login succesfull save the token
    */
    if ($response->data->code == "200") {
    $this->settoken($response->data->data->token);
    }
    return $response;
}

/**
*  Common API interface handling the get and post requests
*/
public function common(string $method, string $command, array $params = []) :object {
        
        $method = strtolower($method);

        $this->checkmethod($method);

        if ($method == 'get') {
        $response = $this->getUri($method, $command.$this->paramstostring($params),['headers' => ['Authorization' => 'Bearer ' . $this->gettoken()]]);     
        }
        if ($method == "post") {
        $response = $this->getUri($method, $command, ['headers' => ['Authorization' => 'Bearer ' . $this->gettoken()], 'form_params' => $params]);   
        }
        return $response;
}
/**
 *  check the method
 */
protected function checkmethod($method) {
    
    if (!in_array($method, array('get','post'))) {
        throw new \Exception("invalid method received!");
    }
}
/**
 *  create the get extra parameters string
 */
protected function paramstostring($params) {
$paramsstr = '';

if (count($params)!= 0) {
$paramsstr = "/".implode("/",$params);
}
return $paramsstr;
}

/**
 * Get the current active token
 */
protected function gettoken() :string {
    return $this->token;
}
/**
* Set the current active token
*/
protected function settoken($token) {
    $this->token = $token;
}

/**
* Decode output JSON to an object
*/
protected function output($output) {
        return json_decode($output);
}

/**
* Get the requested URI and return the response
*/
protected function getUri(string $method, string $uri,array $form = array()) :object {
       $response = $this->client->request($method, $this->baseUri . $uri, $form);

       $response->getBody()->rewind();

       if (in_array($response->getStatusCode(),$this->codes)) {
       return (object) array('code' => $response->getStatusCode(), 'message' => $response->getReasonPhrase()); 
       } else {
       return (object) array('code' => $response->getStatusCode(), 'message' => $response->getReasonPhrase(),'data' => $this->output($response->getBody()->getContents()));
       }
    }
}
?>