<?php
App::uses('HttpSocket', 'Network/Http');
/**
 * DirectCall Component
 *
 * @author Rafael F Queiroz <rafaelfqf@gmail.com>
 */
class DirectCallComponent extends Component {

	/**
	 * URL
	 *
	 * @var string
	 */
	const URL = "https://www.directcallsoft.com/api/extrato/";

	/**
	 * Params default.
	 *
	 * @var array
	 */
	public $params = array(
		'formato' => 'json'
	);

	/**
	 * Method of get
	 *
	 * @param array $params
	 * @return mixed
	 */
	public function get($params) {
		$params  = array_merge($params, $this->params);
		if (!$this->_validateRequest($params))
			throw new CakeException('Campos obrigatorios ausentes');

		$request = $this->_makeRequest($params);
		if (!$request->isOk())
			throw new CakeException($request->body);

		if ($params['formato'] !== 'json')
			return $request->body;

		return (array) json_decode($request->body);
	}

	/**
	 * Make a request.
	 *
	 * @param array $params
	 * @return HttpSocket
	 */
	protected function _makeRequest($params) {
		$http = new HttpSocket();
		return $http->post(self::URL, http_build_query($params));
	}

	/**
	 * Validate a request.
	 *
	 * @param array $params
	 * @return bool
	 */
	protected function _validateRequest($params) {
		$keys = array('login', 'senha', 'pin', 'api', 'dataInicial', 'dataFinal', 'mostrarPlayer', 'formato');
		while ($keys) {
			if (empty ($params[array_shift($keys)]))
				return false;
		}

		return true;
	}

}
