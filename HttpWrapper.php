abstract class HttpWrapper
{
    
    /**
     * Argunments.
     */ 
    protected $args = Array();
	
    /**
     * Used for PUT
     */
     protected $file = Null;
	 
	 /**
     * 
     * The HTTP methods either GET, POST, PUT or DELETE
     */
    protected $method = '';
	
    /**
     * URI Requested.
     */
	 
    protected $endpoint = '';
    /**
     * Used for some thing other than methods.      
	 */ 
    protected $verb = '';
	

    /**
     * Constructor
     */
    public function __construct($request) {
	  $this->Wrapper();	// cleaner to call it in a function instead of putting it in the constructor.
    }
	
	/**
     * Wrapper
     */
	private Wrapper(){
		header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($this->args);
        $this->verb = array_shift($this->args);
        $this->method = $_SERVER['REQUEST_METHOD'];
		
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Header not correct.");
            }
        }

        switch($this->method) {
        case 'DELETE':
        case 'POST':
            $this->request = $this->$_POST;
            break;
        case 'GET':
            $this->request = $this->$_GET;
            break;
        case 'PUT':
            $this->request = $this->$_GET;
            $this->file = file_get_contents("php://input");
            break;
        default:
            $this->response('Invalid Method', 405);
            break;
        }
	}
	
	/**
     * Public to it can be called.
     */
	public function processAPI() {
        if (method_exists($this, $this->endpoint)) {
            return $this->_response($this->{$this->endpoint}($this->args));
        }
        return $this->response("No Endpoint: $this->endpoint", 404);
    }

	/**
     * Send the responce back.
     */
    private function response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    private function requestStatus($code) {
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
	
}