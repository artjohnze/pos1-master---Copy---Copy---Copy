<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="savesupplier.php" method="post">
    <center>
        <h4><i class="icon-plus-sign icon-large"></i> Add Supplier</h4>
    </center>
    <hr>
    <div id="ac">
        <span>Supplier Name : </span><input type="text" style="width:265px; height:30px;" name="name" required /><br>
        <span>Address : </span><input type="text" style="width:265px; height:30px;" name="address" /><br>
        <span>Contact No. : </span><input type="text" style="width:265px; height:30px;" name="cperson" /><br>
        <span>Note : </span><textarea style="width:265px; height:80px;" name="note" /></textarea><br>
        <div style="float:right; margin-right:10px;">
            <button class="btn btn-success btn-large" style="width:130px; margin-right:5px;"><i class="icon icon-save icon-large"></i> Save</button>
            <button type="button" class="btn btn-danger btn-large" style="width:130px;" onclick="window.location.href='supplier.php';"><i class="icon icon-remove icon-large"></i> Cancel</button>
        </div>
    </div>
</form>