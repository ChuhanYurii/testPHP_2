<?php

class Product {

	/*
	 * Удаление продукта по идентификатору
	 */

	public static function delete($mysqli, $aId) {
		$lId = (int) $aId;
		if ($lId < 0 || $lId > PHP_INT_MAX) {
			return false;
		}
		$result = $mysqli->query("DELETE FROM `products` WHERE `id`=" . $lId);
		return $result;
	}

	/*
	 * Получение продукта по идентификатору
	 */

	public static function newInstance($mysqli, $aId) {
		$lId = (int) $aId;
		if ($lId < 0 || $lId > PHP_INT_MAX) {
			return false;
		}
		$result = $mysqli->query("SELECT * FROM `products` WHERE `id`=" . $lId . " LIMIT 1");
		if ($result !== false) {
			$row = $result->fetch_assoc();
			$product = new self();
			$product->id = $row['id'];
			$product->title = $row['title'];
			$product->price = $row['price'];
			$product->image = $row['image'];
			$product->description = $row['description'];
			return $product;
		} else {
			return false;
		}
	}

	public static function newEmptyInstance() {
		return new self();
	}

	public static function find($mysqli) {
		$query = "SELECT `id` FROM `products`";
		$result = $mysqli->query($query);
		if ($result !== false) {
			$lReturnProducts = array();
			while ($row = $result->fetch_assoc()) {
				$lReturnProducts[] = self::newInstance($mysqli, $row['id']);
			}
			return $lReturnProducts;
		} else {
			return false;
		}
	}

	public static function count($mysqli) {
		$query = "SELECT COUNT(`id`) as `count` FROM `products`";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		$count = (int) $row['count'];
		return $count;
	}

	private $id;
	private $title;
	private $price;
	private $image;
	private $description;

	private function __construct() {
		
	}

	public function getId() {
		return $this->id;
	}

	public function setTitle($aTitle) {
		$this->title = $aTitle;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setPrice($aPrice) {
		$this->price = $aPrice;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setImage($aImage) {
		$this->image = $aImage;
	}

	public function getImage() {
		return $this->image;
	}

	public function setDescription($aDescription) {
		$this->description = $aDescription;
	}

	public function getDescription() {
		return $this->description;
	}

	public function save($mysqli) {
		if (isset($this->id)) {
			$this->_update($mysqli);
		} else {
			$this->_insert($mysqli);
		}
	}

	private function _update($mysqli) {
		$mysqli->query("UPDATE `products` SET `title`='{$this->title}', "
				. "`price`='{$this->price}', `image`='{$this->image}', "
				. "`description`='{$this->description}' WHERE `id`={$this->id}");
	}

	private function _insert($mysqli) {
		$mysqli->query("INSERT INTO `products` (`title`, `price`, `image`, `description`)"
				. " VALUES ('{$this->title}', '{$this->price}', '{$this->image}', '{$this->description}')");
		$new_id = $mysqli->insert_id;
		$this->id = $new_id;
	}

}
