<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
declare(strict_types=1);

namespace Mageplaza\ThankYouPageGraphQl\Model\Resolver\Template\FilterArgument;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;
use Mageplaza\ThankYouPage\Helper\Data;

/**
 * Class EntityAttributesForAst
 * @package Mageplaza\ThankYouPageGraphQl\Model\Resolver\Template\FilterArgument
 */
class EntityAttributesForAst implements FieldEntityAttributesInterface
{
    /**
     * @var array
     */
    protected $additionalAttributes = [
        'template_id',
        'name',
        'page_type',
        'status',
        'priority',
        'style',
        'created_at',
        'updated_at',
    ];
    /**
     * @var Data
     */
    protected $helperData;
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * EntityAttributesForAst constructor.
     *
     * @param Data $helperData
     * @param ConfigInterface $config
     */
    public function __construct(
        Data $helperData,
        ConfigInterface $config
    ) {
        $this->helperData = $helperData;
        $this->config     = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityAttributes(): array
    {
        $fields = [];
        /** @var Field $field */
        foreach ($this->config->getConfigElement('MageplazaThankYouPageTemplates')->getFields() as $field) {
            $fieldName          = $field->getName();
            $fields[$fieldName] = ['type' => 'String', 'fieldName' => $fieldName];
        }
        if ($this->helperData->versionCompare('2.3.4')) {
            return $fields;
        }

        return array_keys($fields);
    }
}
