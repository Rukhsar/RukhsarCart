<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/** 
 * Cart class
 */ 
class Cart {

	/**
	 * Config
	 *
	 * @access private 
	 */	
	private static $config;

	/**
	 * Database
	 *
	 * @access private 
	 */		
	private $db;

	/**
	 * Session
	 *
	 * @access private 
	 */		
	private $session;

	/**
	 * Error
	 *
	 * @access private 
	 */		
	private $error;
		
	/**
	 * Constructor
	 * 
	 * @access public 
	 */	  
	public function __construct() {
		
		self::$config = config_load('cart');
		
		$this->db = new Database();
		$this->session = new Session();
		$this->error = new Error();
		
		$this->delete_carts();
		
		if (!$this->session->get('cart_session'))
			$this->cart_session();
		
	}

	/**
	 * Generates the session of the cart
	 * 
	 * @access private 
	 */	
	private function cart_session() {
		
		if (!$this->session->get('cart_session'))	
			$this->session->set('cart_session', md5(uniqid(mt_rand())));
		
	}
		
	/**
	 * Add product
	 * 
	 * @access public 
	 */	
	public function add_product($product_id, $options = array()) {
		
		$product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
		
		$sql = "SELECT product_id, product_name, product_quantity FROM " . self::$config['table_products'] . " WHERE product_id = '" . $product_id . "'";
		
		if ($this->db->row_count($sql)) {
		
			$result = $this->db->fetch_row_assoc($sql);
										
			if ($result['product_quantity'] == 0) {
									
				$this->error->set_error('The ' . $result['product_name'] . ' product is no in stock.');
				
			} else {
					
				if (empty($options[0]))
					$sql = "SELECT * FROM " . self::$config['table_carts'] . " WHERE product_id = '" . $product_id . "' AND cart_session = '" . $this->session->get('cart_session') . "'";
				else
					$sql = "SELECT * FROM " . self::$config['table_carts'] . " WHERE product_id = '" . $product_id . "' AND cart_session = '" . $this->session->get('cart_session') . "' AND options = '" . implode(':', $options) . "'";

				if ($this->db->row_count($sql) == 0) {
					
					if (!empty($options[0])) {
					
						foreach ($options as $value) {
							
							$product_options = $this->db->fetch_row_assoc("SELECT p.product_name, pov.option_value, pov.option_quantity FROM " . self::$config['table_products'] . " p, " . self::$config['table_product_option_values'] . " pov WHERE pov.option_value_id = '" . $value . "' AND pov.product_id = p.product_id");
							
							if ($product_options['option_quantity'] == 0) {
								
								$this->error->set_error('The quantity you have requested for ' . $product_options['product_name'] . ' ' . $product_options['option_value'] . ' is more than that into stock.');

								return;
								
							}
						}
						
					}
					
					$values = array(
						'cart_session' 		=> $this->session->get('cart_session'),
						'product_id' 		=> $product_id,
						'product_quantity' 	=> 1,
						'cart_created' 		=> time()
					); 			
					
					if ($options) $values['options'] = implode(':', $options);
					
					$this->db->insert(self::$config['table_carts'], $values);
														
				} else {

					$result = $this->db->fetch_row_assoc($sql);
					
					$product = $this->db->fetch_row_assoc("SELECT product_id, product_name, product_quantity FROM " . self::$config['table_products'] . " WHERE product_id = '" . $product_id . "'");
					
					$new_quantity = $result['product_quantity'] + 1;
					
					if (empty($options[0])) {
						
						if ($new_quantity > $product['product_quantity']) {

							$new_quantity = $result['product_quantity'];
							$this->error->set_error('The quantity you have requested for ' . $product['product_name'] . ' is more than that into stock.');
						
						}
					
					} else {
						
						$data = explode(':', $result['options']);
						
						foreach ($data as $value) {

							$product_options = $this->db->fetch_row_assoc("SELECT option_value, option_quantity FROM " . self::$config['table_product_option_values'] . " WHERE option_value_id = '" . $value . "'");

							if ($new_quantity > $product_options['option_quantity']) {

								$new_quantity = $result['product_quantity'];
								$this->error->set_error('The quantity you have requested for ' . $product['product_name'] . ' ' . $product_options['option_value'] . ' is more than that into stock.');

							}
														
						}
					
					}
					
					$values = array(
						'product_quantity' => $new_quantity
					);
					
					$where = array(
						'cart_session' 	=> $this->session->get('cart_session'),
						'product_id' 	=> $product_id
					);
					
					if ($options) $where['options'] = implode(':', $options);
					
					$this->db->where($where);
					$this->db->update(self::$config['table_carts'], $values);
					
				}

			}
			
		} else {
			
			$this->error->set_error('Sorry but the product is no longer in stock.');
			
		}			
	}

	/**
	 * Get cart
	 * 
	 * @access public 
	 */	
	public function get_cart() {
		
		$cart = array();
		
		foreach ($this->db->query("SELECT p.product_id, p.product_name, p.product_price, p.shipping, c.cart_id, c.product_quantity FROM " . self::$config['table_products'] . " p, " . self::$config['table_carts'] . " c WHERE p.product_id = c.product_id AND c.cart_session = '" . $this->session->get('cart_session') . "'") as $row) {
			
			$option_price = 0;						
			$options = array();

			foreach ($this->db->query("SELECT * FROM " . self::$config['table_carts'] . " WHERE cart_id = '" . $row['cart_id'] . "'") as $product_options) {

				$data = explode(':', $product_options['options']);
				
				foreach ($data as $value) {
					
					$option_values = $this->db->fetch_row_assoc("SELECT *, po.option_name, pov.option_value FROM " . self::$config['table_product_options'] . " po, " . self::$config['table_product_option_values'] . " pov WHERE po.option_id = pov.option_id AND pov.option_value_id = '" . $value . "'");
					
					if ($option_values['option_type'] == '+')
						$option_price = $option_price + $option_values['option_price'];
					elseif ($option_values['option_type'] == '-')
						$option_price = $option_price - $option_values['option_price'];
						
					$options[] = array(
						'option_value_id' => $option_values['option_value_id'],
						'option_name' => $option_values['option_name'],
						'option_value' => $option_values['option_value'],
						'option_quantity' => $option_values['option_quantity']
					);
								
				}								
			}

			$cart[] = array(
				'cart_id'			=> $row['cart_id'],
				'product_id'		=> $row['product_id'],
				'product_name'		=> $row['product_name'],
				'product_price'		=> $row['product_price'] + $option_price,
				'product_quantity'	=> $row['product_quantity'],
				'shipping'			=> $row['shipping'],
				'total_price'		=> ($row['product_price'] + $option_price) * $row['product_quantity'],
				'options'			=> $options
			);
				
        }
		
		if (isset($cart))
			return $cart;	
		
	}

	/**
	 * Empty cart
	 * 
	 * @access public 
	 */	
	public function empty_cart() {

		$where = array(
			'cart_session' => $this->session->get('cart_session')
		);
		
		$this->db->where($where);
		$this->db->delete(self::$config['table_carts']);
		
		$this->session->delete('cart_session');
		$this->session->delete('coupon');
		
	}

	/**
	 * Remove item
	 * 
	 * @access public 
	 */	
	public function remove_item($cart_id, $product_id) {
		
		$cart_id = filter_var($cart_id, FILTER_SANITIZE_NUMBER_INT);
		$product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);

		$where = array(
			'cart_id'	 => $cart_id,
			'product_id' => $product_id
		);
		
		$this->db->where($where);	
		$this->db->delete(self::$config['table_carts']);
		
	}

	/**
	 * Subtotal
	 * 
	 * @access public 
	 */
	public function subtotal() {
		
		$subtotal = 0;
		$discount = 0;
		
		foreach ($this->db->query("SELECT p.product_price, c.product_quantity, c.cart_id FROM " . self::$config['table_products'] . " p, " . self::$config['table_carts'] . " c WHERE p.product_id = c.product_id AND c.cart_session = '" . $this->session->get('cart_session') . "'") as $row) {
			
			$option_price = 0;
			
			foreach ($this->db->query("SELECT * FROM " . self::$config['table_carts'] . " WHERE cart_id = '" . $row['cart_id'] . "'") as $product_options) {
			
				$data = explode(':', $product_options['options']);
				
				foreach ($data as $value) {
					
					$option_values = $this->db->fetch_row_assoc("SELECT option_price, option_type FROM " . self::$config['table_product_option_values'] . " WHERE option_value_id = '" . $value . "'");
					
					if ($option_values['option_type'] == '+')
						$option_price = $option_price + $option_values['option_price'];
					elseif ($option_values['option_type'] == '-')
						$option_price = $option_price - $option_values['option_price'];
								
				}
			}
			
			$subtotal += ($row['product_price'] + $option_price) * $row['product_quantity'];
							
        }
		
		return $subtotal;
		
	}

	/**
	 * Shipping cost
	 * 
	 * @access public 
	 */
	public function shipping_cost() {
        
        if ($this->has_shipping())
			return self::$config['shipping_cost'];
		else
			return 0;
	
	}

	/**
	 * Tax rate
	 * 
	 * @access public 
	 */
	public function tax_rate() {
		
		$subtotal = 0;
		$tax_shipping = 0;
		
		foreach ($this->db->query("SELECT p.product_price, c.cart_id, c.product_quantity FROM " . self::$config['table_products'] . " p, " . self::$config['table_carts'] . " c WHERE p.product_id = c.product_id AND c.cart_session = '" . $this->session->get('cart_session') . "'") as $row) {

			$option_price = 0;
			
			foreach ($this->db->query("SELECT * FROM " . self::$config['table_carts'] . " WHERE cart_id = '" . $row['cart_id'] . "'") as $product_options) {
			
				$data = explode(':', $product_options['options']);
				
				foreach ($data as $value) {
					
					$option_values = $this->db->fetch_row_assoc("SELECT option_price, option_type FROM " . self::$config['table_product_option_values'] . " WHERE option_value_id = '" . $value . "'");
					
					if ($option_values['option_type'] == '+')
						$option_price = $option_price + $option_values['option_price'];
					elseif ($option_values['option_type'] == '-')
						$option_price = $option_price - $option_values['option_price'];
								
				}
			}
	  		
			$subtotal += ($row['product_price'] + $option_price) * $row['product_quantity'];
	  									
        }

		if (self::$config['tax_shipping'])
			$tax_shipping = self::$config['shipping_cost'];
									
		return (($subtotal + $tax_shipping) * self::$config['tax_rate'] / 100);
				
	}
			
	/**
	 * Total
	 * 
	 * @access public 
	 */
	public function total() {
	
		$subtotal = 0;
		$discount = 0;
		$tax_shipping = 0;
		
		foreach ($this->db->query("SELECT p.product_price, c.product_quantity, c.cart_id FROM " . self::$config['table_products'] . " p, " . self::$config['table_carts'] . " c WHERE p.product_id = c.product_id AND c.cart_session = '" . $this->session->get('cart_session') . "'") as $row) {

			$option_price = 0;
			
			foreach ($this->db->query("SELECT * FROM " . self::$config['table_carts'] . " WHERE cart_id = '" . $row['cart_id'] . "'") as $product_options) {
			
				$data = explode(':', $product_options['options']);
				
				foreach ($data as $value) {
					
					$option_values = $this->db->fetch_row_assoc("SELECT option_price, option_type FROM " . self::$config['table_product_option_values'] . " WHERE option_value_id = '" . $value . "'");
					
					if ($option_values['option_type'] == '+')
						$option_price = $option_price + $option_values['option_price'];
					elseif ($option_values['option_type'] == '-')
						$option_price = $option_price - $option_values['option_price'];
								
				}
			}
						
			$subtotal += ($row['product_price'] + $option_price) * $row['product_quantity'];
							
        }

		if ($this->session->get('coupon_id')) {
			
			$counpon_data = $this->db->fetch_row_assoc("SELECT coupon_id, coupon_type, coupon_discount FROM " . self::$config['table_coupons'] . " WHERE coupon_id = '" . $this->session->get('coupon_id') . "'");
			
			if ($counpon_data['coupon_type'] == 'F') 
				$discount = $counpon_data['coupon_discount'];
			elseif ($counpon_data['coupon_type'] == 'P') 
				$discount = $subtotal * $counpon_data['coupon_discount'] / 100;
				
		}

		if (self::$config['tax_shipping'])
			$tax_shipping = self::$config['shipping_cost'];
							
		if ($this->has_shipping())
			return  (($subtotal + $tax_shipping) * self::$config['tax_rate'] / 100) + ($subtotal + self::$config['shipping_cost']) - $discount;
		else
			return  (($subtotal + $tax_shipping) * self::$config['tax_rate'] / 100) + ($subtotal - $discount);
		
	}

	/**
	 * Delete carts
	 * 
	 * @access private 
	 */
	private function delete_carts() {

		foreach ($this->db->query("SELECT cart_id FROM " . self::$config['table_carts'] . " WHERE cart_created < '" . time() . "' - '" . self::$config['cart_expire'] . "'") as $row) {

			$where = array(
				'cart_id' => $row['cart_id']
			);
			
			$this->db->where($where);
			$this->db->delete(self::$config['table_carts']);
			
		}
				
	}

	/**
	 * Get product
	 * 
	 * @access public 
	 */
	public function get_product($product_id) {
		
		$product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
		
		foreach ($this->db->query("SELECT * FROM " . self::$config['table_products'] . " WHERE product_id = '" . $product_id . "'") as $row) {

			$details[] = array(
				'product_name'			=> $row['product_name'],
				'product_description'	=> $row['product_description'],
				'product_image'			=> $row['product_image'],
				'product_thumbnail'		=> $row['product_thumbnail'],
				'product_price'			=> $row['product_price']
			);
			
		}
		
		if (isset($details))
			return $details;
		
	}

	/**
	 * Get product options
	 * 
	 * @access public 
	 */
	public function get_product_options($product_id) {
		
		$product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
		
		$options = array();

		foreach ($this->db->query("SELECT * FROM " . self::$config['table_product_options'] . " WHERE product_id = '" . $product_id . "' ORDER BY position") as $product_options) {

			$option_values = array();
			
			foreach ($this->db->query("SELECT * FROM " . self::$config['table_product_option_values'] . " WHERE option_id = '" . $product_options['option_id'] . "' ORDER BY position") as $row) {

				$option_values[] = array(
					'option_value_id' 	=> $row['option_value_id'],
					'option_value'		=> $row['option_value'],
					'option_price'		=> $row['option_price'],
					'option_quantity'	=> $row['option_quantity'],
					'option_type'		=> $row['option_type'],
					'position'			=> $row['position']
				);

			}
			
			$options[] = array(
				'option_id'		=> $product_options['option_id'],
				'option_name'	=> $product_options['option_name'],
				'position'		=> $product_options['position'],
				'option_values'	=> $option_values
			);
				
		}

		if (isset($options))
			return $options;
		
	}

	/**
	 * Get product images
	 * 
	 * @access public 
	 */
	public function get_product_images($product_id) {
		
		$product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
		
		$images = array();
		
		foreach ($this->db->query("SELECT * FROM " . self::$config['table_product_images'] . " WHERE product_id = '" . $product_id . "'") as $row) {

			$images[] = array(
				'image_id' 	=> $row['image_id'],
				'image'		=> $row['image'],
				'thumbnail'	=> $row['thumbnail']
			);
		
		}

		if (isset($images))
			return $images;
		
	}

	/**
	 * Get products
	 * 
	 * @access public 
	 */
	public function get_products($category_id) {
		
		$category_id = filter_var($category_id, FILTER_SANITIZE_NUMBER_INT);

		foreach ($this->db->query("SELECT * FROM " . self::$config['table_products'] . " p, " . self::$config['table_category_products'] . " cp WHERE p.product_id = cp.product_id AND cp.category_id = '" . $category_id . "'") as $row) {
			
			$products[] = array(
				'product_id'			=> $row['product_id'],
				'product_name'			=> $row['product_name'],
				'product_description'	=> $row['product_description'],
				'product_image'			=> $row['product_image'],
				'product_thumbnail'		=> $row['product_thumbnail'],
				'product_price'			=> $row['product_price']
			);
			
		}
		
		if (isset($products))
			return $products;
		
	}

	/**
	 * Get categories
	 * 
	 * @access public 
	 */
	public function get_categories($category_id = 0) {
		
		$category_id = filter_var($category_id, FILTER_SANITIZE_NUMBER_INT);
		
		$categories = array();
		
		foreach ($this->db->query("SELECT * FROM " . self::$config['table_categories'] . " WHERE parent_id = '" . $category_id . "' AND category_status = 1 ORDER BY category_name ASC") as $row) {

			$categories[] = array(
				'category_id'	=> $row['category_id'],
				'category_name'	=> $row['category_name']
			);
			
		}

		return $categories;
		
	}

	/**
	 * Get category
	 * 
	 * @access public 
	 */
	public function get_category($category_id) {
		
		$category_id = filter_var($category_id, FILTER_SANITIZE_NUMBER_INT);
		
		foreach ($this->db->query("SELECT * FROM " . self::$config['table_categories'] . " WHERE category_id = '" . $category_id . "'") as $row) {

			$category = array(
				'category_id'	=> $row['category_id'],
				'category_name'	=> $row['category_name']
			);
			
		}
		
		if (isset($category))
			return $category;
		
	}

	/**
	 * Has shipping
	 * 
	 * @access public 
	 */
	public function has_shipping() {

		$shipping = false;
		
		foreach ($this->get_cart() as $product) {
			
	  		if ($product['shipping']) {
				
	    		$shipping = true;
				
				break;
				
	  		}		
		}
		
		return $shipping;
			
	}
	
	/**
	 * Update cart
	 * 
	 * @access public 
	 */
	public function update_cart($cart_id, $product_id, $quantity) {
								
		for ($i = 0; $i < count($quantity); $i++) {

			$sql = "SELECT product_id, product_name, product_quantity FROM " . self::$config['table_products'] . " WHERE product_id = '" . $product_id[$i] . "'";
			
			if ($this->db->row_count($sql)) {
				
				$result = $this->db->fetch_row_assoc($sql);
				
				if ($result['product_quantity'] == 0) {
					
					$this->error->set_error('The ' . $result['product_name'] . ' product is no in stock.');
					
				} else {
						
					if ($quantity[$i] < 1) {

						$where = array(
							'product_id' => $product_id[$i]
						);
						
						$this->db->where($where);
						$this->db->delete(self::$config['table_carts']);
						
					} else {
						
						$new_quantity = $quantity[$i];
						
						$cart = $this->db->fetch_row_assoc("SELECT product_quantity, options FROM " . self::$config['table_carts'] . " WHERE product_id = '" . $product_id[$i] . "' AND cart_id = '" . $cart_id[$i] . "' AND cart_session = '" . $this->session->get('cart_session') . "'");
						
						if (!$cart['options']) {
						
							if ($quantity[$i] > $result['product_quantity']) {
								
								$new_quantity = $result['product_quantity'];
								$this->error->set_error('The quantity you have requested for ' . $result['product_name'] . ' is more than that into stock.');
								
							}
						
						} else {

							$data = explode(':', $cart['options']);
							
							foreach ($data as $value) {

								$product_options = $this->db->fetch_row_assoc("SELECT option_value, option_quantity FROM " . self::$config['table_product_option_values'] . " WHERE option_value_id = '" . $value . "'");

								if ($quantity[$i] > $product_options['option_quantity']) {

									$new_quantity = $cart['product_quantity'];
									$this->error->set_error('The quantity you have requested for ' . $result['product_name'] . ' ' . $product_options['option_value'] . ' is more than that into stock.');
								
								}															
							}						
						
						}
						
						$values = array(
							'product_quantity' => $new_quantity
						);
												
						$where = array(
							'cart_session' 	=> $this->session->get('cart_session'),
							'product_id'	=> $product_id[$i]
						);

						if ($cart['options']) 
							$where['options'] = $cart['options'];
													
						$this->db->where($where);
						$this->db->update(self::$config['table_carts'], $values);
									
					}
				}
				
			} else {
				
				$this->error->set_error('Sorry but the product is no longer in stock.');
				
			}
		}
	
	}

	/**
	 * Check coupon
	 * 
	 * @access public 
	 */	
	public function check_coupon($coupon_code) {
	
		$sql = "SELECT coupon_id FROM " . self::$config['table_coupons'] . " WHERE coupon_code = '" . $coupon_code . "' AND date_start < CURDATE() AND date_end > CURDATE() AND coupon_status = 1";
		
		if ($this->db->row_count($sql)) {
		
			$result = $this->db->fetch_row_assoc($sql);
			
			$this->session->set('coupon_id', $result['coupon_id']);
			
		} else {
		
			$this->error->set_error('Coupon error.');
		
		}
		
	}

	/**
	 * Get coupon
	 * 
	 * @access public 
	 */	
	public function get_coupon($coupon_id) {

		$subtotal = 0;
		
		foreach ($this->db->query("SELECT p.product_price, c.product_quantity, c.cart_id FROM " . self::$config['table_products'] . " p, " . self::$config['table_carts'] . " c WHERE p.product_id = c.product_id AND c.cart_session = '" . $this->session->get('cart_session') . "'") as $row) {
			
			$option_price = 0;
			
			foreach ($this->db->query("SELECT * FROM " . self::$config['table_carts'] . " WHERE cart_id = '" . $row['cart_id'] . "'") as $product_options) {
			
				$data = explode(':', $product_options['options']);
				
				foreach ($data as $value) {
					
					$option_values = $this->db->fetch_row_assoc("SELECT option_price, option_type FROM " . self::$config['table_product_option_values'] . " WHERE option_value_id = '" . $value . "'");
					
					if ($option_values['option_type'] == '+')
						$option_price = $option_price + $option_values['option_price'];
					elseif ($option_values['option_type'] == '-')
						$option_price = $option_price - $option_values['option_price'];
								
				}				
			
			}
			
			$subtotal += ($row['product_price'] + $option_price) * $row['product_quantity'];
							
        }
		
		foreach ($this->db->query("SELECT * FROM " . self::$config['table_coupons'] . " WHERE coupon_id = '" . $coupon_id . "'") as $row) {

			if ($row['coupon_type'] == 'F')
				$coupon_discount = $row['coupon_discount'];
			elseif ($row['coupon_type'] == 'P')
				$coupon_discount = ($subtotal * $row['coupon_discount'] / 100);
				
			$details[] = array(
				'coupon_name'		=> $row['coupon_name'],
				'coupon_discount'	=> $coupon_discount
			);

		}
		
		if (isset($details))
			return $details;
			
	}
	
	/**
	 * Checkout
	 * 
	 * @access public 
	 */	
	public function checkout($user_id, $user_email, $payment_method, $comment = NULL) {

		$result = $this->db->fetch_row_assoc("SELECT * FROM " . self::$config['table_customers'] . " customer, " . self::$config['table_addresses'] . " address, " . self::$config['table_countries'] . " countries WHERE customer.user_id = '" . $user_id . "' AND address.country_id = countries.country_id AND address.user_id = '" . $user_id . "'");
					
		$values = array( 
			'user_id' 						=> $user_id, 
			'first_name' 					=> $result['first_name'],
			'last_name' 					=> $result['last_name'],
			'customer_email' 				=> $result['customer_email'],
			'customer_telephone' 			=> $result['customer_telephone'],
			'shipping_company' 				=> $result['company'], 
			'shipping_first_name' 			=> $result['first_name'],
			'shipping_last_name' 			=> $result['last_name'],
			'shipping_address' 				=> $result['address'],
			'shipping_post_code' 			=> $result['post_code'],
			'shipping_city' 				=> $result['city'],
			'shipping_country_id' 			=> $result['country_id'],
			'shipping_zone' 				=> $result['zone'],
			'shipping_method' 				=> 'Flat Rate',
			'payment_company' 				=> $result['company'], 
			'payment_first_name' 			=> $result['first_name'],
			'payment_last_name' 			=> $result['last_name'],
			'payment_address' 				=> $result['address'],
			'payment_post_code' 			=> $result['post_code'],
			'payment_city' 					=> $result['city'],
			'payment_country_id' 			=> $result['country_id'],
			'payment_zone' 					=> $result['zone'],
			'payment_method' 				=> $payment_method,
			'comment' 						=> $comment,
			'total' 						=> $this->total(),
			'tax_description' 				=> self::$config['tax_description'],
			'tax_rate' 						=> self::$config['tax_rate'],
			'currency' 						=> self::$config['currency_symbol'],
			'order_status_description_id' 	=> '1',
			'date_added' 					=> time(),
			'ip' 							=> $_SERVER['REMOTE_ADDR']
		); 			
		
		if ($this->has_shipping())
			$values['shipping_cost'] = self::$config['shipping_cost'];
		else		
			$values['shipping_cost'] = 0;

		if (self::$config['tax_shipping'])
			$values['tax_shipping'] = 1;
		else
			$values['tax_shipping'] = 0;
			
		if ($this->session->get('coupon_id')) {
		
			$counpon_data = $this->db->fetch_row_assoc("SELECT coupon_name, coupon_type, coupon_discount FROM " . self::$config['table_coupons'] . " WHERE coupon_id = '" . $this->session->get('coupon_id') . "'");
			
			$values['coupon_name'] = $counpon_data['coupon_name'];
			
			if ($counpon_data['coupon_type'] == 'F')
				$values['coupon_discount'] = $counpon_data['coupon_discount'];
			elseif ($counpon_data['coupon_type'] == 'P')
				$values['coupon_discount'] = $this->subtotal() * $counpon_data['coupon_discount'] / 100;			

		}
		
		$this->db->insert(self::$config['table_orders'], $values);
		
		$order_id = $this->db->last_insert_id();
		
		$values = array(
			'order_id' 						=> $order_id,
			'order_status_description_id' 	=> '1',
			'date_added'					=> time()
		);
		
		$this->db->insert(self::$config['table_order_status'], $values);
		
		$values = array();

		$values['order_id'] = $order_id;
		$values['tax'] = self::$config['tax_rate'];
		
		foreach ($this->db->query("SELECT p.product_id, p.product_name, p.product_price, c.product_quantity, c.options FROM " . self::$config['table_products'] . " p, " . self::$config['table_carts'] . " c WHERE p.product_id = c.product_id AND c.cart_session = '" . $this->session->get('cart_session') . "'") as $row) {

			$values['product_id'] = $row['product_id'];
			$values['product_name'] = $row['product_name'];
			$values['product_price'] = $row['product_price'];
			$values['product_quantity'] = $row['product_quantity'];
			$values['total'] = ($row['product_price'] * $row['product_quantity']);
			
			$this->db->insert(self::$config['table_order_products'], $values);
			
			if ($row['options']) {

				$data = explode(':', $row['options']);
				
				foreach ($data as $value) {

					$order_products = $this->db->fetch_row_assoc("SELECT order_product_id FROM order_products ORDER BY order_product_id DESC");

					$option_data = $this->db->fetch_row_assoc("SELECT po.option_name, pov.option_value_id, pov.option_value, pov.option_price, pov.option_type FROM " . self::$config['table_product_options'] . " po, " . self::$config['table_product_option_values'] . " pov WHERE  po.option_id = pov.option_id AND pov.option_value_id = '" . $value . "'");

					$options = array(
						'order_id' 			=> $order_id,
						'order_product_id'	=> $order_products['order_product_id'],
						'option_value_id'	=> $option_data['option_value_id'],
						'option_name' 	 	=> $option_data['option_name'],
						'option_value'      => $option_data['option_value'],
						'option_price'		=> $option_data['option_price'],
						'option_type'		=> $option_data['option_type']
					);
				
					$this->db->insert(self::$config['table_order_options'], $options);
	
				}
							
			}
							
        }
				
		foreach ($this->db->query("SELECT dg.*, c.product_id FROM " . self::$config['table_digital_goods'] . " dg, " . self::$config['table_carts'] . " c WHERE dg.product_id = c.product_id AND c.cart_session = '" . $this->session->get('cart_session') . "'") as $row) {

			$values = array(
				'order_id' 				=> $order_id,
				'product_id' 			=> $row['product_id'],
				'display_filename' 		=> $row['display_filename'],
				'filename' 	 			=> $row['filename'],
				'date_added'            => time(),
				'number_days' 			=> $row['number_days'],
				'number_downloadable' 	=> $row['number_downloadable'],
				'download_hash'			=> md5(time())
			);
				
			$this->db->insert(self::$config['table_order_digital_goods'], $values);
			
		}	
							
		$count = 1;
		
		$checkout  = "?cmd=_cart";
		$checkout .= "&upload=1";
		$checkout .= "&business=" . urlencode(self::$config['paypal_email']);
		$checkout .= "&custom=" . urlencode($order_id);
		$checkout .= "&currency_code=" . urlencode(self::$config['currency_code']);
		$checkout .= "&country=" . urlencode($result['iso_code_2']);
		$checkout .= "&return=" . urlencode(self::$config['paypal_return']);
		$checkout .= "&cancel_return=" . urlencode(self::$config['paypal_cancel_return']);
		$checkout .= "&notify_url=" . urlencode(self::$config['paypal_notify_url']);

		if (self::$config['tax_shipping'])
			$checkout .= "&tax_cart=" . urlencode(number_format(($this->subtotal() + self::$config['shipping_cost']) * self::$config['tax_rate'] / 100, 2, '.', ','));
		else	
			$checkout .= "&tax_cart=" . urlencode(number_format($this->subtotal() * self::$config['tax_rate'] / 100, 2, '.', ','));

		if ($this->session->get('coupon_id')) {

			foreach ($this->get_coupon($this->session->get('coupon_id')) as $value)
				$checkout .= "&discount_amount_cart=" . urlencode(number_format($value['coupon_discount'], 2, '.', ','));
				
		}
							
		if ($this->has_shipping())
			$checkout .= "&shipping_1=" . urlencode(number_format(self::$config['shipping_cost'], 2, '.', ','));
			
		foreach ($this->get_cart() as $value) {

			$result = $this->db->fetch_row_assoc("SELECT product_quantity FROM " . self::$config['table_products'] . " WHERE product_id = '" . $value['product_id'] . "'");
			
			if (!empty($value['options'][0]['option_value_id'])) {
				
				foreach ($value['options'] as $option) {
				
					$values = array(
						'product_quantity' => $option['option_quantity'] - $value['product_quantity']
					); 
				
				}
				
			} else {
				
				$values = array(
					'product_quantity' => $result['product_quantity'] - $value['product_quantity']
				); 	
									
			}

			$where = array(
				'product_id' => $value['product_id']
			);
			
			$this->db->where($where);
			$this->db->update(self::$config['table_products'], $values);
							
			$i = 0;
			
			foreach ($value['options'] as $option) {

				$values = array(
					'option_quantity' => $option['option_quantity'] - $value['product_quantity']
				); 

				$where = array(
					'option_value_id' => $option['option_value_id']
				);
									
				$this->db->where($where);
				$this->db->update(self::$config['table_product_option_values'], $values);
				
				$checkout .= "&on" . $i . "_" . $count . "=" . urlencode($option['option_name']);
				$checkout .= "&os" . $i . "_" . $count . "=" . urlencode($option['option_value']);
				
				$i++;
					
			}
			
			$checkout .= "&item_number_" . $count . "=" . urlencode($value['product_id']);
			$checkout .= "&item_name_" . $count . "=" . urlencode($value['product_name']);
			$checkout .= "&amount_" . $count . "=" . urlencode(number_format($value['product_price'], 2, '.', ','));
			$checkout .= "&quantity_" . $count . "=" . urlencode($value['product_quantity']);
									
			$count++;
			
		}

		$result = $this->db->fetch_row_assoc("SELECT * FROM " . self::$config['table_customers'] . " customer, " . self::$config['table_addresses'] . " address, " . self::$config['table_countries'] . " countries WHERE customer.user_id = '" . $user_id . "' AND address.country_id = countries.country_id AND address.user_id = '" . $user_id . "'");

		$message = file_get_contents(self::$config['absolute_path'] . 'templates/mail/order.tpl');
		
		$message = str_replace('%%ORDER_ID%%', $order_id, $message);
		$message = str_replace('%%DATE%%', strftime("%A %d %B %Y"), $message);
		
		foreach ($this->get_cart() as $value) {
			
			$products .= $value['product_quantity'] . ' x ' . $value['product_name'] . ' (' . price($value['product_price']) . ') = ' . price($value['total_price']) . "<br />";

			foreach ($value['options'] as $option) {
			
				if (isset($option['option_value'])) {
					
					if (reset($value['options']) === $option)
						$products .= '&nbsp;&nbsp; - ' . $option['option_value'];
					elseif (end($value['options']) === $option)
						$products .= ' - ' . $option['option_value'] . "<br />";
					else
						$products .= ' - ' . $option['option_value'];
						
				}
				
			}
			
		}

		$message = str_replace('%%PRODUCTS%%', $products, $message);

		$message = str_replace('%%SUB_TOTAL%%', price($this->subtotal()), $message);		
		$message = str_replace('%%SHIPPING_COST%%', price($this->shipping_cost()), $message);
		
		if (self::$config['tax_rate'] != 0)
			$message = str_replace('%%TAX%%', self::$config['tax_description'] . ' ' . self::$config['tax_rate'] . ' %: ' . price($this->tax_rate()), $message);
		else
			$message = str_replace('%%TAX%%', '', $message);

		if ($this->session->get('coupon_id')) {

			foreach ($this->get_coupon($this->session->get('coupon_id')) as $value)
				$message = str_replace('%%COUPON%%', $value['coupon_name'] . ' : - ' . price($value['coupon_discount']), $message);
			
		} else {
			
			$message = str_replace('%%COUPON%%', '', $message);
			
		}
		
		$message = str_replace('%%TOTAL%%', price($this->total()), $message);

		$message = str_replace('%%FIRST_NAME%%', $result['first_name'], $message);
		$message = str_replace('%%LAST_NAME%%', $result['last_name'], $message);
		$message = str_replace('%%COMPANY%%', $result['company'], $message);
		$message = str_replace('%%ADDRESS%%', $result['address'], $message);
		$message = str_replace('%%CITY%%', $result['city'], $message);
		$message = str_replace('%%POST_CODE%%', $result['post_code'], $message);
		$message = str_replace('%%COUNTRY%%', $result['country_name'], $message);
		$message = str_replace('%%ZONE%%', $result['zone'], $message);
		
		$message = str_replace('%%SITE_TITLE%%', self::$config['site_title'], $message);
		$message = str_replace('%%SITE_URL%%', self::$config['site_url'], $message);

		$headers  = 'From: ' . self::$config['site_title'] . '<' . self::$config['admin_email'] . '>' . "\r\n";
		$headers .=	'Reply-To: ' . self::$config['admin_email'] . "\r\n";
		$headers .=	'X-Mailer: PHP/' . phpversion() . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n"; 
				    
		mail($result['customer_email'], self::$config['site_title'] . ' - ' . self::$config['email_subject'], $message, $headers);
		
		if (self::$config['new_order_notification']) {
		
			$message = file_get_contents(self::$config['absolute_path'] . 'templates/mail/new_order.tpl');

			$message = str_replace('%%FIRST_NAME%%', $result['first_name'], $message);
			$message = str_replace('%%LAST_NAME%%', $result['last_name'], $message);
			$message = str_replace('%%EMAIL%%', $result['customer_email'], $message);

			$message = str_replace('%%ORDER_ID%%', $order_id, $message);
			$message = str_replace('%%DATE%%', strftime("%A %d %B %Y"), $message);
			
			$message = str_replace('%%SITE_TITLE%%', self::$config['site_title'], $message);
			$message = str_replace('%%SITE_URL%%', self::$config['site_url'], $message);
						
			mail(self::$config['admin_email'], self::$config['site_title'] . ' - ' . self::$config['email_subject'], $message, $headers);
		
		}
			
		$this->empty_cart();
		$this->session->delete('coupon_id');
		
		if ($payment_method == 'PayPal') {
			
			if (config_item('cart', 'paypal_sandbox'))
				header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr' . $checkout);
			else
				header('Location: https://www.paypal.com/cgi-bin/webscr' . $checkout);
		
		} else {
			
			$this->session->set('payment_method', $payment_method);
			
			header('Location: ' . config_item('cart', 'site_url') . 'checkout_success.php');

		}
		
	}
		
}

?>
