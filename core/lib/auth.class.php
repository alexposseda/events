<?php
class Auth{
    public static function signIn($email, $password){
        $link = Db::getLink();

        $user = $link->select('users', ['id', 'role'])->selectWHERE_AND(['email'=>$email, 'password'=>$password])->sendSelectQuery();
        if($user->num_rows == 1){
            $user_data = $user->fetch_assoc();
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['role'] = $user_data['role'];
            return true;
        }else{
            return false;
        }
    }
    public static function checkAuth(){
        return (isset($_SESSION['user_id']) and !empty($_SESSION['user_id'])) ? true : false ;
    }
}