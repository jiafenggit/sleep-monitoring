<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * ���ڼ��ҵ�������ѭ�����߳�ʱ������������
 * �������ҵ���������Խ�����declare�򿪣�ȥ��//ע�ͣ�����ִ��php start.php reload
 * Ȼ��۲�һ��ʱ��workerman.log���Ƿ���process_timeout�쳣
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * ���߼�
 * ��Ҫ�Ǵ��� onConnect onMessage onClose ��������
 * onConnect �� onClose �������Ҫ���Բ���ʵ�ֲ�ɾ��
 */
class Events
{
    /**
     * ���ͻ�������ʱ����
     * ���ҵ����˻ص�����ɾ��onConnect
     * 
     * @param int $client_id ����id
     */
    public static function onConnect($client_id)
    {
        // ��ǰclient_id�������� 
        // Gateway::sendToClient($client_id, "Hello\r\n"); 
        // �������˷���
        // Gateway::sendToAll("$client_id login\r\n");
        echo "$client_id login\r\n";
    }
    
   /**
    * ���ͻ��˷�����Ϣʱ����
    * @param int $client_id ����id
    * @param mixed $message ������Ϣ
    */
   public static function onMessage($client_id, $message)
   {	
   		$db_host = "localhost";
      $db_user = "root";
      $db_pwd = "";
      $db_name = "mattress";
      $conn = mysqli_connect($db_host, $db_user, $db_pwd, $db_name) or die("connect failed!\n");
        mysqli_query($conn, "set names 'utf8'");
      $arr = explode("-",$message);
      if($arr[0] == 'd'){
        $mattressid = $arr[1];
        $res = mysqli_query($conn,"select * from `user-info` where mattressid='$mattressid'");
        $row = mysqli_fetch_array($res, MYSQL_ASSOC);
        $userid = $row['userid']; 
        $lng = $arr[2];
        $lat = $arr[3];
        date_default_timezone_set('PRC'); 
        $datetime = date("Y-m-d H:i:s",strtotime("now"));
        $result = mysqli_query($conn,"select * from `gpsdetail` where userid='$userid'");
        $num_rows = mysqli_num_rows($result);
        if($num_rows==0){
          $sql = "insert into `gpsdetail`(`userid`,`lng`,`lat`,`datetime`) values('$userid','$lng','$lat','$datetime')";
        }else{
          $sql = "update `gpsdetail` set `lng`='$lng',`lat`='$lat',`datetime`='$datetime' where userid='$userid'";
        };
        mysqli_query($conn, $sql);
        mysqli_close($conn);
      }
   }
   /**
    * ���û��Ͽ�����ʱ����
    * @param int $client_id ����id
    */
   public static function onClose($client_id)
   {
       // �������˷��� 
       // GateWay::sendToAll("$client_id logout\r\n");
   		echo "$client_id logout\r\n";
   }
}
