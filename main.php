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
<link rel="stylesheet" href="css/mycss.css">

</head>
<body>
	<br />
	<div class="container" style="width: 80%;">
		<h1>E-Commerce Catalog</h1>
		<h3>Products items</h3>
		<br />
		<div class="design_card">
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

                        class Category {
                            public $counter;

                            public $id;

                            public $title;

                            public $list_attributes;

                            public function __construct($id, $title, $list_attributes) {
                                $this->id = $id;
                                $this->title = $title;
                                $this->counter = 0;
                                $this->list_attributes = $list_attributes;
                            }
                        }

                        class Product {

                            public $id;

                            public $title;

                            public $categories;

                            public $price;

                            public $labels;

                            public function __construct($id, $title, $categories, $price, $labels) {
                                $this->id = $id;
                                $this->title = $title;
                                $this->categories = $categories;
                                $this->price = $price;
                                $this->labels = $labels;
                            }
                        }

                        
                        class Label {

                            public $id;

                            public $title;

                            public $counter;

                            public function __construct($id, $title){
                                $this->id = $id;
                                $this->title = $title;
                                $this->counter = 0;
                            }
                        }

                        class Attribute {

                            public $id;

                            public $title;

                            public $arrLabel;

                            // associative array
                            public function __construct($id, $title, $arrLabel) {
                                $this->id = $id;
                                $this->title = $title;
                                $this->arrLabel = $arrLabel;
                            }
                        }

                        

                        function getProductsList($obj, $allCategories) {
                            $products = array();
                            $i = 0;
                            foreach ($obj['products'] as $prod) { // Loop on all products
                                $categoryForItem = array();
                                $attributesForItem = array(); 

                                foreach ($prod['categories'] as $cat) { // Loop on all categories in product
                                    
                                    $categoryForItem[$i] = $allCategories[$cat['id']]; 
                                    
                                    foreach ($prod['labels'] as $labP) {  //Loop on all labels in product
                                        
                                        foreach ($categoryForItem[$i]->list_attributes as $at) {

                                            //cheack if label exist in arrLabel
                                            if(array_key_exists($labP ,$at->arrLabel)) {
                                                    
                                                $key = $at->title; //key = title of attribute
                                                $value = $at->arrLabel[$labP]; //value = label
                                                    
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
  
                                    // create product
                                    $products[$i] = new Product($prod['id'], $prod['title'], $categoryForItem, $prod['price'], $attributesForItem);                               
                            }
                            return $products;
                        }

                        
                        
                        function getCategories($obj) {
                            $categories_list = array(); //get all categories (include counters for each category)
                            
                            foreach ($obj['products'] as $prod) { // Loop on all products
   
                                foreach ($prod['categories'] as $cat) { // Loop on all categories in product
                                    
                                    $id_cat = $cat['id']; //get id of the category
                                    
                                    if (! array_key_exists($id_cat, $categories_list)) { // if the category *not* in $categories_list
                                        
                                        $list_attributes = array();
                                        $new_cat = new Category($cat['id'], $cat['title'], $list_attributes); //create new category
                                        $categories_list[$id_cat] = $new_cat; //add new category to $categories_list
                                        $categories_list[$id_cat]->counter++;// counter ++;
                                    
                                    } else { //if the category in $categories_list - do counter++
                                        $categories_list[$id_cat]-> counter ++;
                                    }
                                }
                            }
                            
                            return $categories_list;
                        }
                        
          
                        
                        function createListAttributes($obj, $category, $label) {
                            
                            foreach ($obj['attributes'] as $attribute) { //Loop on all attributes      
                                $id_attribute = $attribute['id'];
                                
                                foreach ($attribute['labels'] as $lab) { //Loop on all labels per attribute
                                    
                                    if ($label == $lab['id']) { //if the label in the current product is equal to the label in attributes
                                        $id_label = $lab['id'];
                                        
                                        //if $id_attribute not exist in $categories_list
                                        if (! array_key_exists($id_attribute, $category->list_attributes)) {
                                            // create new attribute and add him to $attributes_list
                                            $list_labels = array();
                                            $new_attribute = new Attribute($attribute['id'], $attribute['title'], $list_labels);
                                            $category->list_attributes[$id_attribute] = $new_attribute;
                                            
                                            // create new label and add it to arrLabel
                                            $new_label = new Label($lab['id'], $lab['title']);
                                            $category->list_attributes[$id_attribute]->arrLabel[$id_label] = $new_label;
                                            $category->list_attributes[$id_attribute]->arrLabel[$id_label]->counter ++;
                                        }
                                        else { //if $id_attribute already exist in $categories_list 
                                            
                                            //if $id_label not exist in list_attributes
                                            if (! (array_key_exists($id_label, $category->list_attributes[$id_attribute]->arrLabel))) {
                                                
                                                // create new label and add it to $list_labels
                                                $new_label = new Label($lab['id'], $lab['title']);
                                                $category->list_attributes[$id_attribute]->arrLabel[$id_label] = $new_label;
                                                $category->list_attributes[$id_attribute]->arrLabel[$id_label]->counter ++;
                                            
                                            } else { //if the label alrady in list_attributes - do counter to label++
                                                $category->list_attributes[$id_attribute]->arrLabel[$id_label]->counter ++;
                                            }
                                        }
                                    }
                                }
                            }
                            
                            return $category;
                        }
                        
                        function getAllCategories($obj) {
                            $categories_list = getCategories($obj);
                            
                            foreach ($obj['products'] as $prod) { // Loop on all products
                                
                                foreach ($prod['categories'] as $cat) { // Loop on all categories in product
                                    $cat_id = $cat['id']; //get id of the category
                                    
                                    foreach ($prod['labels'] as $label) { //Loop on all labels in product     
                                        $categories_list[$cat_id] = createListAttributes($obj, $categories_list[$cat_id],$label);
                                    }
                                }
                            }
                            return $categories_list;
                        }

                        
                        $data = file_get_contents("https://backend-assignment.bylith.com/index.php");
                        $obj = json_decode($data, true);
                        
                        $allCat = getAllCategories($obj); //get ctegories list 
                        $allProduct = getProductsList($obj, $allCat); //get product list
                        

                        //display table of all items
                        foreach ($allProduct as $p) {
                            echo '<tr>';
                            echo '<td>' . $p->id . '</td>';
                            echo '<td>' . $p->title . '</td>';
                            echo '<td>' . $p->price .'$'.'</td>';
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
	</div>
	<br />
</body>
</html>
