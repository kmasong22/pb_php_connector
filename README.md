# pb_php_connector
A very simple PocketBase PHP Connector

This is a very simple attempt to create a connector for pocketbase when using PHP as the primary language.
https://github.com/pocketbase/pocketbase

Uploading documents to pocketbase

    session_start();

    define( 'API_SERVER', 'http://127.0.0.1:8013' );
    include_once( __DIR__ . '/pb.php' );

    PB::_init('documents');
    $result = PB::GET( 'p5xk00fn2b2ta0b' , 'documents' );

    // print_r( $result );

    if(isset($_POST['submit'])){

	    $cFile  = curl_file_create($_FILES['file']['tmp_name'],$_FILES['file']['type'],$_FILES['file']['name']);

	    $data = [
		    'title' => $_POST['title'],
		    'owner' => $_POST['owner'],
		    'file'  => $cFile
	    ];

	    $result = PB::CREATE( $data , 'documents' );
