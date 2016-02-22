<!DOCTYPE html>
<html>
<head>
    <title>IIH Order Load Data Help</title>
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


<h2>How to create and load a 'data.txt' file</h2>

<P>Since the data used in this application belongs to Independent Irish Healthfoods and contains sensitive company pricing, you must be a customer to get the data.

<P>Currently you must copy and paste the data from the pdf that they send you by email.  In the future I am hoping that it can simply be provided as a csv or json file.

<h3>Instructions to copy data to a data.txt file</h3>
<P>
<ul>
<li>Find the most recent version  of the pricelist sent from IIH. Most recently it is distributed via dropbox and is name 'IIHFPricelistALLFILES_JanFeb16.pdf'</li>
<li>Open up the pricelist pdf in Adobe PDF Viewer (not just in Chrome or other browser)</li>
<li>Select all the text by pressing ctrl-a (this may take some time)</li>
<li>Copy all the text by pressing ctrl-c (this will take even more time)</li>
<li>When the progress bar finishes showing the data has been copied to the clipboard, open you your favorite TEXT editor (notepad will do but is a bit slow) and paste in all the data. </li>
<li>Save the file as data.txt in the same src directory that you have installed the php application.</li>
<li>Run <a href="Load.php" target="other">Load.php</a> to load items from data.txt into app.</li>
<li>In most recent versions all codes are in the same file, so you will also load Delisted items, but they are clearly labeled Delisted in the Section Column. </li>
<li>Load <a href="iihdisplay.php" >iihdisplay.php</a> to view and use the app.</li>

</ul>

</body>
</html>