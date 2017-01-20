<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\Model;

Interface ResourceValue
{
    /**
     * @param string $key
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $textValue
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setTextValue($textValue);

    /**
     * @return string
     */
    public function getTextValue();

    /**
     * @param mixed $dateValue
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setDateValue($dateValue);

    /**
     * @return mixed
     */
    public function getDateValue();

    /**
     * @param \DateTime $datetimeValue
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setDatetimeValue($datetimeValue);

    /**
     * @return \DateTime
     */
    public function getDatetimeValue();

    /**
     * @param mixed $timeValue
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setTimeValue($timeValue);

    /**
     * @return mixed
     */
    public function getTimeValue();

    /**
     * @param mixed $numberValue
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setNumberValue($numberValue);

    /**
     * @return mixed
     */
    public function getNumberValue();

    /**
     * @param int $integerValue
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setIntegerValue($integerValue);

    /**
     * @return int
     */
    public function getIntegerValue();

    /**
     * @param boolean $boolValue
     *
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\Resource
     */
    public function setBoolValue($boolValue);

    /**
     * @return boolean
     */
    public function getBoolValue();
}
