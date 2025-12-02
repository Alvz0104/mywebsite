<?php
class User{
    private $file="users.json";
    private $users=[];

public function __construct()
{
    if(file_exists($this->file)){
        $data=json_decode(file_get_contents($this->file),true);
        $this->users=$data?$data:[];
    }
    else{
        $this->users=[];
    }
}
//register
public function register($username,$password,$email){
    foreach($this->users as $user){
        if($user["username"]==$username){
            return "Username is already exist";
            exit();
        }
    }

    $this->users[]=[
        "username"=>$username,
        "password"=>password_hash($password,PASSWORD_DEFAULT),
        "email"=>$email,
    ];
    file_put_contents($this->file,json_encode($this->users,JSON_PRETTY_PRINT));
    return "Registration Successfully";
}


public function login($username,$password){
    foreach($this->users as $user){
        if($user["username"]== $username){
            if(password_verify($password,$user["password"])){
                return "Login Successful";
                exit();
                     }
                     else{
                        return "Invalid Password";
                        exit();
                     }
        }
    }
    return "Username not found";
}
}





?>