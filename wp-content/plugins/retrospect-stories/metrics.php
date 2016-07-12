<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
<?
/***************************
DONT FORGET TO USE  get_user_meta($user->ID, 'metrics_exclude', true);
******************************/
global $wpdb;
if(empty($_POST[metrics_end]) && empty($_POST[metrics_start])){
	$_POST[metrics_end] = date("Y-m-d");
	$_POST[metrics_start] = date("Y-m-d",strtotime("-1 week"));	
}

$sql = "SELECT * FROM  ".$wpdb->prefix."posts  WHERE  post_author NOT IN (SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'metrics_exclude') AND (post_date BETWEEN '$_POST[metrics_start] 00:00:00' AND '$_POST[metrics_end] 23:59:00') and post_type = 'stories' AND (post_status = 'publish' OR post_status = 'draft')  GROUP BY post_author";

$result = $wpdb->get_results($sql,ARRAY_A);
$data[0] = sizeof($result);

$sql = "SELECT * FROM  ".$wpdb->prefix."users";
$result = $wpdb->get_results($sql,ARRAY_A);
foreach($result as $user){
	$sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE (post_date BETWEEN '$_POST[metrics_start] 00:00:00' AND '$_POST[metrics_end] 23:59:00') and post_type = 'stories' ANd (post_status = 'publish' OR post_status = 'draft') AND post_author = $user[ID]";
	$result = $wpdb->get_results($sql,ARRAY_A);
	//$num sizeof($result)."<br>";
			
}

$sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE post_author NOT IN (SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'metrics_exclude') AND (post_date BETWEEN '$_POST[metrics_start] 00:00:00' AND '$_POST[metrics_end] 23:59:00') and post_type = 'stories' AND (post_status = 'publish' OR post_status = 'draft')";
$result = $wpdb->get_results($sql,ARRAY_A);

$data[1] = sizeof($result);

$sql = "SELECT * FROM  ".$wpdb->prefix."comments  WHERE user_id NOT IN (SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'metrics_exclude') AND (comment_date BETWEEN '$_POST[metrics_start] 00:00:00' AND '$_POST[metrics_end] 23:59:00')";

$result = $wpdb->get_results($sql,ARRAY_A);
$data[4] = sizeof($result);

$sql = "SELECT * FROM  ".$wpdb->prefix."comments WHERE user_id NOT IN (SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'metrics_exclude') AND (comment_date BETWEEN '$_POST[metrics_start] 00:00:00' AND '$_POST[metrics_end] 23:59:00') GROUP BY comment_author";
$result = $wpdb->get_results($sql,ARRAY_A);
$data[2] = sizeof($result);


 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id WHERE  (meta_value BETWEEN '$_POST[metrics_start]' AND '$_POST[metrics_end]') AND meta_key LIKE 'mark_as_read%' AND post_status = 'publish'";

 $result = $wpdb->get_results($sql,ARRAY_A);
$data[3] = sizeof($result);
?>
<div class="desc" style="margin:5px 0px;padding:5px;color:yellow;background: #005672">Note: Remember, some users have been selected to be excluded.</div>
<h2>Retrospect Metrics</h2>
<form method="post" action="/wp-admin/options-general.php?page=retrospect-stories%2Fmetrics.php">
  
  
  

 
  <table class="form-table">
    <tbody>
     <tr><td>How many unique users have written stories? (draft or publish)</td><td><? echo $data[0]; ?></td></tr>
	<tr><td>How many stories were written?</td><td><? echo $data[1]; ?></td></tr>
    <tr><td>Avg Stories per User?</td><td><? echo ($data[0])?number_format($data[1]/$data[0],2):0; ?></td></tr>
    <tr><td>Total Comments?</td><td><? echo $data[4]; ?></td></tr>
    <tr><td>How many unique users have commented on stories?</td><td><? echo $data[2]; ?></td></tr>
   
    </tbody>
  </table>
   Show metrics between <input type="text" class="datepicker" name="metrics_start" value="<? echo $_POST[metrics_start]; ?>" /> and <input type="text" class="datepicker" name="metrics_end" value="<? echo $_POST[metrics_end]; ?>" />
  <?php submit_button("Filter Metrics"); ?>
  
  <h2>New/Deleted Users</h2>

  <table class="datatables wp-list-table widefat fixed striped users" id="example2">
  <thead>
    <?
  $start = $_POST[metrics_start];
  $days = [];
  while($start <= $_POST[metrics_end]){
	  $days[$start] = 0;
	?><th>'<? echo date("y",strtotime($start)) ; ?><br><? echo date("M",strtotime($start)) ; ?><br><? echo date("j",strtotime($start)) ; ?></th><?  
	$start = date("Y-m-d",strtotime($start. " +1 day"));
  }
  ?>
  </thead>
  <tbody>
  <?
  foreach($days as $day=>$value){
	  $sql = "SELECT * FROM  ".$wpdb->prefix."users WHERE user_registered LIKE '%$day%'";
		$result = $wpdb->get_results($sql,ARRAY_A);
	 $sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key = 'metric_user_deleted' AND meta_value = '$day'";
		$result2 = $wpdb->get_results($sql,ARRAY_A);

	  ?><td><? echo sizeof($result); ?>/<? echo sizeof($result2); ?></td><?
  }
  ?>
  </tbody>
  </table>
  
  <h2>Metrics</h2>
  
  <table class="datatables wp-list-table widefat fixed striped users" id="example1">
  <thead>
    <tr>
      <td class="manage-column"><span>User</span></td>
      <td class="manage-column"><span>Stories</span></td>
      <td class="manage-column"><span>Comments</span></td>
      <td class="manage-column"><span>Marked as Read (date metrics start on 4/22/2016)</span></td>
     
      
    
    </tr>
  </thead>
  <tbody id="the-list">
    <?
	  $sql = "SELECT * FROM  ".$wpdb->prefix."users WHERE ID NOT IN (SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'metrics_exclude') AND ID > 0";
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $row){
			$first = get_user_meta($row[ID], 'first_name',true);
			$last = get_user_meta($row[ID], 'last_name',true);
		?>
    <tr valign="top">
      <td><span title="<? echo $first." ".$last; ?>"><? echo $row[user_login]; ?></span>
        <div class="row-actions">
        <span class="author">
        <a target="_blank" href="/author/<? echo $row[user_nicename]; ?>">Author Page</a>
       </span> | 
       <span class="member">
        <a target="_blank" href="/members/<? echo $row[user_nicename]; ?>">Member Profile</a>
        
        </span> | <span class="edit">
        <a target="_blank" href="/wp-admin/user-edit.php?user_id=<? echo $row[ID]; ?>">Edit</a>
        
        </span></div></td>
    <?
	$sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE (post_date BETWEEN '$_POST[metrics_start] 00:00:00' AND '$_POST[metrics_end] 23:59:00') and post_type = 'stories' ANd (post_status = 'publish' OR post_status = 'draft') AND post_author = $row[ID]";
$result = $wpdb->get_results($sql,ARRAY_A);

?>
      <td><? echo  sizeof($result); ?></td>
      <?
	  $sql = "SELECT * FROM  ".$wpdb->prefix."comments WHERE (comment_date BETWEEN '$_POST[metrics_start] 00:00:00' AND '$_POST[metrics_end] 23:59:00')  AND user_id = $row[ID]";
$result = $wpdb->get_results($sql,ARRAY_A);
?>
       <td><? echo  sizeof($result); ?></td>
        <?
	  $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id WHERE  (meta_value BETWEEN '$_POST[metrics_start]' AND '$_POST[metrics_end]') AND meta_key = 'mark_as_read".$row[ID]."' AND post_status = 'publish'";
	 
$result = $wpdb->get_results($sql,ARRAY_A);
?>
       <td><? echo  sizeof($result); ?></td>
      
   
    </tr>
    <?	
		}
		?>
  </tbody>
  <tfoot>
  <td>Totals</td>
  <td></td>
   <td></td>
    <td></td>
  </tfoot>
</table>
  
</form>
<script>
jQuery(document).ready(function(){
	jQuery(".datepicker").datepicker({dateFormat:"yy-mm-dd"});
	jQuery('.datatables').DataTable({
		 "lengthMenu": [[ -1], [ "All"]],
		 "footerCallback": function(row, data, start, end, display ){
			var api = this.api(), data;
			var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };	
			  var t1 = api.column( 1 ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 
				
				var t2 = api.column( 2 ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 
				
				var t3 = api.column( 3 ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 
				
				
				 t1 = api.column( 1, { page: 'current'} ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
				 t2 = api.column( 2, { page: 'current'} ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
				 t3 = api.column( 3, { page: 'current'} ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
				jQuery( api.column( 1 ).footer() ).html(t1);
				jQuery( api.column( 2 ).footer() ).html(t2);
				jQuery( api.column( 3 ).footer() ).html(t3);
			 
		 }
	});
	
	
	

	
});
function doSums(t){
		
		var sums = [0,0,0,0];
		t.find("tbody > tr:visible").each(function(){
			
				sums[1] += parseInt(jQuery(this).find("td").eq(1).text());
				sums[2] += parseInt(jQuery(this).find("td").eq(2).text());
				sums[3] += parseInt(jQuery(this).find("td").eq(3).text());
				
			
		});
		for(var a = 1;a<sums.length;a++){
			t.find("tfoot > tr > td:eq("+a+")").html(sums[a]);
		}
		console.log( sums);
	}
</script> 
