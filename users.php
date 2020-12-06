<?php
class Constants
{
    //DATABASE DETAILS
    static $DB_SERVER="localhost";
    static $DB_NAME="Dunamix";
    static $USERNAME="root";
    static $PASSWORD="";

    //STATEMENTS
    static $SQL_SELECT_ALL="SELECT * FROM users";
}

class Users
{
    /*
       1.CONNECT TO DATABASE.
       2. RETURN CONNECTION OBJECT
    */
    public function connect()
    {
        $con=new mysqli(Constants::$DB_SERVER,Constants::$USERNAME,Constants::$PASSWORD,Constants::$DB_NAME);
        if($con->connect_error)
        {
            //echo "Unable To Connect";
            return null;
        }else
        {
            return $con;
        }
    }

    public function insert()
    {
        // 3.INSERT USER TO DATABASE.
        $con=$this->connect();

        if($con != null)
        {
            // Get text
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $name = mysqli_real_escape_string($con, $_POST['name']);
            $password = mysqli_real_escape_string($con, $_POST['password']);
            $encrypt_password = md5($password);

            $sql = "INSERT INTO users (email,name,password) VALUES ('$email', '$name', '$encrypt_password')";
            try
            {
                $result=$con->query($sql);
                if($result)
                {
                	print(json_encode(array("message"=>"Success")));
                }else
                {
                    print(json_encode(array("message"=>"Unsuccessful. Connection was successful but data could not be Inserted.")));
                }
                $con->close();
            }catch (Exception $e)
            {
                print(json_encode(array("message"=>"ERROR PHP EXCEPTION : CAN'T SAVE TO MYSQL. ".$e->getMessage())));
                $con->close();
            }
        }
    }

       // 4.SELECT USER FROM DATABASE.
    public function select()
    {
        $con=$this->connect();
        if($con != null)
        {
            $result=$con->query(Constants::$SQL_SELECT_ALL);
            if($result->num_rows > 0)
            {
                $users=array();
                while($row=$result->fetch_array())
                {
                    array_push($users, array("id"=>$row['id'],"name"=>$row['name'],"email"=>$row['email']));
                }
                print(json_encode(array_reverse($users)));
            }else
            {
            }
            $con->close();

        }else{
            print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
    }

    //5.DELETE USER FROM DATABASE.
    public function delete(){

        $con=$this->connect();
        if($con != null)
        {
            $id = mysqli_real_escape_string($con, $_POST['id']);
            $sql = "DELETE FROM users WHERE id = $id";

            $query = "SELECT * FROM users WHERE id=$id";
            $user_id = mysqli_query($con, $query);

            if (mysqli_num_rows($user_id) == 1) { // user found

                try
                {
                    $result=$con->query($sql);
                    if($result)
                    {
                        print(json_encode(array("message"=>"Success")));
                }else
                    {
                        print(json_encode(array("message"=>"Unsuccessful. Connection was successful but user could not be Deleted.")));
                    }
                    $con->close();
                }catch (Exception $e)
                    {
                        print(json_encode(array("message"=>"ERROR PHP EXCEPTION : CAN'T DELETE ON MYSQL. ".$e->getMessage())));
                    $con->close();
                }

            }else
            {
                print(json_encode(array("message"=>"Unsuccessful. No user with defined ID.")));
            }
        }else{
            print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
    }
    
    //6. HANDLE REQUEST
    public function handleRequest() {
        if (isset($_POST['name'])) {
            $this->insert();
        }elseif(isset($_POST['id'])){
            $this->delete();
        }
        else{
            $this->select();
        }
    }
}

//CALL FUCTION ACCORDING TO ITS REQUEST
$users = new Users();
$users -> handleRequest();