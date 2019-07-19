<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/CommonAction.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/WorkshopDAO.php");
	class WorkshopInfosAction extends CommonAction {

		public $workshop;
		public $member_workshops;
		public function __construct() {
			parent::__construct(CommonAction::$VISIBILITY_CUSTOMER_USER,"workshop-infos", "Workshop Information");
		}

		protected function executeAction() {
			if(!empty($_GET["workshop"]))
			{
				$id = intval($_GET["workshop"]);
				$this->workshop = WorkshopDAO::selectWorkshop($id);
				$this->show_workshop = true;
				$this->questions = WorkshopDAO::selectWorkshopQuestions($id);
				$this->member_workshops = [];
				if(!empty($_SESSION["member_id"]))
				{
					$this->member_workshops =WorkshopDAO::selectMemberWorkshop($_SESSION["member_id"]);
					$existe = false;
					foreach ($this->member_workshops as $item) {
						if($id == $item["ID_WORKSHOP"])
						{
								$existe = true;
								break;
						}
					}
					if(!$existe)
					{
						WorkshopDAO::addMemberWorkshop($_SESSION["member_id"],$id,2);
					}
				}



			}
		}
	}