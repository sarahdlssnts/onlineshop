<?php 
	session_start();
	require_once('connection.php');
	$order = "";
	$content='';
	
$content.=<<< END
<div class='container-fluid container-fluid1 bodypad' >
	<div class='row row1'>
		
	</div>

END;

$content.=<<<END
	<div class='row row1'>
		<div class='col-md-12'>
			<div class='col-md-2 blue'>

				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Sort by <span class="cairo_ps_surface_restrict_to_level(surface, level)t"></span>
					</button>


					<ul class="dropdown-menu">
END;
							if(isset($_GET['category']) )
							{$uri = strtok($_SERVER["REQUEST_URI"],'&')."&";}
							else
							{$uri = strtok($_SERVER["REQUEST_URI"],'?')."?"; }

						
$content.=<<<END
						<li><a href="$uri sort=LowtoHigh">Price Low to High</a></li>
						<li><a href="$uri sort=HightoLow">Price High to Low</a></li>
						<li><a href="$uri sort=NewArrivals">New Arrivals</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</div>
				<div>
				<ul class="nav nav-pills nav-stacked itemlist_ul">
END;
					
						$sql = "SELECT  COUNT(*) count FROM items";
						$result = mysqli_query($conn, $sql);
						
						while($row = mysqli_fetch_assoc($result)){
							$content.= "<li>
							<a href='?category=All'>All
							<span class='badge pull-right'>".$row['count']."</span>
							</a>
							</li>";
						}		

						$sql = "SELECT type, COUNT(type) count FROM items GROUP BY type";
						$result = mysqli_query($conn, $sql);
						
						while($row = mysqli_fetch_assoc($result)){
							$content.= "<li>
							<a href='?category=".$row['type']."'>
							<span class='badge pull-right'>".$row['count']."</span>".$row['type']."
							</a>
							</li>";
						}

$content.=<<<END
				</ul>

			</div>
			</div>

				<div class='col-md-10 '>

					<div class='row red'>

					
END;
					

					if (isset($_GET['category'])){
					if($_GET['category']!='All')
					{$sql = "SELECT * FROM items WHERE type='$_GET[category]'";}

					else
					{$sql = "SELECT * FROM items";}
					}else
					{$sql = "SELECT * FROM items";}

					if(isset($_GET['sort'])){
						if($_GET['sort']=="LowtoHigh")
							{$sql .= " ORDER BY price ASC";}
						elseif($_GET['sort']=="HightoLow")
							{$sql .= " ORDER BY price DESC";}
						elseif($_GET['sort']=="NewArrivals")
							{$sql .= " ORDER BY id DESC";}
					}

					$result = mysqli_query($conn, $sql);
					
					while($row =mysqli_fetch_assoc($result) ){
						$id = $row['id'];
						$name = $row['name'];
						$description= $row['description'];
						$imagesrc= $row['img_url'];
						$type = $row['type'];
						$price = $row['price'];
						$quantity = $row['quantity'];
						$font_color='black';
						if ($quantity == 0){
							$font_color='gray';
						}

						

						$content.= "<div class='col-md-4 green' style='color: ".$font_color.";'>";
						$content.= "<img class='items_img img-responsive 'src ='$imagesrc'>";

						$content.= "<div class='item_info'><span class='name'><hr>";
						$content.= $name;
					
						$content.= "</span><br>(";
						$content.= $description;
						$content.= ")<br>";
						$content.= $type;
						$content.= "<br>P";
						$content.= $price;
						$content.= ".00<br>";   
						$content.= $quantity." Pieces";
						$content.= "</div>";
						$item_status = '';

						if ($quantity ==0){
							$item_status = 'disabled';
						}


	// if ((isset($_SESSION['signup_msg'])) && ($_SESSION['signup_msg'])){
	// 						$content.='kkkkkkkk';
	// 					}
						
$c='';	
$c=<<<END
						<div>
						<div class='col-xs-12  col-md-12 add_to_cart'>
							<form>
										
							<input id="item_id$id" type='hidden' value='$id' name='item_id'>
							<input id="qty$id" class='form-control' type='number' name='qty' min=1 max=$quantity value=1>
							
								
							<input type='button' $item_status id="$id" class= ' ' value='Add to Cart' name='addtocart' onclick='addCart(this.id);'></input>
									
							</form>
						</div>	
						</div>				
END;



						if(isset($_SESSION['username'])){
							$content.= $c;
							// $content.= $id;
						}

						$content.= "</div>";

					}
					

$content.=<<<END
					</div>
				</div>
			</div>
		</div>	
	</div>
<div>
END;

	

	$cart_active=' ';
	$contact_active=' ';
	$home_active='active';

		// echo $content;
		include_once('template.php');
		 // include_once('footer.php');
?>

<script type="text/javascript">
	function addCart(id){
		var a = "#qty"+id;
		var b = "#item_id"+id;
		var qty = $(a).val();
		var item_id = $(b).val();
	    $.ajax({
	    	url: "addtocart.php",
	    	type: 'GET',
	    	data: { qty: qty, item_id : item_id} ,
	    	success: function(result){
	        	alert("item added to cart");
	    	}
		});
	};


	// $(".itemlist_ul").click(function(){

	// 		$("li").css("background-color", "black	");
	// });



</script>




