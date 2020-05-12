<?php
class HTTP {

public $ch;
public $url;
public $param;
public $option;
private $method;

public function __construct($url, $method = 'post', $redirect = true) {

$this->url = $url;

$this->ch = curl_init ( $this->url );

if ($method == 'post') {
curl_setopt ( $this->ch, CURLOPT_POST, true );
} else {
curl_setopt ( $this->ch, CURLOPT_HTTPGET, true );
}

$this->method = $method;

curl_setopt ( $this->ch, CURLOPT_RETURNTRANSFER, true );

if ($redirect) {
curl_setopt ( $this->ch, CURLOPT_FOLLOWLOCATION, true );
}
}

public function setOption($option) {

if (! empty ( $option )) {
$this->option = $option;
} else {
return false;
}

foreach ( $this->option as $k => $v ) {
curl_setopt ( $this->ch, $k, $v );
}

return true;
}

public function setParam($param) {
if (! empty ( $param )) {
$this->param = $param;
} else {
return false;
}

foreach ( $param as $k => $v ) {
$params [] = "$k=$v";
}

$this->param = implode ( '&', $params );

if ($this->method == 'post') {
curl_setopt ( $this->ch, CURLOPT_POSTFIELDS, $this->param );
} else {
curl_setopt ( $this->ch, CURLOPT_URL, $this->url . '?' . $this->param );
}

return true;
}

public function exec() {
return curl_exec ( $this->ch );
}

public function __destruct() {
curl_close ( $this->ch );
}
}

class DnspodApi {

private $httpHandler;
private $email;
private $pass;
private $format = 'json';

private $error = array();

public function __construct($email, $pass) {
if (! $email || ! $pass) {
exit ( 'no email or pass' );
}
$this->email = $email;
$this->pass = $pass;
$this->httpHandler = new HTTP ( 'https://dnsapi.cn/' );
}

public function createDomain($domain){
$this->setAction('Domain.Create');
$params['domain'] = $domain;
return $this->exec($params);
}

public function removeDomain($domainId){
$this->setAction('Domain.Remove');
$params['domain_id'] = $domainId;
return $this->exec($params);
}

public function setDomainStatus($params){
$this->setAction('Domain.Status');
return $this->exec($params);
}

public function getDomainList() {
$this->setAction('Domain.List');
return $this->exec();
}

public function getRecordList($domainId){
$this->setAction('Record.List');
$params['domain_id'] = $domainId;
return $this->exec($params);
}

public function createRecord($params){
$this->setAction('Record.Create');
return $this->exec($params);
}

public function modifyRecord($params){
$this->setAction('Record.Modify');
return $this->exec($params);
}

public function removeRecord($params){
$this->setAction('Record.Remove');
return $this->exec($params);
}

public function setRecordStatus($params){
$this->setAction('Record.Status');
return $this->exec($params);
}

private function exec($params = ''){
$params['login_email'] = $this->email;
$params['login_password'] = $this->pass;
$params['format'] = $this->format;

$this->httpHandler->setParam($params);

$result = $this->httpHandler->exec();

$result = json_decode ( $result ,true);

return $result;
}

private function setAction($action){
$this->httpHandler->setOption ( array (CURLOPT_URL => $this->httpHandler->url . $action ) );
}

private function error($result){
$this->error['code'] = $result->status->code;
$this->error['message'] = $result->status->message;
}

public function getError(){
return $this->error;
}
}
?>