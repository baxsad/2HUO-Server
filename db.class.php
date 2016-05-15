<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 4/29/16
 * Time: 5:26 下午
 */

require("config.php");

class coolMysql
{
    var $db_name;
    var $db_pass;
    var $db_host;
    var $db_user;
    var $db;
    var $error;

    public function coolMysql($db_name="",$db_pass="",$db_host="",$db_user=""){
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_name = $db_name;
        $this->db_pass = $db_pass;
    }

    /**
     * 连接数据库
     */
    private function connect()
    {
        //echo $this->db_host.$this->db_user.$this->db_pass;
        $conn = mysqli_connect($this->db_host,$this->db_user,$this->db_pass);
        if (!$conn)
        {
            die('Could not connect: ' . mysqli_errno($conn));
        }
        else
        {
            //echo 'connected!';
        }
        mysqli_select_db($conn,$this->db_name) or die ("error select db") ;
        mysqli_set_charset($conn,'utf8');
        return $conn;
    }

    /**
     * 运行Sql语句,不返回结果集
     *
     * @param string $sql
     *
     * @return array 返回结果集
     */
    public function runSql($sql)
    {

        if($sql=='') return null;
        $db=$this->db();
        $result=mysqli_query($db,$sql);
        $this->save_error($db);
        return $result;

    }

    /**
     * 运行Sql,以多维数组方式返回结果集
     *
     * @param string $sql
     *
     * @param int $type
     *
     * @return array 成功返回数组，失败时返回false
     */
    public function getData($sql,$type=1){

        if($sql=='') return null;
        $data=Array();
        $db=$this->db();
        $result=mysqli_query($db,$sql);
        $this->save_error($db);
        if(is_bool($result))
            return $result;
        if($type==1)
            while($a=mysqli_fetch_array($result,MYSQLI_ASSOC))
                $data[]=$a;
        elseif($type==2)
            while($a=mysqli_fetch_row($result))
                $data[]=$a;
        mysqli_free_result($result);
        if($data)
            return $data;
        else
            return NULL;

    }

    /**
     * 运行Sql,以数组方式返回结果集第一条记录
     *
     * @param string $sql
     * @return array 成功返回数组，失败时返回false
     */
    public function getLine($sql)
    {
        $data=$this->getData($sql);
        if($data)
            return @reset($data);
        else
            return false;
    }

    /**
     * 运行Sql,返回结果集第一条记录的第一个字段值
     *
     * @param string $sql
     * @return string 成功时返回一个值，失败时返回false
     */
    public function getVar($sql){
        $data=$this->getLine($sql);
        if($data)
            return $data[@reset(@array_keys($data))];
        else
            return false;
    }

    /**
     * 函数返回上一步 INSERT 操作产生的 ID。
     *
     * @return int 成功返回行数,失败时返回-1
     * @author Elmer Zhang
     */
    public function affectedRows(){
        return mysqli_affected_rows($this->db());
    }

    /**
     * 关闭数据库连接
     *
     * @return bool
     */
    public function closeDb(){
        if(isset($this->db))
            @mysqli_close($this->db);
    }

    /**
     * 转义 SQL 语句中使用的字符串中的特殊字符
     *
     * @param string $str
     * @return string
     */
    public function escape($str){
        return addslashes($str);
    }

    /**
     * 返回错误信息
     *
     * @return string
     */
    public function error(){
        return $this->error;
    }

    private function db(){
        if(!isset($this->db)||!mysqli_ping($this->db))
            $this->db=$this->connect();
        return $this->db;
    }

    private function save_error($db){
        $this->error=mysqli_error($db);
    }

}

$DB = new coolMysql($db_name,$db_pass,$db_host,$db_user);
