<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\Model;

class Resource implements ResourceInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $textValue;

    /**
     * @var \DateTime
     */
    protected $datetimeValue;

    /**
     * @var
     */
    protected $dateValue;

    /**
     * @var
     */
    protected $timeValue;

    /**
     * @var
     */
    protected $numberValue;

    /**
     * @var int
     */
    protected $integerValue;

    /**
     * @var bool
     */
    protected $boolValue;

    public function __construct()
    {
        $this->boolValue = false;
    }

    /**
     * {@inheritdoc}
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function setTextValue($textValue)
    {
        $this->textValue = $textValue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTextValue()
    {
        return $this->textValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateValue($dateValue)
    {
        $this->dateValue = $dateValue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateValue()
    {
        return $this->dateValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setDatetimeValue($datetimeValue)
    {
        $this->datetimeValue = $datetimeValue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatetimeValue()
    {
        return $this->datetimeValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeValue($timeValue)
    {
        $this->timeValue = $timeValue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeValue()
    {
        return $this->timeValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumberValue($numberValue)
    {
        $this->numberValue = $numberValue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumberValue()
    {
        return $this->numberValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setIntegerValue($integerValue)
    {
        $this->integerValue = $integerValue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegerValue()
    {
        return $this->integerValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setBoolValue($boolValue)
    {
        $this->boolValue = $boolValue;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBoolValue()
    {
        return $this->boolValue;
    }
}
