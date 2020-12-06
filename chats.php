<?php
class Constants
{
    //DATABASE DETAILS
    static $DB_SERVER="localhost";
    static $DB_NAME="Dunamix";
    static $USERNAME="root";
    static $PASSWORD="";

    //STATEMENTS
    static $SQL_SELECT_ALL = "SELECT * FROM chats";
}

class Chats
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
                    array_push($pictures, array("id"=>$row['id'],"name"=>$row['name'],"messages"=>$row['messages'],"time"=>$row['time']));
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
            $this->select();
    }
}

//CALL FUCTION ACCORDING TO ITS REQUEST
$chats = new Chats();
$chats -> handleRequest();