<?php
require_once 'Config.php';

class Zoho_CloudDb_Query
{
    /**
     * @var string $_auth
     */
    private $_auth;
    /**
     * @var string $_endpoint
     */
    private $_endpoint;
    /**
     * @var string $_username
     */
    private $_username;
    /**
     * @var array $_param
     */
    private $_param = array(
        'ZOHO_ACTION'        => 'EXPORT',
        'ZOHO_API_VERSION'   => '1.0',
        'ZOHO_ERROR_FORMAT'  => 'JSON',
        'ZOHO_OUTPUT_FORMAT' => 'JSON'
    );
    
    /**
     *
     * Allow user to inject their own config
     *
     * @param array $options
     *
     * @return void
     */
    public function __construct($options=null)
    {   
        $conf = config();
        if ($options) {
            $conf = array_merge($options, $default);
        }
        $this->_setConfig($conf);
    }
    
    /**
     *
     * Set Config options at instantiation
     *
     * @param array $config
     *
     * @return void
     */
    private function _setConfig(array $config)
    {
        $this->_endpoint = $config['end_point'];
        $this->_username = $config['user_name'];
        $this->_param['authtoken'] = $config['report_auth'];
    }
    
    /**
     *
     * Set Database to query
     *
     * @param string $db
     *
     * @return object Query
     */
    public function setDb($db)
    {
        $this->_db = $db;
        return $this;
    }
    
    /**
     *
     * Set Query
     *
     * @param string $query
     *
     * @return object Query
     */
    public function setQuery($query)
    {
        $this->_query = 'ZOHO_SQLQUERY=' . rawurlencode($query);
        return $this;
    }
    
    /**
     *
     * Set format for output
     *
     * @param string $format
     * - CSV
     * - XML
     * - JSON
     * - HTML
     * - PDF
     */
    public function setOutputFormat($format)
    {
        $this->_param['ZOHO_OUTPUT_FORMAT'] = $format;
        return $this;
    }
    
    /**
     *
     * Set format for error output
     *
     * @param string $format
     * - CSV
     * - XML
     * - JSON
     * - HTML
     * - PDF
     */
    public function setErrorFormat($format)
    {
        $this->_param['ZOHO_ERROR_FORMAT'] = $format;
        return $this;
    }
    
    /**
     *
     * Made for forward compatability
     *
     * @param string $version
     */
    public function setVersion($version=null)
    {   
        if ($version) {
            $this->_param['ZOHO_API_VERSION'] = $version;
        }
        return $this;   
    }
    
    /**
     *
     * Set report auth key
     *
     * @param string $auth
     */
    public function setAuth($auth=null)
    {
        if ($auth) {
            $this->_param['authtoken'] = $auth;
        }
        return $this;
    }
    
    /**
     *
     * Assemple URL for request
     *
     * @return string
     */
    private function _getUrl()
    {
        $url  = $this->_endpoint . '/';
        $url .= $this->_username . '/';
        $url .= $this->_db . '?';
        
        $url .= http_build_query($this->_param);
        
        return $url;
    }
    
    /**
     *
     * Return query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->_query;
    }
    
    /**
     *
     * Run query
     *
     * @return mixed
     *
     * @throws RunTimeExcpetion
     */
    public function run()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_getUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getQuery());
        
        $result = curl_exec($ch);
    
        if ($result === false) {
            throw new RunTimeExcpetion(sprintf(
                "Request failed: %s", curl_error($ch)
            ));
        }            
        curl_close($ch);
                
        return $result;
    }
}