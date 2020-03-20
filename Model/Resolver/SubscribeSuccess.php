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
 * @package     Mageplaza_ThankYouPageGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
declare(strict_types=1);

namespace Mageplaza\ThankYouPageGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\ThankYouPage\Helper\Data;
use Mageplaza\ThankYouPage\Model\ThankYouPageRepository;

/**
 * Class SubscribeSuccess
 * @package Mageplaza\ThankYouPageGraphQl\Model\Resolver
 */
class SubscribeSuccess implements ResolverInterface
{

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var ThankYouPageRepository
     */
    protected $thankYouPageRepository;

    /**
     * SubscribeSuccess constructor.
     *
     * @param Data $helperData
     * @param ThankYouPageRepository $thankYouPageRepository
     */
    public function __construct(
        Data $helperData,
        ThankYouPageRepository $thankYouPageRepository
    ) {
        $this->helperData = $helperData;
        $this->thankYouPageRepository = $thankYouPageRepository;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            throw new GraphQlInputException(__('The module is disabled'));
        }
        if (!isset($args['email'])) {
            throw new GraphQlInputException(__('"email" should be specified'));
        }
        if (!isset($args['storeId'])) {
            $args['storeId'] = null;
        }
        if (!isset($args['customerGroup'])) {
            $args['customerGroup'] = null;
        }

        $template = $this->thankYouPageRepository->getSubsPage(
            $args['email'],
            $args['storeId'],
            $args['customerGroup']
        );

        return $template;
    }
}
