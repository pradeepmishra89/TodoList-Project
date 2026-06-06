<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DatabaseModel extends CI_Model {

  /*  public function getUsers()
    {
        return $this->db->get('test')->result();
    }

    public function insertData($insertData)
    {
        return $this->db->insert('test', $insertData);
    }

   public function login($userName, $password)
{
    $this->db->where('email', $userName);
    $query = $this->db->get('test');
     
    // print_r($query->result());
    // die;

    
    if($query->num_rows() > 0)
    {
        $user = $query->row();
        

        return password_verify($password, $user->password);
    }

    return false;
}

  public function updateData($id, $data)
{
    $this->db->where('id', $id);
    return $this->db->update('test', $data);
}

public function getUserById($id){
    $this->db->where('id',$id);
    return $this->db->get('test')->result();
}*/

public function validLogin($userName,$password){
    $this->db->where('email', $userName);
    $this->db->where('status', 1);
    $this->db->where('isdeleted', 0);
    $query = $this->db->get('users');
    if($query->num_rows()==1){
        $user = $query->row();

        if(password_verify($password,$user->password)){
            return [
                'status'  => true,
                'message' => 'Success',
                'id'=>$user->user_id,
                'name'=>$user->name
            ];
        }else{
            return [
                'status'  => false,
                'message' => 'Email or Password Incorrect'
            ];
        }
            }else{
                return [
                'status'  => false,
                'message' => 'Email or Password Incorrect'
            ];
            }
}

public function updateTable($tableName,$data,$condition){
    $this->db->where($condition);
     $this->db->where('isdeleted', 0);
    if($this->db->update($tableName, $data)){
        return true;
    }else{
        return false;
    }
     
}

public function updateSingleRow($tableName,$data,$condition){
    $this->db->where($condition);
    $this->db->where('isdeleted', 0);
  $result=  $this->db->update($tableName, $data);
    //   echo $result;die;

    return $this->db->affected_rows() > 0;
}

public function insertData($tableName,$data){
//    $result =  $this->db->insert($tableName,$data);
   
// echo "Insert ID: ".$this->db->insert_id()."<br>";
// echo $this->db->last_query();
// die;
//    var_dump($result);die;
   if($this->db->insert($tableName,$data)){
    return $this->db->insert_id();
   }else{
    return false;
   }

    
}


public function fetchData($tableName,$condition){
    if($tableName=='tasks'){
    $this->db->select('id, title, description,status');
    $this->db->where($condition);
    $this->db->where('isdeleted', 0);
    $result= $this->db->get($tableName);

      if ($result->num_rows() >= 1) {
            $ret = [];
            foreach ($result->result() as $row) {
                $ret[] = $row;
            }
            return $ret;
        } else {
            return [];
        }
    }else{
    $this->db->where($condition);
    $this->db->where('isdeleted', 0);

   $result= $this->db->get($tableName);

      if ($result->num_rows() >= 1) {
            $ret = [];
            foreach ($result->result() as $row) {
                $ret[] = $row;
            }
            return $ret;
        } else {
            return [];
        }
    }
}

public function fetchAllData($tableName){
    if($tableName=='tasks'){
        $this->db->select('id, title, description,status');
      $this->db->where('isdeleted', 0);
       $result = $this->db->get($tableName)->result();
      $ret=[];
   foreach($result as $row){
$ret[]=$row;
   }
   return $ret;
}else{
         $result = $this->db->get($tableName)->result();
      $ret=[];
   foreach($result as $row){
$ret[]=$row;
   }
   return $ret;
}
    }
   
}