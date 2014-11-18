<?php
// Constants
require_once __DIR__ . '/Constants/Constants.php';

// Interfaces
require_once __DIR__ . '/Templating/Html/IDocumentElement.php';
require_once __DIR__ . '/Templating/Tags/IViewTag.php';
require_once __DIR__ . '/ViewRendering/IFileReader.php';
require_once __DIR__ . '/Templating/Tags/ITagExtractor.php';
require_once __DIR__ . '/Routing/IRequest.php';

// Exceptions
require_once __DIR__ . '/Exceptions/TagWithNoContentException.php';
require_once __DIR__ . '/Exceptions/OpenTagNotClosedException.php';
require_once __DIR__ . '/Exceptions/UnrecognizedElementTypeException.php';
require_once __DIR__ . '/Exceptions/UnrecognizedTagTypeException.php';
require_once __DIR__ . '/Exceptions/InvalidRequestMethodException.php';

// Classes
require_once __DIR__ . '/Templating/Tags/ViewTag.php';
require_once __DIR__ . '/Templating/Tags/TagExtractor.php';
require_once __DIR__ . '/Templating/Html/TagElement.php';
require_once __DIR__ . '/Templating/Html/HtmlElement.php';
require_once __DIR__ . '/Templating/Html/Document.php';
require_once __DIR__ . '/Templating/Html/TagElement.php';

require_once __DIR__ . '/ViewRendering/PhpRenderer.php';
require_once __DIR__ . '/ViewRendering/FileReader.php';
require_once __DIR__ . '/ViewRendering/ViewRenderer.php';

require_once __DIR__ . '/Common/PathExtensions.php';

require_once __DIR__ . '/Routing/ControllerRouting.php';
require_once __DIR__ . '/Routing/Request.php';
require_once __DIR__ . '/Routing/RequestMethod.php';
require_once __DIR__ . '/Routing/UriManipulator.php';
require_once __DIR__ . '/Routing/MethodInvoker.php';


