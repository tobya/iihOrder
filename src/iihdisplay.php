<?php
/**

 */

date_default_timezone_set('Europe/London');

//Very large response so zip return
ob_start('ob_gzhandler');
include('config.php');




if( isset($_GET['CompanyTag']) ){
$CompanyTag =$_GET['CompanyTag'];
} else
{
  die( "You must choose Company please go <a href='index.php'>here </a>");
}

//$Items = GetIIHItems();


$Account = GetAccountDetails($CompanyTag);
//$Account = json_decode($AccountJSON,true);

if (isset($_GET['Order'])){
  $CurrentOrder['KEY'] = $_GET['Order'];
  $CurrentOrder['DATA']  = GetSetting($_GET['Order']);
  $CurrentOrder['SaveOrderCaption'] = 'Copy Order';
  $CurrentOrder['Type'] = 'old';
} else {

  $CurrentOrder['KEY'] = 'IIH_CURRENTORDER_' . $Account['CustomerID'];
  $CurrentOrder['DATA']  = json_encode(GetSetting( $CurrentOrder['KEY']) );
  $CurrentOrder['SaveOrderCaption'] = 'Save Order';
  $CurrentOrder['Type'] = 'current';

}


$CurrentOrderData =  $CurrentOrder['DATA'];


if ($CurrentOrderData == false)
{
  $CurrentOrderData = '{}';
} 

/*Retrieve items from HTML Scrap file.*/
function GetIIHItems(){

  $iihDataTextFile = 'iihitems.data.txt';
  $html = '';
  if (file_exists($iihDataTextFile)){
    $html = file_get_contents($iihDataTextFile);
   } 
   return $html;
  
}

function GetAccountDetails($CompanyTag)
{
   $Setting = GetSetting('IIH_ACCOUNTDETAILS_' .  $CompanyTag );
   
	if (is_array($Setting))
	{
    return $Setting;
  }
    else
  {
    
		return  array('AccountID'=>'X','CustomerID'=> 'X');
	}
}
		
?>

<html>
<head>
    <title>IIH Order</title>
    <link rel="stylesheet" href="../vendor/mleibman/SlickGrid/slick.grid.css" type="text/css"/>
    <link rel="stylesheet" href="../vendor/mleibman/SlickGrid/controls/slick.pager.css" type="text/css"/>
    <link rel="stylesheet" href="../vendor/mleibman/SlickGrid/css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
      <link rel="stylesheet" href="../vendor/mleibman/SlickGrid/controls/slick.columnpicker.css" type="text/css"/>
  
  <link rel="stylesheet" href="order.css" type="text/css"/>
    <style>
        .cell-title {
            font-weight: bold;
        }

        .cell-effort-driven {
            text-align: center;
        }

        .cell-selection {
            border-right-color: silver;
            border-right-style: solid;
            background: #f5f5f5;
            color: gray;
            text-align: right;
            font-size: 10px;
        }

        .slick-row.selected .cell-selection {
            background-color: transparent; /* show default selected row background */
        }

        #EmailOutput {
          position: relative;
          left: 0;
          top: 10;
        }
    
      #inlineBtnPanel {
        width: 900px;
          background-color: transparent;
      }

    </style>
</head>
<body>
<div id="inlineBtnPanel" style="background:#dddddd;padding:3px;color:black;">

    IIH Details: Account ID: <input type=text id="AccountID" >
  Customer Number: <input type=text id="CustomerID">
  Signature Name: <input type=text id="CustomerSignature">
  <span id="alertbox">
    Alert
  </span>
  <div id="inlineBtnPanel" style="background:#dddddd;padding:3px;color:black;">


        <button id="btnShowOrder">Show Order</button>
    |    <button id="btnShowAll">Show All</button>
    |    <button id="btnSaveOrder">Save Order</button>
    |    <button id="btnNewOrder">New Blank Order</button>
    |    <button id="btnGenerateEmail">Generate Email</button>
  </div>
</div>
<div style="position:relative">
    <div style="width:900px;">
        <div class="grid-header" style="width:100%">
            <label>SlickGrid</label>
      <span style="float:right" class="ui-icon ui-icon-search" title="Toggle search panel"
            onclick="toggleSearchRow()"></span>
        </div>
        <div id="myGrid" style="width:100%;height:500px;"></div>
        <div id="pager" style="width:100%;height:20px;"></div>
    </div>


    <div class="options-panel">

    <h3> Overview</h3>

    <p>First time use, make sure you enter your Account ID and Customer Number at the Top and a Signature for the email</p>

    <p>Type words into the search box above grid of items to find items. eg type 'hazel' to get Prepack Hazelnuts, Roasted Hazelnuts, Hazelnut butter etc. If viewing order and nothing shows then press enter</p>
    <BR>Hitting 'esc' will clear the search box and show all items, ready for a new search.

    <BR><strong> To Add item:  </strong>
    <ul>
    <li>
    Type the quantity of the item you wish to have on your order in the quantity column beside the item.  After entering quantity press Tab or Enter to save.
    </li></ul>

    <B>Buttons</B>:
    <ul>
    <li><B>Show Order</B>: Show only items that are in your order</li>
    <li><B>Show all</B>: Show all items available</li>
    <li><B>Save Order</B>: Order is saved automatically, but you can click to save if you wish</li>
    <li><B>New Blank Order</B>: Current order stays until you click this button.  </li>
    <li><B>Generate Email</B>: Generate Email into the Text Box below the list, that can be copied and pasted into your favourite email application. </li>
    </ul>
    <BR>
    <Strong>Load Items</Strong>
    <ul><li>To initially load items you must create a data.txt file and load items before the software will run.  Please see <a href='loadhelp.php'> help </a> for more details 
    </li></ul>
    </div>

</div>
    <div ><P><h3>Output: </h3>(To Copy: Click in Textfield, type ctrl-a to select all, then ctrl-c to copy to clipboard. <BR> <textarea id="EmailOutput" cols=170 rows="22"></textarea></div>

<div id="inlineFilterPanel" style="display:none;background:#dddddd;padding:3px;color:black;">
    Search for items: <input type="text" id="txtSearch2">

</div>


<div id="inlineBtnPanel" style="display:none;background:#dddddd;padding:3px;color:black;">

    <button id="btnGenerateEmail">Generate Email</button>
 |    <button id="btnShowOrder">Show Order</button>
</div>




<script src="../vendor/mleibman/SlickGrid/lib/jquery-1.7.min.js"></script>
<script src="../vendor/mleibman/SlickGrid/lib/jquery-ui-1.8.16.custom.min.js"></script>
<script src="../vendor/mleibman/SlickGrid/lib/jquery.event.drag-2.2.js"></script>
<script src="../vendor/mleibman/SlickGrid/slick.core.js"></script>
<script src="../vendor/mleibman/SlickGrid/slick.formatters.js"></script>
<script src="../vendor/mleibman/SlickGrid/slick.editors.js"></script>
<script src="../vendor/mleibman/SlickGrid/plugins/slick.rowselectionmodel.js"></script>
<script src="../vendor/mleibman/SlickGrid/slick.grid.js"></script>
<script src="../vendor/mleibman/SlickGrid/slick.dataview.js"></script>
<script src="../vendor/mleibman/SlickGrid/controls/slick.pager.js"></script>
<script src="../vendor/mleibman/SlickGrid/controls/slick.columnpicker.js"></script>

<script>


 Date.prototype.yyyymmdd = function() {
   var yyyy = this.getFullYear().toString();
   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
   var dd  = this.getDate().toString();
   return yyyy + (mm[1]?mm:"0"+mm[0]) + (dd[1]?dd:"0"+dd[0]); // padding
  };




var dataView;
var grid;
var data = [];
var columns = [

    {id: "AutoItemID", name: "AutoItemID", field: "id", behavior: "select", cssClass: "cell-selection", width: 40, cannotTriggerInsert: true, resizable: false, selectable: false },
    {id: "Quantity", name: "Quantity", field: "Quantity", width: 60, minWidth: 30, cssClass: "cell-title", sortable: true, editable:true , editor: Slick.Editors.Integer},
    {id: "Code", name: "Code", field: "Code", width: 60, minWidth: 30, cssClass: "cell-title", sortable: true},
    {id: "Description", name: "Description", field: "Description", width:400, minWidth: 30, cssClass: "cell-title", sortable: true},
    {id: "Measure", name:"Measure", field: "Measure", width: 60, minWidth:30, cssClass: "cell-title", sortable : true },
    {id: "Wholesale", name:"Wholesale", field: "Wholesale", width: 60, minWidth:30, cssClass: "cell-title", sortable : true },
    {id: "RRP", name:"RRP", field: "RRP", width: 30, minWidth:60, cssClass: "cell-title", sortable : true },
    {id: "VAT", name:"VAT", field: "VAT", width: 30, minWidth:60, cssClass: "cell-title", sortable : true },
    {id: "Section", name: "Section", field: "Section", width: 160, minWidth: 30, cssClass: "cell-title", sortable: true},
];

var options = {
    editable: true,
    enableAddRow: true,
    enableCellNavigation: true,
    asyncEditorLoading: true,
    forceFitColumns: false,
    topPanelHeight: 25
};

var sortcol = "Description";
var sortdir = 1;
var percentCompleteThreshold = 0;
var searchString = "";
var showOrder = false;
var IIH = {};

function requiredFieldValidator(value) {
    if (value == null || value == undefined || !value.length) {
        return {valid: false, msg: "This is a required field"};
    }
    else {
        return {valid: true, msg: null};
    }
}


/*Custom Filter for Grid
Called for every single item.  
Function returns true to include, false to hide.*/
function gridSearchFilter(item, args) {

    //If we are only showing the current order then only show item if quantity > 1
    if (args.showOrder){
      if (parseInt(item["Quantity"],10) < 1){
        return false;
      }
    }


    //Search values must have values and must be lowercase for comparison.
    //Search code and section also
    var lowercaseitem = item["Description"] + item['Section'] + item['Code'];
       
    if (lowercaseitem != undefined){
           lowercaseitem = lowercaseitem.toLowerCase();
    }
    else{
           return false;
    }

    //Get search string as lowercase also
    var s = args.searchString;

    if (s != undefined){
       s = s.toLowerCase();
    }
    else {
        return false;
    }


    //Split String so we can search for each word seperately
    var SearchItems = args.searchString.split(" ");
   var i;
   
     for (i = 0;i< SearchItems.length;i++){
        
        s = SearchItems[i].toLowerCase();
      	
        //Compare Search String
        if (args.searchString != "" && lowercaseitem.indexOf(s) == -1) {
          return false;
        }
    }
    
    //If all search words have been matched then send back true.

    return true;
}



function comparer(a, b) {
    var x = a[sortcol], y = b[sortcol];
    return (x == y ? 0 : (x > y ? 1 : -1));
}

function toggleSearchRow() {
    if ($(grid.getTopPanel()).is(":visible")) {
        grid.setTopPanelVisibility(false);
    } else {
        grid.setTopPanelVisibility(true);
    }
}

var iih ={};

iih.CountOfItems = function(dataitems){
                          var count = 0;
                          $.each(dataitems, function(key,item){
                              
                              if (parseInt(item["Quantity"],10) > 0){
                                count++;
                              }
                            
                            }
                          );
                          return count;
                        };




$(".grid-header .ui-icon")
    .addClass("ui-state-default ui-corner-all")
    .mouseover(function (e) {
        $(e.target).addClass("ui-state-hover")
    })
    .mouseout(function (e) {
        $(e.target).removeClass("ui-state-hover")
    });


  var AccountSelector = $('#AccountID,#CustomerID,#CustomerSignature');

  AccountSelector.change(function (data){

    var Items = {'AccountID': $('#AccountID').val(),'CustomerID': $('#CustomerID').val(), 'CompanyTag' : '<?php echo $CompanyTag; ?>', 'CustomerSignature' : $('#CustomerSignature').val() };
   // console.log(Items);
   $('#alertbox').fadeOut().html('Updating Account info').fadeIn().delay(2000).fadeOut('slow');
    $.post('postdata.php',{'action':'SaveAccount','AccountInfo':Items},function(data){
       // alert(data);
      $('#alertbox').fadeOut().html('Account info updated').fadeIn().delay(2000).fadeOut('slow');
        $('#EmailOutput').val( data );
    })
    IIH.AccountInfo = Items;
  })

     IIH.AccountInfo = <?php echo json_encode($Account); ?>

 $('#AccountID').val(IIH.AccountInfo.AccountID);
 $('#CustomerID').val(IIH.AccountInfo.CustomerID);
 $('#CustomerSignature').val(IIH.AccountInfo.CustomerSignature);



$(function () {
    // prepare the data
    var data = [];
    var d =  {};



    <?php
      echo GetIIHItems();
    ?>


    dataView = new Slick.Data.DataView({ inlineFilters: true });
    grid = new Slick.Grid("#myGrid", dataView, columns, options);
    grid.setSelectionModel(new Slick.RowSelectionModel());

    var pager = new Slick.Controls.Pager(dataView, grid, $("#pager"));
    var columnpicker = new Slick.Controls.ColumnPicker(columns, grid, options);
	
    IIH.orderType = '<?php echo $CurrentOrder['Type']; ?>';
    // move the filter panel defined in a hidden div into grid top panel
    $("#inlineFilterPanel")
        .appendTo(grid.getTopPanel())
        .show();



    $("#btnGenerateEmail").click(function(e){
        var i ;
        var DupList = {};
        var Items = [];
        for (i = 0; i < data.length ; i++)
        {

            if (data[i].Quantity > 0){
              //Prevent duplicate from being put in email.
              if (DupList[data[i].Code] == undefined){
                Item = {'Code': data[i].Code, 'Quantity' : data[i].Quantity, 'Description': data[i].Description};
                Items.push(Item);
                DupList[data[i].Code] = 1; 
              }
            }            

        }
        $.post('postdata.php',{'action':'GenerateMail','items':Items , 'AccountInfo': IIH.AccountInfo},function(data){
           // alert(data);
            $('#EmailOutput').val( data );
        })
    })
    
    IIH.SaveOrder = function(e){
        var i ;
        var List = '';
        var Items = [];
       


        for (i = 0; i < data.length ; i++)
        {
           // console.log('Check');
            if (data[i].Quantity > 0){

            Item = {'Code': data[i].Code, 'Quantity' : data[i].Quantity, 'Description': data[i].Description};
            Items.push(Item);
             
            }

        }
        //console.log(Items);
        $.ajax({type: 'POST',
              url : 'postdata.php',
              data: {'action':'SaveOrder','items':Items, 'AccountInfo': IIH.AccountInfo
              /*,'debug': true*/},
              async : true,
              success: function(data){
           // alert(data);
            var now = new Date();
            $('#alertbox').fadeOut().html('Order Last Saved: ' + now.yyyymmdd()).fadeIn().delay(2000).fadeOut('slow');
          $('#EmailOutput').val( data ); 
        }})
    }

    IIH.NewOrder = function(e){
      var i ;
      var List = '';
      var Items = [];
      for (i = 0; i < data.length ; i++)
      {
        if (data[i].Quantity > 0){

          Item = {'Code': data[i].Code, 'Quantity' : data[i].Quantity, 'Description': data[i].Description};
          Items.push(Item);
        }

      }
      $.post('postdata.php',{ 'action':'NewOrder','items':Items ,
                              'AccountInfo': IIH.AccountInfo},function(data){
       // alert('New Order Created:' + data);
        $('#alertbox').fadeOut().html('New Order Created: ' + data ).fadeIn().delay(2000).fadeOut('slow');
      })
      //Clear data for new order.
      for (i = 0; i < data.length ; i++)
      {
        data[i].Quantity = 0;
      }

      refreshGrid();

    }
    
    $("#btnSaveOrder").click(IIH.SaveOrder);
    
    $("#btnNewOrder").click(IIH.NewOrder);

    $("#btnShowOrder").click(function(e){
        Slick.GlobalEditorLock.cancelCurrentEdit();
        searchString = '';
        showOrder = true;
        updateFilter();

    })



  $("#btnShowAll").click(function(e){
    Slick.GlobalEditorLock.cancelCurrentEdit();
    searchString = '';

    updateFilter();

  })

    function setSaveCaption(){
      var Caption = '<?php echo $CurrentOrder['SaveOrderCaption']; ?>';
      $('#btnSaveOrder').html(Caption);

    }
    
    function     setOrder(dv){
        var i,c ;
        var CurrentItems = <?php echo $CurrentOrderData; ?>;
        if (CurrentItems == null){return;};
        var Items = {};
        //console.log(CurrentItems);
        for (c = 0 ; c < CurrentItems.length; c++)
        {
        	
        	//alert(CurrentItems[c]);
        	Items[CurrentItems[c]['Code']] =  CurrentItems[c]['Quantity'];
        }
       // console.log('items');
       // console.log(Items);
       // console.log(data.length);
        for (i = 0; i < data.length ; i++)
        {
        
        	//console.log(data[i].Code);
            if (Items.hasOwnProperty(data[i].Code))	{
            
		          //console.log(Items[data[i].Code]);
		data[i].Quantity = Items[data[i].Code];
            }

        }    	
    }


    grid.onCellChange.subscribe(function (e, args) {
        dataView.updateItem(args.item.id, args.item);
        	IIH.SaveOrder();
    });



   

    grid.onKeyDown.subscribe(function (e) {

      //on F2 choose search
      if (e.which == 113) {
        $('#txtSearch2').focus();
        e.preventDefault();
        return true;
      }


        // select all rows on ctrl-a
        if (e.which != 65 || !e.ctrlKey) {
            return false;
        }



        var rows = [];
        for (var i = 0; i < dataView.getLength(); i++) {
            rows.push(i);
        }

        grid.setSelectedRows(rows);
        e.preventDefault();
    });

    grid.onSort.subscribe(function (e, args) {
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;

        if ($.browser.msie && $.browser.version <= 8) {
            // using temporary Object.prototype.toString override
            // more limited and does lexicographic sort only by default, but can be much faster

            var percentCompleteValueFn = function () {
                var val = this["percentComplete"];
                if (val < 10) {
                    return "00" + val;
                } else if (val < 100) {
                    return "0" + val;
                } else {
                    return val;
                }
            };

            // use numeric sort of % and lexicographic for everything else
            dataView.fastSort((sortcol == "percentComplete") ? percentCompleteValueFn : sortcol, args.sortAsc);
        } else {
            // using native sort with comparer
            // preferred method but can be very slow in IE with huge datasets
            dataView.sort(comparer, args.sortAsc);
        }
    });

    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function (e, args) {
        grid.updateRowCount();
        grid.render();
    });

    dataView.onRowsChanged.subscribe(function (e, args) {
        grid.invalidateRows(args.rows);
        grid.render();
    });

    dataView.onPagingInfoChanged.subscribe(function (e, pagingInfo) {
        var isLastPage = pagingInfo.pageNum == pagingInfo.totalPages - 1;
        var enableAddRow = isLastPage || pagingInfo.pageSize == 0;
        var options = grid.getOptions();

        if (options.enableAddRow != enableAddRow) {
            grid.setOptions({enableAddRow: enableAddRow});
        }
    });




    // wire up the search textbox to apply the filter to the model
    $("#txtSearch,#txtSearch2").keyup(function (e) {
        Slick.GlobalEditorLock.cancelCurrentEdit();

        // clear on Esc
        if (e.which == 27) {
            this.value = "";
        }

        searchString = this.value;
        updateFilter();
    });




    function updateFilter() {
        dataView.setFilterArgs({

            percentCompleteThreshold: percentCompleteThreshold,
            searchString: searchString,
            showOrder : showOrder
        });
        dataView.refresh();
        window.setTimeout(resetShowOrder, 1000);
    }

    function resetShowOrder(){
        showOrder = false;
        //$('#btnShowOrder').caption('Show Order');
    }

    function refreshGrid(){
      searchString = 'yyy';
      updateFilter();

      searchString = '';
      updateFilter();

    }

    $("#btnSelectRows").click(function () {
        if (!Slick.GlobalEditorLock.commitCurrentEdit()) {
            return;
        }

        var rows = [];
        for (var i = 0; i < 10 && i < dataView.getLength(); i++) {
            rows.push(i);
        }

        grid.setSelectedRows(rows);
    });

    setOrder(dataView);	

    // initialize the model after all the events have been hooked up
    dataView.beginUpdate();
    dataView.setItems(data);

    //show order if available
    showOrder = true;
    if (iih.CountOfItems(data) == 0){
      showOrder = false;
    }
    dataView.setFilterArgs({
        percentCompleteThreshold: percentCompleteThreshold,
        searchString: searchString,
        showOrder: showOrder
    });
    dataView.setFilter(gridSearchFilter);
    dataView.endUpdate();

   //Show Search Panel on Load.
    toggleSearchRow();




  //Set Save / Copy Caption
  setSaveCaption();

    ItemCount = iih.CountOfItems(data);
  $('#alertbox').html('Item Selected:' + ItemCount);

		//Save every 2 minutes if changed to prevent dataloss
		window.setInterval(function() {
						
						IIH.SaveOrder();
						    	
		}, 180000);


    // if you don't want the items that are not visible (due to being filtered out
    // or being on a different page) to stay selected, pass 'false' to the second arg
    dataView.syncGridSelection(grid, true);

    $("#gridContainer").resizable();
})
</script>
</body>
</html>
<?php ob_end_flush(); ?>