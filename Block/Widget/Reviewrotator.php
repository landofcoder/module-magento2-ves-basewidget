<?php
/**
 * Venustheme
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Venustheme.com license that is
 * available through the world-wide-web at this URL:
 * http://www.venustheme.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Venustheme
 * @package    Ves_BaseWidget
 * @copyright  Copyright (c) 2014 Venustheme (http://www.venustheme.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
 */
namespace Ves\BaseWidget\Block\Widget;
use Ves\BaseWidget\Block\AbstractWidget;

class Reviewrotator extends AbstractWidget
{

	protected $_reviewsCollection;
	protected $_blockModel;
	protected $_dataFilterHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $reviewFactory;

    /**
     * @var \Magento\Review\Model\Rating\Option\VoteFactory
     */
    protected $voteFactory;

    /**
     * @var \Magento\Review\Model\RatingFactory
     */
    protected $ratingFactory;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Cms\Model\Block $blockModel,
		\Ves\BaseWidget\Helper\Data $dataHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\Rating\Option\VoteFactory $voteFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
		array $data = []
	) {
		parent::__construct($context, $blockModel, $dataHelper, $data);
        $this->reviewFactory = $reviewFactory;
        $this->productFactory = $productFactory;
		$this->_blockModel = $blockModel;
        $this->ratingFactory = $ratingFactory;
        $this->voteFactory = $voteFactory;
		$this->_dataFilterHelper = $dataHelper;
		$this->setTemplate("widget/reviewsrotator.phtml");
	}

    /**
     * @inheritdoc
     */
	public function getReviews($number,$type)
    {
		$store = $this->_storeManager->getStore();
		$reviews = $this->reviewFactory->create()->getResourceCollection();
		$reviews->addStoreFilter($store->getId());
		$reviews->setPageSize($number);
		if($type=='random'){
			$reviews->getSelect()->order(new \Zend_Db_Expr('RAND()'));
		}else{
			$reviews->setOrder('review_id');
		}
		$reviewCollection = array();
		foreach ($reviews as $review) {
			$reviewId = $review->getId();
			$productId = $review->getEntityPkValue();
			$obj = $this->productFactory->create();
			$_product = $obj->load($productId);
			$reviewCollection[$reviewId]['review'] = array(
				'title' => $review->getTitle(),
				'description' => $review->getDetail(),
				'url' => $this->getUrl('review/product/view',array('id'=>$reviewId)),
				'created_at' => $review->getCreatedAt(),
				);
			$reviewCollection[$reviewId]['product'] = array(
				'name' => $_product->getName(),
				'url' => $_product->getProductUrl(),
				);
			$reviewCollection[$reviewId]['author'] = array(
				'name' => $review->getNickname(),
				);
			$votesCollection = $this->voteFactory->create()
						->getResourceCollection()
						->setReviewFilter($reviewId)
						->setStoreFilter($store->getId())
						->load();
			foreach ($votesCollection as $vote) {
				$id = $vote->getRatingId();
				$ratings = $this->ratingFactory->create()
                                ->getCollection()
                                ->addFilter('rating_id',$id);

				foreach ($ratings as $rating) {
					$reviewCollection[$reviewId]['rating'][] = array(
						'name' => $rating->getRatingCode(),
						'percent' => $vote->getPercent(),
						'value' => $vote->getValue(),
						);
				}
			}
		}
		return $reviewCollection;
	}

    /**
     * @inheritdoc
     */
	protected function _toHtml()
    {
		if(!$this->getDataFilterHelper()->getConfig('general/show')) return;

		$number = $this->getConfig('show_number');
		$type = $this->getConfig('show_type');
		$this->assign('reviews',$this->getReviews($number,$type));
		$this->assign('title',$this->getConfig('title'));
		$this->assign('author',$this->getConfig('enable_author'));
		$this->assign('description',$this->getConfig('enable_description'));
		$this->assign('reviewTitle',$this->getConfig('enable_title'));
		return parent::_toHtml();
	}
}
