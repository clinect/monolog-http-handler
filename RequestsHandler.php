<?php

/*
 * This file is part of the MonologRequestsHandler package.
 *
 * (c) Clinect Healthcare, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Clinect\MonologRequestsHandler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\JsonFormatter;

/**
 * Base Handler class providing the Handler structure
 *
 * @author Johnny Freeman <jfreeman@clinecthealthcare.com>
 */
class RequestsHandler extends AbstractProcessingHandler
{
    /**
     * @var url
     */
    protected $url;

    /**
     * @var method
     */
    protected $method = 'post';

    /**
     * @var headers
     */
    protected $headers = array();

    /**
     * @var options
     */
    protected $options = array();

    /**
     * Initialize Handler
     *
     * @param string $url
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($url, $level = Logger::WARNING, $bubble = true) {
        parent::__construct($level, $bubble);
        $this->url = $url;
    }

    /**
     * Set Headers
     *
     * @param array $headers
     * @return void
     */
    public function setHeaders($headers) {
        array_merge($this->headers, $headers);
    }

    /**
     * Set Options
     *
     * @param array $options
     * @return void
     */
    public function setOptions($options) {
        array_merge($this->options, $options);
    }

    /**
     * Set HTTP Method
     *
     * @param string $method
     * @return void
     */
    public function setMethod($method) {
        $this->method = $method;
    }

    /**
     * {@inheritDoc}
     */
    public function write(array $record)
    {
        call_user_func(array('Requests', $this->method), $this->url, $this->headers, $record['formatted'], $this->options);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter();
    }
}