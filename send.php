<?php
class Constants
{
    //DATABASE DETAILS
    static $DB_SERVER="localhost";
    static $DB_NAME="Dunamix";
    static $USERNAME="root";
    static $PASSWORD="";

    //STATEMENTS
    static $SQL_SELECT_ALL="SELECT * FROM chats";
}

class SendMessage
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
            $messages = mysqli_real_escape_string($con, $_POST['messages']);
            $name = mysqli_real_escape_string($con, $_POST['name']);

            $sql = "INSERT INTO chats (messages, name) VALUES ('$messages', '$name')";
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
        }else{
            print(json_encode(array("message"=>"ERROR PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
    }

       //1.SELECT FROM DATABASE.
    public function select()
    {
        $con=$this->connect();
        if($con != null)
        {
            $result=$con->query(Constants::$SQL_SELECT_ALL);
            if($result->num_rows > 0)
            {
                $pictures=array();
                while($row=$result->fetch_array())
                {
                    array_push($pictures, array("id"=>$row['id'],"name"=>$row['name'],"description"=>$row['description'],"image_url"=>$row['image_url']));
                }
                print(json_encode(array_reverse($pictures)));
            }else
            {
            }
            $con->close();

        }else{
            print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
    }
    
    //6. HANDLE REQUEST
    public function handleRequest() {
        if (isset($_POST['messages'])) {
            $this->insert();
        }
         else{
            $this->select();
        }
    }
}

//CALL FUCTION ACCORDING TO ITS REQUEST
$sendMessage = new SendMessage();
$sendMessage -> handleRequest();