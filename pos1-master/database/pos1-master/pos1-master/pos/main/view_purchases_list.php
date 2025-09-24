<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<table class="table table-bordered" id="resultTable" data-responsive="table" style="text-align: left;">
	<thead>
		<tr>
			<th width="25%"> Name </th>
			<th width="3%"> Qty </th>
			<th width="8%"> Cost </th>
		</tr>
	</thead>
	<tbody>
		<?php
		include('../connect.php');

		// Check if invoice parameter exists
		if (!isset($_GET['iv']) || empty($_GET['iv'])) {
			echo '<tr><td colspan="3" style="color: red; text-align: center;">Error: No invoice number provided. Please access this page through the purchases list.</td></tr>';
		} else {
			$id = $_GET['iv']; // Get the invoice parameter

			$result = $db->prepare("SELECT * FROM purchases_item WHERE invoice= :userid");
			$result->bindParam(':userid', $id);
			$result->execute();

			$found_items = false;
			for ($i = 0; $row = $result->fetch(); $i++) {
				$found_items = true;
		?>
				<tr class="record">
					<td><?php
									$rrrrrrr = $row['name'];
									$resultss = $db->prepare("SELECT * FROM products WHERE product_code= :asas");
									$resultss->bindParam(':asas', $rrrrrrr);
									$resultss->execute();
									$product_found = false;
									for ($j = 0; $rowss = $resultss->fetch(); $j++) {
										echo $rowss['product_name'];
										$product_found = true;
									}
									if (!$product_found) {
										echo "Product not found: " . htmlspecialchars($rrrrrrr);
									}
									?></td>
					<td><?php echo $row['qty']; ?></td>
					<td>
						<?php
						$dfdf = $row['cost'];
						echo formatMoney($dfdf, true);
						?>
					</td>
				</tr>
		<?php
			}

			if (!$found_items) {
				echo '<tr><td colspan="3" style="color: orange; text-align: center;">No items found for invoice: ' . htmlspecialchars($id) . '</td></tr>';
			}
		}
		?>
		<tr>
			<td colspan="2"><strong style="font-size: 12px; color: #222222;">Total:</strong></td>
			<td><strong style="font-size: 12px; color: #222222;">
					<?php
					function formatMoney($number, $fractional = false)
					{
						if ($fractional) {
							$number = sprintf('%.2f', $number);
						}
						while (true) {
							$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
							if ($replaced != $number) {
								$number = $replaced;
							} else {
								break;
							}
						}
						return $number;
					}

					// Check if invoice parameter exists for total calculation
					if (!isset($_GET['iv']) || empty($_GET['iv'])) {
						echo "0.00";
					} else {
						$sdsd = $_GET['iv'];
						$resultas = $db->prepare("SELECT sum(cost) FROM purchases_item WHERE invoice= :a");
						$resultas->bindParam(':a', $sdsd);
						$resultas->execute();
						$total_found = false;
						for ($i = 0; $rowas = $resultas->fetch(); $i++) {
							$fgfg = $rowas['sum(cost)'];
							if ($fgfg !== null) {
								echo formatMoney($fgfg, true);
								$total_found = true;
							}
						}
						if (!$total_found || $fgfg === null) {
							echo "0.00";
						}
					}
					?>
				</strong></td>
		</tr>

	</tbody>
</table>