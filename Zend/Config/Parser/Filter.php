<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Config\Parser;

use Zend\Config\Config,
    Zend\Config\Parser,
    Zend\Config\Exception\InvalidArgumentException,
    Zend\Filter\Filter as ZendFilter,
    \Traversable,
    \ArrayObject;

/**
 * @category   Zend
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Filter implements Parser
{
    /**
     * @var \Zend\Filter\Filter
     */
    protected $filter;

    /**
     * Filter all config values using the supplied Zend\Filter
     *
     * @param \Zend\Filter\Filter $filter
     * @return \Zend\Config\Parser\Filter
     */
    public function __construct(ZendFilter $filter)
    {
        $this->setFilter($filter);
    }

    /**
     * @return \Zend\Filter\Filter
     */
    public function getFilter()
    {
        return $this->translator;
    }

    /**
     * @param \Zend\Filter\Filter $filter
     */
    public function setFilter(ZendFilter $filter)
    {
        $this->filter = $filter;
    }

    public function parse(Config $config)
    {
        if ($config->isReadOnly()) {
            throw new InvalidArgumentException('Cannot parse config because it is read-only');
        }

        /**
         * Walk through config and replace values
         */
        foreach ($config as $key => $val) {
            if ($val instanceof Config) {
                $this->parse($val);
            } else {
                $config->$key = $this->filter->filter($val);
            }
        }

        return $config;
    }

	/**
	 * Process a single value
	 *
	 * @param $value
	 * @return mixed
	 */
	public function parseValue($value)
	{
		return $this->filter->filter($value);
	}

}
