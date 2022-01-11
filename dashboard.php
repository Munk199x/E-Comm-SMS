<?php echo '<script src="js/chart/chart.js"></script>'; ?>

<?php 

require_once 'includes/header.php'; ?>

<?php 

$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = "";
while ($orderResult = $orderQuery->fetch_assoc()) {
      $totalRevenue = $orderResult['paid'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;



?>


<style type="text/css">
	.ui-datepicker-calendar {
		display: none;
	}
</style>

<!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.print.css" media="print">


<div class="row">
	
	<div class="col-md-4">
		<div class="panel panel-success">
			<div class="panel-heading">
				
				<a href="product.php" style="text-decoration:none;color:black;">
					Total Products
					<span class="badge pull pull-right"><?php echo $countProduct; ?></span>	
				</a>
				
			</div> <!--/panel-hdeaing-->
		</div> <!--/panel-->
	</div> <!--/col-md-4-->

		<div class="col-md-4">
			<div class="panel panel-info">
			<div class="panel-heading">
				<a href="orders.php?o=manord" style="text-decoration:none;color:black;">
					Total Orders Generated
					<span class="badge pull pull-right"><?php echo $countOrder; ?></span>
				</a>
					
			</div> <!--/panel-hdeaing-->
		</div> <!--/panel-->
		</div> <!--/col-md-4-->

	<div class="col-md-4">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<a href="product.php" style="text-decoration:none;color:black;">
					 Low Stock
					<span class="badge pull pull-right"><?php echo $countLowStock; ?></span>	
				</a>
				
			</div> <!--/panel-hdeaing-->
		</div> <!--/panel-->
	</div> <!--/col-md-4-->
	<?php

	$stNu = '';
	$stLaberPr = '';
	$sql = "SELECT SUM(ot.quantity) AS `Total_Products_Order`,p.product_name,p.product_id
			FROM `orders` o,`order_item` ot,`product` p 
			WHERE o.order_id = ot.order_id 
			AND ot.product_id = p.product_id
			GROUP BY p.product_name
			ORDER BY p.product_name";

	$result = $connect->query($sql);

	while ($fetch = $result->fetch_assoc()){
			$Total_Products_Order = $fetch['Total_Products_Order'];
			$stNu = $stNu.$fetch['Total_Products_Order'].",";		
			$Product_name = $fetch['product_name'];

			$productarray[] = $fetch['product_name'];
			$productnumber[$fetch['product_name']] = $fetch['Total_Products_Order'];
			
			$stLaberPr = $stLaberPr . '"'. $Product_name  ." - ". $fetch['Total_Products_Order'].'",';
			

			//echo $Total_Products_Order.'<br>';
	}

	?>


	<div class="col-md-6">
		<div class="card">

		  <div class="cardContainer">
		    <!--<span><?php echo date('l') .' '.date('d').', '.date('Y'); ?></span>-->
			
	<?php	echo '	
			<canvas id="myChartOrderedProductsOne" class="printable" width="800" height="450">
			</canvas>	
			<script>
				var ctx = "myChartOrderedProductsOne";
				var myChart = new Chart(ctx, {
				    type: "pie",
				    data: {
				   	 datasets: [{
					          label: "Bar",
					          data: ['.$stNu.'],
						     
						  borderColor: ["rgba(255,99,132,1)","rgba(54,162,235,1)","rgba(255,206,86,1)","rgba(75,192,192,1)","rgba(153,102,255,1)","rgba(67,54,154,1)","rgba(224,155,136,1)","rgba(0,159,84,1)","rgba(125,144,49,1)","rgba(227,45,178,1)","rgba(17,121,44,1)","rgba(113,49,133,1)","rgba(253,136,115,1)","rgba(154,53,41,1)","rgba(236,231,111,1)","rgba(98,3,101,1)","rgba(243,100,16,1)","rgba(172,219,118,1)","rgba(160,252,214,1)","rgba(203,108,96,1)","rgba(223,17,108,1)","rgba(111,71,177,1)","rgba(251,34,54,1)","rgba(84,65,39,1)"],
						  backgroundColor: ["rgba(255, 99, 132, 0.2)","rgba(54, 162, 235, 0.2)","rgba(255, 206, 86, 0.2)","rgba(75,192,192,0.2)","rgba(153, 102, 255, 0.2)","rgba(67,54,154,0.2)","rgba(224,155,136,0.2)","rgba(0,159,84,0.2)","rgba(125,144,49,0.2)","rgba(227,45,178,0.2)","rgba(17,121,44,0.2)","rgba(113,49,133,0.8)","rgba(253,136,115,0.2)","rgba(154,53,41,0.2)","rgba(236,231,111,0.2)","rgba(98,3,101,0.2)","rgba(243,100,16,0.2)","rgba(172,219,118,0.2)","rgba(160,252,214,0.2)","rgba(203,108,96,0.2)","rgba(223,17,108,0.2)","rgba(111,71,177,0.2)","rgba(251,34,54,0.2)","rgba(84,65,39,0.2)"]
				        }],
						labels:['.$stLaberPr.']
					
					},
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>';  ?>

		  </div>
		</div> 
		<br/>

	</div>


	<?php

	$st = '';

	$product_namex = '';

	$sqlx = "SELECT SUM(ot.quantity) AS `Total_Products_Order`,p.product_name,SUM(o.grand_total) AS `grand_total`
			FROM `orders` o,`order_item` ot,`product` p 
			WHERE o.order_id = ot.order_id 
			AND ot.product_id = p.product_id
			GROUP BY p.product_name
			ORDER BY p.product_name;";

	$resultx = $connect->query($sqlx);

	while ($fetchx = $resultx->fetch_assoc()){
			$Total_Products_Orderx = $fetchx['Total_Products_Order'];
			
			//$Years = $fetchx['Total_Products_Order'];

			$grand_totalx = $fetchx['grand_total'];	

			$Product_Fullx = $fetchx['product_name'];

			$product_namex = $product_namex . '"'. $Product_Fullx ." - ". $grand_totalx.'",';

			$st = $st.$grand_totalx . ",";


			//echo $Total_Products_Orderx.'<br>';
	}

	?>

	<div class="col-md-6">
		<div class="card">

		  <div class="cardContainer">
			<?php	
			echo '
			<canvas id="myChartOrderedProductsTwo" class="printable" width="800" height="450">
			</canvas>	
			<script>
			var ctx = "myChartOrderedProductsTwo";
				var myChart = new Chart(ctx, {
				    type: "bar",
				    data: {
				   	datasets: [{
					          label: "Linear",
					          data: ['.$st.'],
						      type: "line",
						  backgroundColor: "rgba(255, 159, 64, 0.1)",
						  borderWidth: 1
				        }, {
					          label: "Bar",
					          data: ['.$st.'],
						  borderColor: "rgba(255,99,132,0.8)",
						  backgroundColor: "rgba(255,99,132,0.8)"
				        }],
						labels:['.$product_namex.']
					
					},
					options: {
			
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero:true
								}
							}]
						}
						
					}
				});
			</script>';

			?>

		  </div>
		</div> 

	</div>

	<div class="col-md-12">
		<div class="card">
		  <div class="cardHeader" style="background-color:#245580;">
		    <h1><?php if($totalRevenue) {
		    	echo $totalRevenue;
		    	} else {
		    		echo '0';
		    		} ?></h1>
		  </div>

		  <div class="cardContainer">
		    <p> <i class="glyphicon glyphicon-usd"></i> Total Revenue</p>
		  </div>
		</div> 
	</div>
	
</div> <!--/row-->

<!-- fullCalendar 2.2.5 -->
<script src="assests/plugins/moment/moment.min.js"></script>
<script src="assests/plugins/fullcalendar/fullcalendar.min.js"></script>


<script type="text/javascript">
	$(function () {
			// top bar active
	$('#navDashboard').addClass('active');

      //Date for the calendar events (dummy data)
      var date = new Date();
      var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear();

      $('#calendar').fullCalendar({
        header: {
          left: '',
          center: 'title'
        },
        buttonText: {
          today: 'today',
          month: 'month'          
        }        
      });


    });
</script>

<?php require_once 'includes/footer.php'; ?>