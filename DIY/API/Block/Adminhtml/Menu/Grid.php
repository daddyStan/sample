<?php
namespace DIY\API\Block\Adminhtml\Menu;

use DIY\API\Model\apiMethods;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \DIY\API\Model\menuFactory
     */
    protected $_menuFactory;

    /**
     * @var \DIY\API\Model\Status
     */
    protected $_status;

    protected $_apiMethods;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \DIY\API\Model\menuFactory $menuFactory
     * @param \DIY\API\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \DIY\API\Model\apiMethods $apiMethods,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \DIY\API\Model\MenuFactory $MenuFactory,
        \DIY\API\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_apiMethods = $apiMethods;
        $this->_menuFactory = $MenuFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $item =  $this->getRequest()->getParam("clause");
        $collection = $this->_menuFactory->create()->getCollection();

        if($item != "") {
            $collection->getSelect()->where("`parent_id` = ?", $item);
        } else {
            $collection->getSelect()->where("`parent_id` = 0");
        }

        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $item =  $this->getRequest()->getParam("clause");

        if($item == "") {
            $this->addColumn(
                'name',
                [
                    'header' => __('Имя'),
                    'index' => 'name',
                    'header_css_class' => 'col-id',
                    'column_css_class' => 'col-id'
                ]
            );

            $this->addColumn(
                'menu_id',
                [
                    'header' => __('menu_id'),
                    'index' => 'menu_id',
                ]
            );
        } else {
            $this->addColumn(
                'name',
                [
                    'header' => __('Имя'),
                    'index' => 'name',
                    'header_css_class' => 'col-id',
                    'column_css_class' => 'col-id'
                ]
            );

            $this->addColumn(
                'position',
                [
                    'header' => __('position'),
                    'index' => 'position',
                ]
            );

            $this->addColumn(
                'url',
                [
                    'header' => __('url'),
                    'index' => 'url',
                ]
            );
        }

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('item_id');
        //$this->getMassactionBlock()->setTemplate('DIY_API::menu/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('menu');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('api/*/massDelete'),
                'confirm' => __('Вы уверены?')
            ]
        );

        return $this;
    }
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('api/*/index', ['_current' => true]);
    }

    /**
     * @param \DIY\API\Model\menu|\Magento\Framework\Object $row
     * @return string
     * Даём url строке
     */
    public function getRowUrl($row)
    {
        $clause =  $this->getRequest()->getParam("clause");

        if($clause == "") {
            return $this->getUrl(
                'api/*/index',
                ['clause' => $row->getId()]
            );
        } else {
            return $this->getUrl(
                'api/*/edit',
                [
                    'item_id' => $row->getId(),
                    'clause' => $clause,
                    'editeclause'  => true
                ]
            );
        }
    }
}