    <?php
    require_once('jwt.php');
    header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend's URL

    // Allow specific HTTP methods
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    // Allow specific headers
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200); // Respond OK to preflight
        exit();
    }
    class RestAPI {
        private $db;

        public function __construct($host, $dbname, $username, $password) {
            try {
                $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $this->sendResponse(500, ['error' => 'Database connection failed: ' . $e->getMessage()]);
                exit;
            }
        }

        // Handle the incoming request
        public function handleRequest() {
            $method = $_SERVER['REQUEST_METHOD'];
            $path = trim($_SERVER['PATH_INFO'] ?? '/', '/');

            // Parse the path
            $pathParts = explode('/', $path);
            $resource = $pathParts[0] ?? null;

            $id = $pathParts[1] ?? null;
            switch ($method) {
                case 'GET':
                    $this->handleGet($resource, $id);
                    break;
                case 'POST':
                    $this->handlePost($resource);
                    break;
                case 'PUT':
                    $this->handlePut($resource, $id);
                    break;
                case 'DELETE':
                    $this->handleDelete($resource, $id);
                    break;
                default:
                    $this->sendResponse(405, ['error' => 'Method Not Allowed']);
                    break;
            }
        }

        // Handle GET requests
        private function handleGet($resource, $id) {
            if ($resource === 'users') {
                if ($id) {
                    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $this->sendResponse(200, $user);
                } else {
                    $stmt = $this->db->query("SELECT * FROM users");
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $this->sendResponse(200, $users);
                }
            } else {
                $this->sendResponse(404, ['error' => 'Resource not found']);
            }
        }

        // Handle POST requests
        private function handlePost($resource) {
            if ($resource === 'users') {
                $data = json_decode(file_get_contents('php://input'), true);
                if (!empty($data['name']) && !empty($data['email'])) {
                    $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
                    $stmt->bindParam(':name', $data['name']);
                    $stmt->bindParam(':email', $data['email']);
                    $stmt->execute();
                    $this->sendResponse(201, ['message' => 'User created']);
                }
                else {
                    $this->sendResponse(400, ['error' => 'Invalid data']);
                }
            } 
            else if($resource === 'login'){
                $data = json_decode(file_get_contents('php://input'), true);
                $this->userLogin($data);
                
            }else if($resource === 'course_videos'){
                $data = json_decode(file_get_contents('php://input'), true);
            //    print_r($data);die;
                $this->getVideoUrl($data);
                // print_r($this->getVideoUrl($data));die;
                
            }
            else {
                $this->sendResponse(404, ['error' => 'Resource not found']);
            }
        }

        // Handle PUT requests
        private function handlePut($resource, $id) {
            if ($resource === 'users' && $id) {
                $data = json_decode(file_get_contents('php://input'), true);
                if (!empty($data['name']) || !empty($data['email'])) {
                    $fields = [];
                    if (!empty($data['name'])) {
                        $fields[] = "name = :name";
                    }
                    if (!empty($data['email'])) {
                        $fields[] = "email = :email";
                    }
                    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
                    $stmt = $this->db->prepare($sql);
                    if (!empty($data['name'])) {
                        $stmt->bindParam(':name', $data['name']);
                    }
                    if (!empty($data['email'])) {
                        $stmt->bindParam(':email', $data['email']);
                    }
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $this->sendResponse(200, ['message' => 'User updated']);
                } else {
                    $this->sendResponse(400, ['error' => 'Invalid data']);
                }
            } else {
                $this->sendResponse(404, ['error' => 'Resource not found']);
            }
        }

        // Handle DELETE requests
        private function handleDelete($resource, $id) {
            if ($resource === 'users' && $id) {
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $this->sendResponse(200, ['message' => 'User deleted']);
            } else {
                $this->sendResponse(404, ['error' => 'Resource not found']);
            }
        }

        private function userLogin($data) {
            if (!empty($data['email']) && !empty($data['password'])) {
                // Hash the password using md5
                $hashedPassword = md5($data['password']);
        
                // Query to match the email and hashed password
                $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
                $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->execute();
        
                // Fetch the user
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($user) {
                    $data['id']             = $user['id'];
                    $data['branch_id']      = $user['branch_id'];
                    $data['emp_id']         = $user['emp_id'];
                    $data['name']           = $user['name'];
                    $data['email']          = $user['email'];
                    $data['phone']          = $user['phone'];
                    $data['group_id']       = $user['group_id'];



                    if($user['status'] == 'active'){
                        $payload = ['email'=> $user['email'],'group_id'=> $user['group_id'],'name'=>$user['name'],'id'=>$user['id']];
                        $jwt = new JwtHandler();
                        $token = $jwt->generateToken($payload);
                        $this->sendResponse(200, ['status' => 'success','message'=>'Login successful','user'=>$data,'token'=>$token]);
                    }else{
                        $this->sendResponse(200, ['status' => 'error','message'=>'Your account is not active.please']);
                    }
                    
                } else {
                    $this->sendResponse(200, ['status' => 'error','message'=>'Invalid email or password']);
                }
            } else {
                $this->sendResponse(200, ['status' => 'error','message'=>'Email and password are required']);
            }
        }
        
        private function getVideoUrl($data) {
            // Check if the group_id is passed as a GET parameter
            if (isset($_GET['group_id'])) {
                $groupId = $_GET['group_id'];
            
                // Your logic to fetch the videos by group_id
                $stmt = $this->db->prepare("SELECT courses.*, GROUP_CONCAT(course_videos.video_url) AS video_urls
                       FROM courses
                       LEFT JOIN course_videos ON courses.id = course_videos.course_id
                       WHERE courses.group_id = :group_id
                       GROUP BY courses.id
                       LIMIT 0, 25");
                $stmt->bindParam(':group_id', $groupId, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if ($results) {
                    // Send response with data
                    $this->sendResponse(200, [
                        'status' => 'success',
                        'message' => 'Data fetched successfully',
                        'data' => $results
                    ]);
                } else {
                    // No data found
                    $this->sendResponse(404, [
                        'status' => 'error',
                        'message' => 'No data found for the given group ID'
                    ]);
                }
            } else {
                $this->sendResponse(400, [
                    'status' => 'error',
                    'message' => 'Group ID is required'
                ]);
            }
        }
        
        
        
        
        
        
        

        // Send a JSON response
        private function sendResponse($status, $data) {
            http_response_code($status);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    }
   

    // Example Usage
    $api = new RestAPI('localhost', 'training', 'root', '');
    $api->handleRequest();
                
    