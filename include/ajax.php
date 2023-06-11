<?php
require_once "database.php";

class Ajax {
    private $db;
    public static function renderView($path, $data = array()) {
        // Extract the data array into variables
        extract($data);
        // Start output buffering
        ob_start();
        // Include the view file
        include $path;
        // Get the buffered content
        $content = ob_get_clean();
        // Return the rendered content
        return $content;
    }
    public function __construct() {
        $this->db = new Database();
    }
    public function index(){
        $loggedIn = isset($_SESSION['user_id']);
        if ($loggedIn) {

            return Ajax::renderView("UI/views/dashboard.php",[
                'user'=>$this->getConnectedUser(),
                'users'=>$this->getUsers(),
                'stats'=>$this->getStats(),
                'UserData'=>$this->getUserData()
            ]);
        } else {
            return Ajax::renderView("UI/views/login.php");
        }
    }
    public function getRoles() {
        $sql = "SELECT * FROM roles";
        $stmt = $this->db->runQuery($sql);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($roles);
    }
    public function getStats() {
        $stats=[];
        $sql = "SELECT *  FROM projects WHERE  isDeleted='0'";
        $stmt = $this->db->runQuery($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats["projects"]= $data;

        $sql = "SELECT *  FROM assignments WHERE isDeleted='0'";
        $stmt = $this->db->runQuery($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats["assignments"]= $data;

        return $stats;
    }
    public function getUserData() {
        $UserData=[];
        $sql = "SELECT * FROM projects WHERE IsArchived='0' AND isDeleted ='0' AND UserId=:user_id";
        $params = array(
            ':user_id' => $_SESSION["user_id"],
        );
        $stmt = $this->db->runQuery($sql,$params);
        $UserData["projects"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT assignments.*,users.Username as Owner,projects.name as Project,Label as Status  FROM assignments  JOIN projects ON ProjectId=projects.Id JOIN users ON  projects.UserId =users.Id JOIN statuses ON StatusId=statuses.Id  WHERE  assignments.isDeleted ='0' AND projects.isDeleted =0 AND assignments.UserId=:user_id";
        $params = array(
            ':user_id' => $_SESSION["user_id"],
        );
        $stmt = $this->db->runQuery($sql,$params);
        $UserData["assignments"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->getProjectsAssignments($UserData["projects"]);

        $sql = "SELECT * FROM statuses";
        $stmt = $this->db->runQuery($sql);
        $UserData["statuses"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $UserData;
    }
    private function getProjectsAssignments(&$projects)
    {
        $assignments=[];
        foreach ($projects as &$project) {

            $sql = "SELECT assignments.*,users.Username,statuses.Label as Status FROM assignments JOIN users ON users.Id = UserId JOIN statuses ON statuses.Id = StatusId   WHERE ProjectId=:project_id AND isDeleted = '0'";
            $params = array(
                ':project_id' => $project["Id"],
            );
            $stmt = $this->db->runQuery($sql,$params);
            $project["assignments"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

    }

    public function registerUser()
    {
        try {

            $input = json_decode(file_get_contents('php://input'),true);
            $username = explode("@",$input["Email"])[0];
            $email = $input["Email"];
            $password =$input["Password"];
            $RoleId = 1;

            $sql = "INSERT INTO users (Username, Email, Password, RoleId) VALUES (:username, :email, :password, :RoleId)";
            $params = array(
                ':username' => $username,
                ':email' => $email,
                ':password' => $password,
                ':RoleId' => $RoleId
            );

            $stmt = $this->db->runQuery($sql, $params);

            return json_encode(["success" => true, "data" => $stmt]);
        } catch (PDOException $e) {
            return json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }
    public function getUsers() {
        $sql = "SELECT id,Username,Email,RoleId FROM users";
        $stmt = $this->db->runQuery($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($users);
    }

    public function getConnectedUser() {
        $sql = "SELECT * FROM users WHERE Id=:Id LIMIT 1";
        $params = array(
            ':Id' => $_SESSION["user_id"],
        );

        $stmt = $this->db->runQuery($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function logIn() {
        $input = json_decode(file_get_contents('php://input'),true);
        $email = $input["Email"];
        $password =$input["Password"];
        $sql = "SELECT * FROM users WHERE Email=:email AND Password=:password LIMIT 1";
        $params = array(
            ':email' => $email,
            ':password' => $password,
        );
        $stmt = $this->db->runQuery($sql, $params);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            if(count($user)!=0){
                $_SESSION["user_id"] = $user["Id"];
                return json_encode(["success" => true]);
            }
            else {
                return json_encode(["success" => false,"message"=>"invalid credentials"]);
            }
        }
        else {
            echo json_encode(["success" => false,"message"=>"User not Exists"]);
        }
    }
    public function logOut() {
        unset($_SESSION["user_id"]);
        header("location:/");
    }


    public function  addProject(){
        if(isset($_SESSION['user_id'])){

            try {

                $input = json_decode(file_get_contents('php://input'),true);
                $name = $input["Name"];
                $DueDate =$input["DueDate"];

                $sql = "INSERT INTO projects (Name, DueDate, UserId) VALUES (:name, :DueDate, :user_id)";
                $params = array(
                    ':name' => $name,
                    ':DueDate' => $DueDate,
                    ':user_id' => $_SESSION['user_id'],
                );
                $stmt = $this->db->runQuery($sql, $params);
                return json_encode(["success" => true, "data" => $stmt]);
            } catch (PDOException $e) {
                return json_encode(["success" => false, "message" => $e->getMessage()]);
            }
        }
        else {
            header("location:/");
        }
    }
    public function addAssignment()
    {
        if(isset($_SESSION['user_id'])){
            try {
                $input = json_decode(file_get_contents('php://input'),true);

                $name = $input["Name"];
                $DueDate =$input["DueDate"];
                $UserId =$input["UserId"];
                $Description =$input["Description"];
                $ProjectId =$input["ProjectId"];
                $statusId=1;

                $sql = "INSERT INTO assignments (Name, Description,DueDate, UserId,ProjectId,StatusId) VALUES (:name, :Description, :DueDate, :user_id,:ProjectId, :statusId)";
                $params = array(
                    ':name' => $name,
                    ':DueDate' => $DueDate,
                    ':Description' => $Description,
                    ':ProjectId' => $ProjectId,
                    ':user_id' => $UserId,
                    ':statusId'=>$statusId
                );
                $stmt = $this->db->runQuery($sql, $params);
                return json_encode(["success" => true, "data" => $stmt]);
            }
            catch (Exception $e) {
                return json_encode(["success" => false, "message" => $e->getMessage()]);
            }
        }
        else{
            header("location:/");
        }
    }
    public function deleteProject(){
        if(isset($_SESSION['user_id'])){
            try {
                $input = json_decode(file_get_contents('php://input'),true);

                $sql = "UPDATE projects SET isDeleted = '1' WHERE Id= :Id";
                $params = array(
                    ':Id' => $input["Id"],
                );
                $stmt = $this->db->runQuery($sql, $params);
                return json_encode(["success" => true]);
            }
            catch (Exception $e) {
                return json_encode(["success" => false, "message" => $e->getMessage()]);
            }
        }
        else{
            header("location:/");
        }
    }

    public function deleteAssignment(){
        if(isset($_SESSION['user_id'])){
            try {
                $input = json_decode(file_get_contents('php://input'),true);

                $sql = "UPDATE assignments SET isDeleted = '1' WHERE Id= :Id";
                $params = array(
                    ':Id' => $input["Id"],
                );
                $stmt = $this->db->runQuery($sql, $params);
                return json_encode(["success" => true]);
            }
            catch (Exception $e) {
                return json_encode(["success" => false, "message" => $e->getMessage()]);
            }
        }
        else{
            header("location:/");
        }
    }
    public function updateStatus(){
        if(isset($_SESSION['user_id'])){
            try {
                $input = json_decode(file_get_contents('php://input'),true);
                $sql = "UPDATE assignments SET StatusId = :statusId WHERE Id= :assignmentId";
                $params = array(
                    ':statusId' => $input["statusId"],
                    ':assignmentId' => $input["assignmentId"],
                );
                $stmt = $this->db->runQuery($sql, $params);
                return json_encode(["success" => true]);
            }catch (Exception $e){
                return json_encode(["success" => false, "message" => $e->getMessage()]);
            }
        }
        else{
            header("location:/");
        }
    }
    public function getProject()
    {
        if(isset($_SESSION['user_id'])){
            try {
                $input = json_decode(file_get_contents('php://input'),true);
                $sql = "SELECT * FROM projects WHERE  Id= :Id";
                $params = array(
                    ':Id' => $input["Id"],
                );
                $stmt = $this->db->runQuery($sql,$params);
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return json_encode(["success" => true, "projects" => $projects]);

                }catch (Exception $e){
                    return json_encode(["success" => false, "message" => $e->getMessage()]);
                }

        }
    }
    public function getAssignment()
    {   if(isset($_SESSION['user_id'])){
            try {
                    $input = json_decode(file_get_contents('php://input'),true);
                    $sql = "SELECT * FROM assignments WHERE  Id= :Id";
                    $params = array(
                        ':Id' => $input["Id"],
                    );
                    $stmt = $this->db->runQuery($sql,$params);
                    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return json_encode(["success" => true, "assignments" => $assignments]);

            }
            catch (Exception $e){
                return json_encode(["success" => false, "message" => $e->getMessage()]);
            }
        }
    }
    public function updateProject()
    {
        if(isset($_SESSION['user_id'])){
            try {
                    $input = json_decode(file_get_contents('php://input'),true);
    
                    $sql = "UPDATE projects SET Name=:name , DueDate=:DueDate WHERE  Id= :Id";
                    $params = array(
                        ':Id' => $input["ProjectId"],
                        ':name' => $input["Name"],
                        ':DueDate' => $input["DueDate"],
                    );
                    $stmt = $this->db->runQuery($sql,$params);
                    
                    return json_encode(["success" => true]);

            }
            catch (Exception $e){
                return json_encode(["success" => false, "message" => $e->getMessage()]);
            }

        }
    }

}