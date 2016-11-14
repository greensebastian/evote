<?php

if(!$evote->ongoingSession()){
	echo "<p><h3>Det finns inget pågående val för tillfället.</h3></p><br>";
}else{
	$ongoing = $evote->ongoingRound();

	if(!$ongoing){
		echo "<p><h3>Det finns inget att rösta på för tillfället. Ta en kaka.</h3></p><br>";
	}else{
            $res = $evote->getOptions();
            if($res->num_rows > 0){
?>
	    	<h3 class="small-centered" style="max-width: 165px;">Röstning pågår</h3>
			<hr>
			<div class="well small-centered"style="max-width: 400px;">
				<?php
				$max = $evote->getMaxAlternatives();
				echo "<div name=\"maxalt_header\" >";
					echo "<h4>Du får rösta på <b>".$max."</b> av alternativen</h4>";
				echo "</div>";
				?>
	    	    <form action="actions/votingpagehandler.php" method="POST" autocomplete="off" onsubmit="return confirmChoice()">
	    	        <?php
                        $head = "";
						$type = "checkbox";
						$id = 0;
						if($max <= 1){
							$type = "radio";
							$id = 1;
						}
						echo "<div class=\"panel panel-default\">";
	    	        	echo "<table class=\"table table\" id=\"contentTable\">";
                        while($row = $res->fetch_assoc()){
                                if($head != $row["e_name"]){
	    	        	        echo "<tr class=\"rowheader\";><th colspan=\"2\">".$row["e_name"]."</th></tr>";
                                $head = $row["e_name"];
                                                }
	    	        			echo "<tr>
									<td class=\"col-md-1 col-xs-1\">
									<input type=$type class=\"big\" name=\"person[]\" id=$id value=".$row["id"]." onclick=\"maxCheck()\"></td>
	    	        				<td class=\"col-md-11 col-xs-11\">".$row["name"]." </td>
									</tr>\n";
	    	        		}
	    	        	echo "</table>";
						echo "</div>";
	    	        ?>
					<script>
					function confirmChoice(){
						var max = <?php echo $evote->getMaxAlternatives() ?>;

    					var checkboxes = document.getElementsByName("person[]");
						var countChecked = 0;
						var blankChecked = false;
						for(var i = 0; i<checkboxes.length; i++){
							if(checkboxes[i].checked == true){
								if (i == checkboxes.length-1){
									blankChecked = true;
								}
								countChecked++;
							}
						}

						// Check if vote is both blank and something else
						if (blankChecked && countChecked > 1){
							if (confirm('Om blank är vald räknas inget av dina andra val, vill du verkligen göra det?')){
								for(var i = 0; i<checkboxes.length-1; i++){
									checkboxes[i].checked = false;
								}
								return true;
							}
							else {
								return false;
							}
						}
						// Check if not all votes are used
						else if (countChecked < max){
							return confirm('Denna röst innebär att du vill vakantsätta ' + (max-countChecked) + ' poster. Vill du verkligen göra det?');
						}
						else {
							return true;
						}
					}
					function maxCheck(){
    					var max = <?php echo $evote->getMaxAlternatives() ?>;

    					var checkboxes = document.getElementsByName("person[]");
						var countChecked = 0;
						for(var i = 0; i<checkboxes.length; i++){
							if(checkboxes[i].checked == true){
								countChecked++;
							}
						}
						for(var i = 0; i<checkboxes.length; i++){
							checkboxes[i].disabled = false;
							if(checkboxes[i].checked == false && countChecked >= max && checkboxes[i].id != 1){
								checkboxes[i].disabled = true;
							}
						}


					}

					</script>

					</script>
	    	        <div class="form-group">
	    	            <label >Personlig valkod:</label>
	    	            <input type="password" class="form-control" name="code1">
	    	        </div>
	    	        <div class="form-group">
	    	            <label >Tillfällig valkod:</label>
	    	            <input type="text" class="form-control" name="code2">
	    	        </div>
                            <br>
                            <div class="span7 text-center">
	    	                <button type="submit" class="btn-lg btn-primary" value="vote" name="button" >Rösta!</button>
                            </div>
	    	    </form>
		</div>
<?php
            }
		}
	}
?>
