<html>  <head>    <title>Cacahuetes - Jazz for all occasions</title>  </head>  <body>    <h1>Please hold the line!</h1>    <p>We'll be back before you know it :)</p>    <p>Current URL is <?php echo $_SERVER['REQUEST_URI']; ?></p><?php $split = preg_split('@/@', $_SERVER['REQUEST_URI'], null, PREG_SPLIT_NO_EMPTY);$elements = count($split);echo "<p>Elements: $elements";$controller = $elements > 0 ? $split[0] : "Home";$method = $elements > 1 ? $split[1] : "Index";$args = array_slice($split, 2);echo "<p>Controller: $controller</p>";echo "<p>Method: $method</p>";echo '<p>Args:</p>';echo '<ul>';foreach ($args as $arg) {  echo "<li>$arg</li>";}echo '</ul>';?>  </body></html>