<?php
namespace application\modules\billing;
use application\core\mvc\MainController;
use application\modules\billing\model;


class Controller extends MainController
{
    public $block, $model, $sms;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
		(substr($_SERVER['REQUEST_URI'], 0, 20) == "/billing/result-pay/") ? "" : $this->ExistSessionAuth();
		//$this->ExistSessionAuth();        
        $this->RunAjax();
    }

    public function ActionIndex($param)
    {
        $data = true;
        $this->headerTxt['title'] = "Пополнение счёта - GS11";
        if(isset($param) && $param == "pay")
        {
            $data = $this->GetPay();
        }
        $this->view->Generate('menu/auth-menu.tpl.php', 'billing/index.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
		/*$outSumm = $_REQUEST["OutSum"];        
        $pass1 = "z!f48pqdsv6y";
        echo strtoupper(md5($outSumm.":37134398:".$pass1));        */
        //$this->view->Generate('menu/auth-menu.tpl.php', 'billing/info.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionTariff()
    {
        $data = true;
        $this->headerTxt['title'] = "Выбор тарифа для подключения - GS11";
        $this->view->Generate('menu/auth-menu.tpl.php', 'billing/tariff.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }
	public function ActionResultPay()
    {
		if(isset($_REQUEST["OutSum"], $_REQUEST["SignatureValue"]))
		{
			$outSumm = $_REQUEST["OutSum"];
			$payUser = $this->model->PayUser();
			$pass1 = "z!f48pqdsv6y";
			$crcMy  = strtoupper(md5($outSumm.":".$payUser->pay.":".$pass1));
			$crc  = strtoupper($_REQUEST["SignatureValue"]);

			if($_REQUEST['InvId'] == $payUser->pay && $crcMy == $crc && $payUser->sum == (int)$outSumm)
			{
				$balance = (int)$payUser->sum + (int)$payUser->balance;
				$this->model->UpdatePay($payUser, $balance);
				/*echo true;
				$data = true;
				$this->headerTxt['title'] = "Успешное проведение платежа ".$payUser->pay." - GS11";
				$this->view->Generate('menu/auth-menu.tpl.php', 'billing/success.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());*/
			}
			else
			{
				echo "Уже оплачено";
				/*$data = false;
				$this->headerTxt['title'] = "Неверные реквизиты платежа - GS11";
				$this->view->Generate('menu/auth-menu.tpl.php', 'billing/success.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());*/
			}
		}
		exit("error");
    }

    public function ActionSuccess()
    {		
		/*$outSumm = $_REQUEST["OutSum"];
        $payUser = $this->model->PayUser();		
        $pass1 = "z!f48pqdsv6Y";
        $crcMy  = strtoupper(md5($outSumm.":".$payUser->pay.":".$pass1));
        $crc  = strtoupper($_REQUEST["SignatureValue"]);
        echo $crcMy;
        echo $payUser->pay;
		var_dump($payUser);
        if($_REQUEST['InvId'] == $payUser->pay && $crcMy == $crc && $payUser->sum == (int)$outSumm)
        {            
            $data['pay'] = $payUser->pay;
            $this->headerTxt['title'] = "Успешное проведение платежа №".$payUser->pay." - GS11";
            $this->view->Generate('menu/auth-menu.tpl.php', 'billing/success.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        }
        else
        {
            $data['pay'] = false;
            $this->headerTxt['title'] = "Неверные реквизиты платежа - GS11";
            $this->view->Generate('menu/auth-menu.tpl.php', 'billing/success.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        }*/
		$data['pay'] = $_REQUEST['InvId'];
		$this->model->GetRefreshDataUser();
		$this->headerTxt['title'] = "Успешное проведение платежа №".$_REQUEST['InvId']." - GS11";
		$this->view->Generate('menu/auth-menu.tpl.php', 'billing/success.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
		
    }
    public function ActionError()
    {
        $payUser = $this->model->PayUser();
        //echo "Здесь будет ошибка при неверном заполнении данных, условий быть ни каких не должно просто показать какие данные были отправленны, и какие пришли";
        // todo реализовать лог ошибок
        if(isset($_GET['OutSum'], $_GET['InvId'], $payUser->sum) && $_GET['OutSum'] == $payUser->sum && $_GET['InvId'] == $payUser->pay)
        {
            $data['pay'] = $payUser->pay;
            $this->headerTxt['title'] = "Ошибка платежа - GS11";
            $this->view->Generate('menu/auth-menu.tpl.php', 'billing/error.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        }
        else
        {
            header("Location: /billing");
            exit();
        }
    }

    private function GetPay()
    {
        $data['login'] = "vayas";
        $data['pass-1'] = "z!f48pqdsv6Y";
        $time = (string)mktime();
        $data['pay-id'] = rand(10, 99) . $_SESSION['user-data']['id'] . rand(10, 99) . mb_substr($time, 8, 10);
        $data['desc'] = "Пополнение счёта - GS11";
        $data['sum'] = "50";
        $data['crc']  = md5($data['login'].":".$data['sum'].":".$data['pay-id'].":".$data['pass-1']);
        $this->model->Payment($data['pay-id'], $data['sum'], $data['desc']);

        return $data;
    }

}