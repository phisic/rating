<?php

class BBOpen extends CApplicationComponent {

    public $apiKey;
    public $pageSize = 50;
    protected $_url = 'http://api.remix.bestbuy.com/v1/products';
    protected $_page = 1;
    protected $_show = 'sku,name,categoryPath,modelNumber,manufacturer,longDescription,shortDescription,largeFrontImage,alternateViewsImage,angleImage';
    protected $_category = 'abcat0100000';

    public function category($category) {
        $this->_category = $category;
        return $this;
    }

    public function page($page) {
        $this->_page = $page;
        return $this;
    }

    public function show($show) {
        $this->_show = $show;
        return $this;
    }

    public function pageSize($size) {
        $this->pageSize = $size;
        return $this;
    }

    public function query() {
        $url = $this->_url . '(categoryPath.id=' . $this->_category . ')?show=' . $this->_show . '&pageSize=' . $this->pageSize . '&page=' . $this->_page . '&apiKey=' . $this->apiKey;
        $xml = simplexml_load_file($url);
        return json_decode(json_encode((array) $xml), TRUE);
    }

}