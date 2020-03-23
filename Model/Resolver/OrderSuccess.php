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

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\ThankYouPage\Helper\Data;
use Mageplaza\ThankYouPage\Model\ThankYouPageRepository;
use Magento\Framework\Authorization\PolicyInterface;

/**
 * Class OrderSuccess
 * @package Mageplaza\ThankYouPageGraphQl\Model\Resolver
 */
class OrderSuccess implements ResolverInterface
{
    const RESOURCE = 'Mageplaza_ThankYouPage::thankyoupage';

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var ThankYouPageRepository
     */
    protected $thankYouPageRepository;

    /**
     * @var PolicyInterface
     */
    private $aclPolicy;

    /**
     * OrderSuccess constructor.
     * @param Data $helperData
     * @param ThankYouPageRepository $thankYouPageRepository
     * @param PolicyInterface $aclPolicy
     */
    public function __construct(
        Data $helperData,
        ThankYouPageRepository $thankYouPageRepository,
        PolicyInterface $aclPolicy
    ) {
        $this->helperData             = $helperData;
        $this->thankYouPageRepository = $thankYouPageRepository;
        $this->aclPolicy              = $aclPolicy;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            throw new GraphQlInputException(__('The module is disabled'));
        }

        if (!$this->isAllowed($context)) {
            throw new GraphQlInputException(__("The consumer isn't authorized to access %1", self::RESOURCE));
        }

        if (!isset($args['orderId'])) {
            throw new GraphQlInputException(__('"orderId" should be specified'));
        }

        return $this->thankYouPageRepository->getOrderPage($args['orderId']);
    }

    /**
     * @param ContextInterface $context
     * @return mixed
     */
    public function isAllowed($context)
    {
        $type     = [UserContextInterface::USER_TYPE_INTEGRATION, UserContextInterface::USER_TYPE_ADMIN];
        $userType = $context->getUserType();

        return in_array($userType, $type, true) &&
               $this->aclPolicy->isAllowed($context->getUserId(), self::RESOURCE);
    }
}
