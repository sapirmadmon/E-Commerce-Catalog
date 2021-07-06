<!DOCTYPE html>
<html>
<head>
<title>E-Commerce Catalog</title>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
	<br />
	<div class="container" style="width: 80%;">
		<h3>E-Commerce Catalog - Product items</h3>
		<br />
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Price</th>
					<th>Attributes</th>
					<th>Categories</th>

				</tr>  
                          <?php

                        class Category
                        {

                            public $counter;

                            public $id;

                            public $title;

                            public $list_attributes;

                            public function __construct($id, $title, $list_attributes)
                            {
                                $this->id = $id;
                                $this->title = $title;
                                $this->counter = 0;
                                $this->list_attributes = $list_attributes;
                            }

                            public function __toString()
                            {
                                return $this->title;
                            }
                        }

                        class Product
                        {

                            public $id;

                            public $title;

                            public $categories;

                            public $price;

                            public $labels;

                            public function __construct($id, $title, $categories, $price, $labels)
                            {
                                $this->id = $id;
                                $this->title = $title;
                                $this->categories = $categories;
                                $this->price = $price;
                                $this->labels = $labels;
                            }
                        }

                        class Label
                        {

                            public $id;

                            public $title;

                            public $counter;

                            public function __construct($id, $title)
                            {
                                $this->id = $id;
                                $this->title = $title;
                                $this->counter = 0;
                            }

                            public function __toString()
                            {
                                return $this->title. " ";
                            }
                        }

                        class Attribute
                        {

                            public $id;

                            public $title;

                            public $arrLabel;

                            // associative array
                            public function __construct($id, $title, $arrLabel)
                            {
                                $this->id = $id;
                                $this->title = $title;
                                $this->arrLabel = $arrLabel;
                            }

                            public function __toString()
                            {
                                return $this->title . ': ';
                            }
                        }

                        

                        function getProductsList($obj, $allCategories)
                        {
                            $products = array();
                            $i = 0;
                            foreach ($obj['products'] as $prod) {
                                $categoryForItem = array();
                                $attributesForItem = array(); 

                                foreach ($prod['categories'] as $cat) {
                                    
                                    $categoryForItem[$i] = $allCategories[$cat['id']];
                                    
                                    foreach ($prod['labels'] as $labP) {
                                        
                                        foreach ($categoryForItem[$i]->list_attributes as $at) {

                                            if(array_key_exists($labP ,$at->arrLabel)) {
                                                    
                                                $key = $at->title;
                                                $value = $at->arrLabel[$labP];
                                                    
                                                if (!array_key_exists($key, $attributesForItem)) { // if key did not exist in $attributesForItem - add it
                                                    $attributesForItem[$key] = array($labP => $value);
                                                } else if(!array_key_exists($labP, $attributesForItem[$key])) { // key in $attributesForItem, add just value into the value array
                                                    $attributesForItem[$key][$labP] = $value;
                                                    }
                                                }
                                            }
                                        }  
                                        
                                        $i ++;
                                    }
                                   

                                // create the product
                                    $products[$i] = new Product($prod['id'], $prod['title'], $categoryForItem, $prod['price'], $attributesForItem);
                                    print '<pre>' . print_r( $products[$i], true) . '</pre>';
                                
                            }

                            return $products;
                            
                        }

                        
                        function getAllCategories($obj)
                        {
                            $categories_list = array();
                            foreach ($obj['products'] as $prod) { // $prod - the current product

                                foreach ($prod['categories'] as $cat) { // $cat - the current category in product

                                    $id_cat = $cat['id'];

                                    if (! array_key_exists($id_cat, $categories_list)) { // if the category *not* in $categories_list
                                        $list_attributes = array();
                                        $new_cat = new Category($cat['id'], $cat['title'], $list_attributes);
                                        $categories_list[$id_cat] = $new_cat;
                                        $categories_list[$id_cat]->counter ++;
                                    } else {
                                        $categories_list[$id_cat]->counter ++;
                                    }
                                }
                            }

                            foreach ($obj['products'] as $prod) {
                                foreach ($prod['categories'] as $cat) {

                                    $cat_id = $cat['id'];
                                    foreach ($prod['labels'] as $label) {

                                        foreach ($obj['attributes'] as $attribute) {

                                            $id_attribute = $attribute['id'];

                                            foreach ($attribute['labels'] as $lab) {

                                                if ($label == $lab['id']) {

                                                    $id_label = $lab['id'];

                                                    if (! array_key_exists($id_attribute, $categories_list[$cat_id]->list_attributes)) {
                                                        // create new attribute and add him to $attributes_list
                                                        $list_labels = array();
                                                        $new_attribute = new Attribute($attribute['id'], $attribute['title'], $list_labels);
                                                        $categories_list[$cat_id]->list_attributes[$id_attribute] = $new_attribute;

                                                        // create new label and add it to arrLabel
                                                        $new_label = new Label($lab['id'], $lab['title']);
                                                        $categories_list[$cat_id]->list_attributes[$id_attribute]->arrLabel[$id_label] = $new_label;
                                                        $categories_list[$cat_id]->list_attributes[$id_attribute]->arrLabel[$id_label]->counter ++;
                                                    } 
                                                    else {

                                                        if (! (array_key_exists($id_label, $categories_list[$cat_id]->list_attributes[$id_attribute]->arrLabel))) {

                                                            // create new label and add it to $list_labels
                                                            $new_label = new Label($lab['id'], $lab['title']);
                                                            $categories_list[$cat_id]->list_attributes[$id_attribute]->arrLabel[$id_label] = $new_label;
                                                            $categories_list[$cat_id]->list_attributes[$id_attribute]->arrLabel[$id_label]->counter ++;
                                                        } else {
                                                            $categories_list[$cat_id]->list_attributes[$id_attribute]->arrLabel[$id_label]->counter ++;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            return $categories_list;
                        }

                        $data = file_get_contents("https://backend-assignment.bylith.com/index.php");
                        $obj = json_decode($data, true);
                        print print_r($obj['products'], true);
                        
                        $allCat = getAllCategories($obj);
                        $allProduct = getProductsList($obj, $allCat);
                        

                        //display table of all items
                        foreach ($allProduct as $p) {
                            echo '<tr>';
                            echo '<td>' . $p->id . '</td>';
                            echo '<td>' . $p->title . '</td>';
                            echo '<td>' . $p->price . '</td>';
                            echo '<td>';
                            foreach ($p->labels as  $at => $key)  {
                                echo '<b>' . $at. ':</b> ';
                                foreach ($key as $k) {
                                    echo $k->title.',';
                                }
                                echo '<br/>';
                            }
                            echo '</td>';
                            echo '<td>';
                            foreach ($p->categories as $cat) {
                                echo $cat->title, '<br/>'; 
                            }
                            echo '</td>';
                            echo '</tr>';
                        }

                        ?>  
                     </table>
		</div>
	</div>
	<br />
</body>
</html>
