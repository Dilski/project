  <?php 
include('includes/session.php');
include('includes/accessControl.php');
 
 $a = true; //allow admin
 $m = true; //allow manager
 $u = true; //allow user
 $n = false; //allow anyone
 
 access($a,$m,$u,$n,$session_group);
 ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Dave Casey & Co.</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Add custom CSS here -->
        <link href="css/stylesheet.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="css/profilePanel.css" rel="stylesheet">
        <link href="css/tablePanel.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    </head>

    <body>

	<?php
	include('includes/menu.php');
	?>
    
  <div class="container-fluid">
      <div class="row">
      
        <link href="css/noMoreTables.css" rel="stylesheet">
        <style>
        	@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
        	#no-more-tables-1 td:nth-of-type(1):before { content: "Task"; }
        	#no-more-tables-1 td:nth-of-type(2):before { content: "Start"; }
        	#no-more-tables-1 td:nth-of-type(3):before { content: "End"; }
        	#no-more-tables-1 td:nth-of-type(4):before { content: "View"; }
        	
        	#no-more-tables-2 td:nth-of-type(1):before { content: "Start"; }
        	#no-more-tables-2 td:nth-of-type(2):before { content: "End"; }
        	#no-more-tables-2 td:nth-of-type(3):before { content: "Status"; }
        	#no-more-tables-2 td:nth-of-type(4):before { content: "Cancel"; }
        	}
        </style>
        
        <div class="col-md-12">
        	<div class="panel panel-primary">
			  <div class="panel-body">
			    <h1> Welcome to the Dave Casey & Co Online Organiser, <?php echo $session_username;?> </h1> 
			  </div>
			</div>
        </div>
        
        
        
        
        
        
       <div id='no-more-tables-1'> 
        <div id='no-more-tables'>
        	<div class="col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Tasks</h3>
					</div>
					
					<div class="panel-body">
						<input type="text" class="form-control" id="filter" data-action="filter" placeholder="Filter That Dosen't Work" />
					</div>
					<table class="table table-hover" id="task-table">
						<thead>
							<tr>
								<th> Task </th>
								<th> Start </th>
								<th> End </th>
								<th> View </th>
							</tr>
						</thead>
					<tbody>
					<?php
						include('connection.php');
						
						$query = mysql_query("SELECT * FROM task WHERE userId=$session_id AND DATE(taskEnd) >= DATE(NOW()) ORDER BY taskStart ASC", $connection);
						
						while($rows = mysql_fetch_array($query))
						{
							$taskId = $rows['taskId'];
							$userId = $rows['userId'];
							$taskName = $rows['taskName'];
							$taskNameId = $rows['taskNameId'];
							$taskStart = date_create($rows['taskStart']);
							$taskEnd = date_create($rows['taskEnd']);
							$taskStartTime = substr($rows['taskStart'], 10, -3);
							$taskEndTime = substr($rows['taskEnd'], 10, -3);
							
							echo('<tr>');
								echo('<td>'.$taskName.'</td>'); 
								
								echo('<td>'); echo($taskStartTime.' - ');  echo(date_format($taskStart, 'l jS F Y')); echo('</td> ');
								
								echo('<td>'); echo($taskEndTime.' - ');  echo(date_format($taskEnd, 'l jS F Y')); echo('</td> ');
								
								echo('<td><p title="View"><a class="btn btn-primary btn-xs" href="viewTask.php?ID='.$taskId.'" ><span class="glyphicon glyphicon-eye-open"></span></a></p></td>');
								
							echo('</tr>');
						}
						$amtOfRows = mysql_num_rows($query);
						 if($amtOfRows < 1)
						 	echo('<tr><td>There are no tasks</td></tr>');
					?>
					</tbody>
				</table>
			</div>
		</div>
		</div>
	</div>
	
	<div id='no-more-tables-2'>
	<div id='no-more-tables'>
        	<div class="col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Holidays</h3>
						<div class="pull-right">
						<a style="color: #fff" href="newHolidayRequest.php"> <i  class="glyphicon glyphicon-plus"></i> </a>
						</div>
					</div>
					
					<div class="panel-body">
						<input type="text" class="form-control" id="filter" data-action="filter" placeholder="Filter That Dosen't Work" />
					</div>
					<table class="table table-hover" id="task-table">
						<thead>
							<tr>
								<th> Start </th>
								<th> End </th>
								<th> Status </th>
								<th> Cancel </th>
							</tr>
						</thead>
					<tbody>
					<?php
						include('connection.php');
						
						$query = mysql_query("SELECT * FROM holiday WHERE `userId`='$session_id' AND DATE(end) >= DATE(NOW()) ORDER BY start ASC", $connection);
						
						while($rows = mysql_fetch_array($query))
						{
							$holidayId = $rows['holidayId'];
							$start = date_create($rows['start']);
							$end = date_create($rows['end']);
							$status = $rows['status'];
							
							switch($status)
							{
							    
							    case 'p':
							        $statusFormatted = '<span class="label label-warning">Pending</span>';
							        break;
							    case 'a':
							        $statusFormatted = '<span class="label label-success">Accepted</span>';
							        break;
							    case 'd':
							    	$statusFormatted = '<span class="label label-danger">Denied</span>';
							    	break;
							        
							}
							
							echo('<tr>');
								
								echo('<td>'); echo(date_format($start, 'l jS F Y')); echo('</td> ');
								
								echo('<td>'); echo(date_format($end, 'l jS F Y')); echo('</td> ');
								
								echo('<td>'); echo($statusFormatted); echo('</td> ');
								
								echo('<td><p title="View"><a class="btn btn-danger btn-xs" data-href="cancelHoliday.php?ID='.$holidayId.'" data-toggle="modal" data-target="#confirm-delete" href="#"><span class="glyphicon glyphicon-remove"></span></a></p></td>');
								
							echo('</tr>');
						}
						$amtOfRows = mysql_num_rows($query);
						 if($amtOfRows < 1)
						 	echo('<tr><td>There are no holidays</td></tr>');
					?>
					</tbody>
				</table>
			</div>
		</div>
		</div>
</div>
        

    
    
    <?php
    include('includes/footer.php');
    ?>
    
    
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Cancel Holiday
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this holiday request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger danger">Delete</a>
            </div>
        </div>
    </div>
</div>

        <!-- Bootstrap core JavaScript -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/menu.js"></script>
        <script>
            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
            });
        </script>


    </body>

</html> 