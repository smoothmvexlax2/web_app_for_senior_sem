<?
// Some help with strings in PHP and building SQL insert statements

// The auto-increment primary key is sent as an empty string
// VARCHAR fields need to be quoted, whereas numeric fields do not 

$query = "INSERT INTO table_name VALUES ('','Fred','Jones',33)";

// Here is simulating submitting some data
$_REQUEST['fname'] = 'Fred'; 
$_REQUEST['lname'] = 'Jones'; 

// When directly inserting submitted form data, be careful the VARCHAR fields still get quoted.

$query = "INSERT INTO table_name VALUES ('','" . $_REQUEST['fname']. "','" . $_REQUEST['lname']. "',33)";

// A slightly cleaner solution is to use the fact that PHP scalar variables are 'Interpolated' inside double quoted strings.

$x = "cats";

// below $y will contain "I love cats"

$y = "I love $x";

// below $y will contain 'I love $x' since variables are not interpolated inside single quoted strings

$y = 'I love $x';  

// So it can be cleaner to construct SQL queries from submitted data by using extra scalar variables as follows

$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];

$query = "INSERT INTO table_name VALUES ('','$fname','$lname',33)";

// You can see the query like this - very helpful for debugging

// var_dump($query);

//  If you aren't sure if your query is being constructed properly inside your PHP code
//  var_dump that puppy and see if it is formatted correctly.

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Query Construction in PHP</title>
  </head>
  
  <body>
    You have to look at the source code to see how the query below is constructed.
    <br><br>
    <?= $query ?>
    <br><br>
    Sometimes it's hard to figure out what is wrong with a query when you execute it through the PHP
    mysql_query() function.
    It's hard to see exactly what's inside the returned mysql result set until you start looping over it in PHP fetching out one row at a time.
    <br><br>
    Notice in the PHPMyAdmin database GUI that there is an "SQL" tab near the "Browse" and "Structure" tabs. 
    You can copy and paste a raw SQL query such as above directly into the GUI using the "SQL" tab.
    If you aren't sure what your query is returning, you can var_dump() it or echo it into your page to get the raw SQL.
    Then you can exectute the query in the GUI to see exactly what it is returning, or what error it's throwing. 
    Once you know exactly what your query is returning, then you can better figure out how to deal with the queried data in PHP.
  </body>
</html> 