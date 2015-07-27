<?php

/*
 * This file is part of the MonologRequestsHandler package.
 *
 * (c) Clinect Healthcare, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Clinect\MonologHttpHandler;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\JsonFormatter;

/**
 * Base Handler class providing the Handler structure
 *
 * @author Johnny Freeman <jfreeman@clinecthealthcare.com>
 */
class HttpHandler extends AbstractProcessingHandler
{
    /**
     * @var url
     */
    protected $url;

    /**
     * @var Http Client
     */
    protected $client;

    /**
     * @var method
     */
    protected $method = 'post';

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
    public function __construct($options, $level = Logger::WARNING, $bubble = true) {
        parent::__construct($level, $bubble);
        $this->client = new GuzzleHttp\Client();

        if (isset($options['url'])) {
            $this->url = $options['url'];
            unset($options['url']);
        }

        if (isset($options['method'])) {
            $this->method = $options['method'];
            unset($options['method']);
        }
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
        call_user_func(
            array($this->client, $this->method), 
            $this->url, 
            array_merge($this->options, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $record['formatted']
            ])
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter();
    }
}