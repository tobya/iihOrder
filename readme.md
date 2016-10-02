IIH Order
--------

Open source PHP / Javascript webapp using [SlickGrid](https://github.com/mleibman/SlickGrid) to display 10,000+ items and search really fast.

Allows simple creation of IIH - [Independent Irish Health Foods](http://www.iihealthfoods.com/) orders by modifying quantity field in grid.  Then a simple email text can be generated with the order.

Installation
------------

Requires Server running PHP 5.3 >

Data
-----

The data required for this to work will be provided to you on PDF when you sign up for an account with IIH.

Composer
--------

Can be installed using Composer 

Manual
------

Download Source then download [SlickGrid](https://github.com/mleibman/SlickGrid) and install into

    vendor/mleibman/SlickGrid
   

Get Involved
------------

Please submit pull requests or Issues.


How to create and load a 'data.txt' file 
----------------------

Since the data used in this application belongs to Independent Irish Healthfoods and contains sensitive company pricing, you must be a customer to get the data.

Currently you must copy and paste the data from the pdf that they send you by email.  In the future I am hoping that it can simply be provided as a csv or json file.

Instructions to copy data to a data.txt file 
------------------------

 - Download the most recent version  of the pricelist sent from IIH. Most recently it is distributed via dropbox 
 - Open up the pricelist pdf in Adobe PDF Viewer (not just in Chrome or other browser) 
 - Select all the text by pressing ctrl-a (this may take some time) 
 - Copy all the text by pressing ctrl-c (this will take even more time) 
 - When the progress bar finishes showing the data has been copied to the clipboard, open you your favorite TEXT editor (notepad will do but is a bit slow) and paste in all the data.  
 - Save the file as data.txt in the same src directory that you have installed the php application. 
 - Run Load.php to load items from data.txt into app. 
 - In most recent versions all codes are in the same file, so you will also load Delisted items, but they are clearly labeled Delisted in the Section Column.  
 - Load iihdisplay.php to view and use the app. 

