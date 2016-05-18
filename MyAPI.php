class MyAPI extends HttpWrapper
{
    protected $User;

    public function __construct($request, $origin) {
		// flavor of depencency indection.
		  
        $User = new User();

    }

    /**
     * Endpoint
     */
     protected function example() {
        if ($this->method == 'GET') {
            return "Your name is " . $this->User->name;  // just a example.
        } else {
            return "Only accepts GET requests";
        }
     }
 }