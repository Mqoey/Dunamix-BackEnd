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

class Login
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
        // INSERT
        $con=$this->connect();

        if($con != null)
        {
            // Get text
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $password = mysqli_real_escape_string($con, $_POST['password']);
            $password2 = md5($password);

                
             $query = "SELECT * FROM users WHERE email='$email' AND password='$password2' LIMIT 1";
             $results = mysqli_query($con, $query);

             if (mysqli_num_rows($results) == 1) { // user found

             	print(json_encode(array("message"=>"Success")));

                $logged_in_user = mysqli_fetch_assoc($results);
				
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
					
				}else {
						array_push($errors, "Wrong username/password combination");
				}
                	
        }else{

            print(json_encode(array("message"=>"ERROR PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
    }
}

//CALL FUCTION ACCORDING TO ITS REQUEST
$login = new Login();
$login -> handleRequest();