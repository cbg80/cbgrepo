<?php
class RestServer
{
	private $_allow;
	private $_contentType;
// 	private $_request = array();
	private $_method;
	private $_code;
	function __construct(array $allow, $contentType, /*$request,*/ $method, $code)
	{
		//$this->inputs();
		$this->_allow = $allow;
		$this->_contentType = $contentType;
// 		$this->_request = $request;
		$this->_method = $method;
		$this->_code = $code;
	}
	static function getReferer()
	{
		return $_SERVER['HTTP_REFERER'];
	}
	static function getRequestMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	static function getRequestContentType()
	{
		if (isset($_SERVER['CONTENT_TYPE'])) {
			$contentType = $_SERVER['CONTENT_TYPE'];
		} else {
			$contentType = FALSE;
		}
		return $contentType;
	}
	//TODO Añadir el internet media type a la respuesta json
	function response($data, $status = 200)
	{
		$this->_code = $status;
		print $data;
		$this->setHeaders(strlen($data));
		exit;
	}
	private function getStatusMessage()
	{
		$status = [
					100 => 'Continue'
					,101 => 'Switching Protocols'
					,200 => 'OK'
					,201 => 'Created'
					,202 => 'Accepted'
					,203 => 'Non-Authoritative Information'
					,204 => 'No Content'
					,205 => 'Reset Content'
					,206 => 'Partial Content'
					,300 => 'Multiple Choices'
					,301 => 'Moved Permanently'
					,302 => 'Found'
					,303 => 'See Other'
					,304 => 'Not Modified'
					,305 => 'Use Proxy'
					,306 => '(Unused)'
					,307 => 'Temporary Redirect'
					,400 => 'Bad Request'
					,401 => 'Unauthorized'
					,402 => 'Payment Required'
					,403 => 'Forbidden'
					,404 => 'Not Found'
					,405 => 'Method Not Allowed'
					,406 => 'Not Acceptable'
					,407 => 'Proxy Authentication Required'
					,408 => 'Request Timeout'
					,409 => 'Conflict'
					,410 => 'Gone'
					,411 => 'Length Required'
					,412 => 'Precondition Failed'
					,413 => 'Request Entity Too Large'
					,414 => 'Request-URI Too Long'
					,415 => 'Unsupported Media Type'
					,416 => 'Requested Range Not Satisfiable'
					,417 => 'Expectation Failed'
					,500 => 'Internal Server Error'
					,501 => 'Not Implemented'
					,502 => 'Bad Gateway'
					,503 => 'Service Unavailable'
					,504 => 'Gateway Timeout'
					,505 => 'HTTP Version Not Supported'
		];
		if (isset($status[$this->_code])) {
			$ret = $status[$this->_code];
		} else {
			$ret = $status[500];
		}
		return $ret;
	}
// 	private function inputs()
// 	{
// 		switch ($this->getRequestMethod()) {
//  			case 'POST':
//  				$this->_request = $this->cleanInputs($_POST);
//  				break;
// 			case 'GET':
// 			case 'DELETE':
// 				$this->_request = $this->cleanInputs($_GET);
// 				break;
// 			case 'PUT':
// 				parse_str(file_get_contents('php://input'), $this->_request);
// 				$this->_request = $this->cleanInputs($this->_request);
// 				break;
// 			default:
// 				$this->response('', 406);
// 				break;
// 		}
// 	}
	static function cleanInputs($data)
	{
		$clean_input = array();
		if (is_array($data) and count($data) > 0) {
			foreach ($data as $key => $value) {
				$clean_input[$key] = self::cleanInputs($value);
			}
		} else {
			$data = trim(stripslashes($data));
			$data = strip_tags($data);
			$clean_input = trim($data);
		}
		return $clean_input;
	}
	private function setHeaders($contentLength)
	{
		header('HTTP/1.1 ' . $this->_code . ' '. $this->getStatusMessage());
		header('Content-Type:' . $this->_contentType);
		header('Content-Length: '. $contentLength);
	}
// 	function processApi($controllerObj)
// 	{
// 		$func = strtolower(trim(str_replace('/', '', $_REQUEST['function'])));
// 		if ((int)method_exists($controllerObj, $func) > 0) {
// 			$controllerObj->$func();
// 		} else {
// 			//If the method is not declared within $controllerObj, response would be "Page not found"
// 			$this->response('', 404);
// 		}
// 	}
	static function json(array $data)
	{
		if (is_array($data)) {
			return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
		}
	}
}
?>