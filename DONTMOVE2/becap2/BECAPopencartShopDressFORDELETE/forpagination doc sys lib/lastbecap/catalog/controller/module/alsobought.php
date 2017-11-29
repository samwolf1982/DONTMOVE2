<?php  

class ControllerModuleAlsobought extends Controller {

	public function index() {
        $this->load->model('module/alsobought');
		$this->language->load('module/alsobought');
      	$data['heading_title'] = $this->language->get('heading_title');
		$data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['text_tax'] = $this->language->get('text_tax');
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['data']['alsobought'] = str_replace('http', 'https', $this->config->get('alsobought'));
		} else {
			$data['data']['alsobought'] = $this->config->get('alsobought'); 
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsobought.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/alsobought.tpl';
		} else {
			$this->template = 'default/template/module/alsobought.tpl';
		}

		$moduleSetting = $this->model_module_alsobought->getSetting('alsobought', $this->config->get('config_store_id'));
        $data['moduleData'] = isset($moduleSetting['alsobought']) ? $moduleSetting['alsobought'] : array ();
		if(!isset($data['moduleData']['PanelName'][$this->config->get('config_language')])){
			$data['PanelName'] = $data['heading_title'];
		} else {
			$data['PanelName'] = $data['moduleData']['PanelName'][$this->config->get('config_language')];
		}

		if (isset($this->request->get['product_id'])) { 
			$alsoBoughtProducts = $this->listAlsoBoughtById((int)$this->request->get['product_id'],(int)$data['moduleData']['NumberOfProducts']);
		} else { 
			$alsoBoughtProducts = array();  
		}
		
		$this->load->model('tool/image');

		$data['products'] = array();

		foreach ($alsoBoughtProducts as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $data['moduleData']['PictureWidth'], $data['moduleData']['PictureHeight']);
			} else {
				$image = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
					

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}	

			if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
            } else {
                $tax = false;
            }   

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}			

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
                'tax'         => $tax,
				'rating'     => $rating,
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
			); 
		}
		
		$ajaxrequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		
		if ($ajaxrequest == false) {
			$data['product_id'] = $this->request->get['product_id'];
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsobought.tpl')) {
				return $this->load->view($this->config->get('config_template').'/template/module/alsobought.tpl', $data);
			} else {
				return $this->load->view($this->config->get('config_template').'/template/module/alsobought.tpl', $data);
			}  
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsobought/alsobought.tpl')) {
				return $this->load->view($this->config->get('config_template').'/template/module/alsobought/alsobought.tpl', $data);
			} else {
				return $this->load->view($this->config->get('config_template').'/template/module/alsobought/alsobought.tpl', $data);
			}  
		}

    }
	
	public function getindex() {
		$this->response->setOutput($this->index());
	}

    

      public function getCatalogURL($store_id){

        if(isset($store_id) && $store_id){

            $storeURL = $this->db->query('SELECT url FROM `'.DB_PREFIX.'store` WHERE store_id=' . $store_id)->row['url'];

        }elseif (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {

            $storeURL = HTTPS_SERVER;

        } else {

            $storeURL = HTTP_SERVER;

        } 

        return $storeURL;

    }

	

	private function listAlsoBoughtById($product_id,$limit=5) {
		$this->load->model('catalog/product');
		$data = $this->db->query("SELECT * FROM `" . DB_PREFIX . "alsobought` WHERE `low` = $product_id OR `high` = $product_id ORDER BY `number` DESC LIMIT $limit");
		$rows = $data->rows;
		$products = array();
		foreach ($rows as $row) {
			if ($row['low'] == $product_id) {
				$pid = $row['high'];
			} else {
				$pid = $row['low'];
			}

			$products[$pid] = $this->model_catalog_product->getProduct($pid);
		}
		return $products;
	}

}

?>