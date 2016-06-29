<?php

class MotoRequirements
{
	const CHECK_EMAIL_TIMEOUT = 86400;
	/**
     * Validation rules
     * @var array
     * @example
     * 	 array(
     * 		array(
     * 			'validator_name' => array(
     * 				'validator_param1' => '...',
     * 				...
     * 			)
     * 		),
     * 		...
     *   );
     */
    var $validators;

    /**
     * List of warning/error messages
     * @var array
     */
    var $messages;

    /**
     * Validation status
     * @var boolean
     */
    var $is_valid;



    function __construct() {
        $this->Moto_Requirements();
    }

    function Moto_Requirements()
    {
        $this->validators = array();
        $this->messages = array();
    }

    function check()
	{
	    $this->messages = array(); // reset messages array
	    $this->is_valid = true; // rest status

        foreach ($this->validators as $validator)
        {
            list($validator, $params) = $validator;
            $result = array();
            $validator = 'validate' . $this->_separatorToCamelCase($validator);

            if (!method_exists($this, $validator))
            {
                trigger_error('Call to undefined validator "'.$validator.'"', E_USER_ERROR);
                return false;
            }

            $result = call_user_func(array(&$this, $validator), $params);
            $this->is_valid = $this->is_valid && $result['is_valid'];


            $status = $result['is_valid'] ? 'success' : 'fail';

            if (isset($result[$status . '_message']))
            {
                preg_match('/(?:^([\w\-_]+){1}\s*:{1})?\s*(.+)?\s*(.+)?/i', $params[$status . '_message'], $message);
                $message[1] = isset($message[1]) ? $message[1] : 'error';
                $message[2] = isset($message[2]) ? $message[2] : "Validater {$validator} failed";
                $message[3] = isset($message[3]) ? $message[3] : '';

                // Hide some private parameters from output messages
                unset($result['is_valid']);
                unset($result['success_message']);
                unset($result['fail_message']);
                unset($result['default_placeholder']);

                if (count($result) > 0)
                {
                    // Replace message and details %placeholders% with values
                    foreach($result as &$str)
                        $str = str_replace ('|', ' or ', $str);
                    $vars = explode(',', '%' . implode("%,%", array_keys($result)) . '%');
                    $values = array_values($result);
                    $message[2] = str_replace($vars, $values, $message[2]);
                    $message[3] = str_replace($vars, $values, $message[3]);
                }

                // Fill not replaced %placeholders%
                $params['default_placeholder'] = isset($params['default_placeholder']) ? $params['default_placeholder'] : '\\0';
                $message[2] = preg_replace('/%([\w_]+)%/i', $params['default_placeholder'], $message[2]);
                $message[3] = preg_replace('/%([\w_]+)%/i', $params['default_placeholder'], $message[3]);

                $this->messages[] = array(strtolower(trim($message[1])), trim($message[2]), trim($message[3]));
            }
        }

        return $this->is_valid;
	}

	/**
	 * Add validation rule
	 * @param $validator array
	 * @return void
	 */
	function addValidator($name, $params)
	{
	    $this->validators[] = array($name, $params);
	    return $this;
	}

	/**
	 * Returns validation messages
	 * @return array
	 */
	function getMessages()
	{
	    return $this->messages;
	}

	/**
	 * Returns validation status
	 * @return boolean
	 */
	function isValid()
	{
	    return $this->is_valid;
	}

	/**
	 * Validate PHP min version
	 * @param $params array('min' => string, 'max' => string)
	 * @return array array(
	 * 		'is_valid' 				=> boolean
	 * 		'php_version'			=> string
	 * )
	 */
	function validatePhpVersion($params)
	{
	    $min = isset($params['min']) ? $params['min'] : 0;
	    $max = isset($params['max']) ? $params['max'] : 0;

	    $params['is_valid'] = true;
	    $params['php_version'] = PHP_VERSION;

	    if ($min > 0)
        {
            $params['is_valid'] = $params['is_valid'] && version_compare(PHP_VERSION, $min, '>=');
        }

        if ($max > 0)
        {
            $params['is_valid'] = $params['is_valid'] && version_compare(PHP_VERSION, $max, '<=');
        }

        return $params;
	}

	/**
	 * Validate if file is writable
	 * @param $params array('file' => string)
	 * @return array array(
	 * 		'is_valid'	=> boolean
	 * )
	 */
    function validateFileWritable($params)
	{
	    $params['is_valid'] = is_writable($params['file']);
	    return $params;
	}

	/**
	 * Validate if file is readable
	 * @param $params array('file' => string);
	 * @return array array(
	 * 		'is_valid'	=> boolean
	 * )
	 */
    function validateFileReadable($params)
	{
	    $params['is_valid'] = is_readable($params['file']);
	    return $params;
	}

	/**
	 * Validate if PHP extension is loaded
	 * @param $params array('extension' => string);
	 * @return array array(
	 * 		'is_valid'	=> boolean
	 * )
	 */
    function validatePhpExtensionLoaded($params)
	{
        $is_valid = false;
        if (strpos($params['extension'], '|') > 0)
        {
            $extensions = explode('|', $params['extension']);
            foreach($extensions as $extension )
            {
                $is_valid = $is_valid || extension_loaded($extension);
            }
        }
        else
        {
            $is_valid = extension_loaded($params['extension']);
        }
        $params['is_valid'] = $is_valid;
	    return $params;
	}

	/**
	 * Validte PHP upload file size
	 * @param $params array('min' => string, 'max' => string);
	 * @return array array(
	 * 		'is_valid'				=> boolean
	 * 		'post_max_size'			=> string
	 * 		'upload_max_filesize'	=> string
	 * 		'upload_max_size'		=> string min(post_max_size, upload_max_filesize)
	 * )
	 */
	function validatePhpUploadFileSize($params)
	{
	    $min = isset($params['min']) ? $this->_phpIniSizeToInt($params['min']) : 0;
	    $max = isset($params['max']) ? $this->_phpIniSizeToInt($params['max']) : 0;

        $uploadMaxSize = min(
            $this->_phpIniSizeToInt(ini_get('post_max_size')),
            $this->_phpIniSizeToInt(ini_get('upload_max_filesize'))
        );

        $params['is_valid'] = true;
        $params['post_max_size'] = ini_get('post_max_size');
        $params['upload_max_filesize'] = ini_get('upload_max_filesize');
        $params['upload_max_size'] = round($uploadMaxSize / (1024 * 1024), 2) . 'MB';

        if ($min > 0)
        {
            $params['is_valid'] = $params['is_valid'] && ($uploadMaxSize >= $min);
        }

        if ($max > 0)
        {
            $params['is_valid'] = $params['is_valid'] && ($uploadMaxSize <= $max);
        }

        return $params;
	}

	/**
	 * Validte PHP upload enabled
	 * @param $params array();
	 * @return array array('is_valid' => boolean)
	 */
	function validatePhpUploadEnabled($params)
	{
        $params['is_valid'] = false;

        if (ini_get('file_uploads'))
        {
            $params['is_valid'] = true;
        }

        return $params;
	}

	/**
	 * Validate mail function
	 * @param $params array();
	 * @return array array('is_valid' => boolean)
	 */
    function validateMailFunction($params)
	{
		if (isset($_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["lastcheck"])
			&& isset($_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["result"])
			&& $_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["result"]
			&& is_numeric($_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["lastcheck"])
			&& (time() - $_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["lastcheck"]) < self::CHECK_EMAIL_TIMEOUT
			)
		{
			$mail_result = $_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["result"];
		}
		else
		{
			$params['is_valid'] = false;

			$to      = 'noreply@cms-guide.com';
			$subject = 'mail test';
			$message = '';
			$headers =	'From: noreply@cms-guide.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();

			try
			{
        		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
				$mail_result = mail($to, $subject, $message, $headers);
				$_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["lastcheck"] = time();
				$_SESSION["MOTO_" . __CLASS__][__FUNCTION__]["result"] = $mail_result;
			}
			catch (Exception $e) {}
		    error_reporting(E_ALL);

		}
		if ($mail_result === true)
			$params['is_valid'] = true;
	    return $params;
	}

	/**
	 * Convert PHP ini size (e.g. "2M") to integer (e.g. 2*1024*1024)
	 * @access private
	 * @param $size string
	 * @return integer Size in bytes
	 */
	function _phpIniSizeToInt($size)
	{
	    preg_match('/^([0-9]+)\s*(P|T|G|M|K){0,1}/i', $size, $matches);
	    $value = isset($matches[1]) ? (int) $matches[1] : 0;
        $unit = isset($matches[2]) ? strtoupper($matches[2]) : '';

        switch($unit) {
            case 'P':
                $value *= 1024;
            case 'T':
                $value *= 1024;
            case 'G':
                $value *= 1024;
            case 'M':
                $value *= 1024;
            case 'K':
                $value *= 1024;
                break;
        }

        return $value;
	}

	/**
	 * Convert delimeter separated string to camelcase
	 * @access private
	 * @param $subject
	 * @param $separator
	 * @return string
	 */
	function _separatorToCamelCase($subject, $separator = '_')
	{
		$separator = preg_quote($separator, '\\');
		$parts = explode($separator, strtolower($subject));
		$parts = array_map('ucfirst', $parts);
		return implode($parts);
	}

	/**
	 * @return array
	 * 'is_valid' => boolean
	 */
	function validatePhpFunctionExists($params)
	{
		$params['is_valid'] = function_exists($params['function']);
		return $params;
	}

}