<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/CommonAction.php");

	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/WorkshopDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/FamilyDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/UsersDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/RobotDAO.php");

	class SearchAjaxAction extends CommonAction {
		public $results;
		public function __construct() {
			parent::__construct(CommonAction::$VISIBILITY_ADMIN_USER,"search-ajax","Search,Ajax");
		}

		protected function executeAction() {
			if(isset($_POST["name"]))
			{
				$name = $_POST["name"];
				if(isset($_POST["workshop"]))
				{
					$this->results = WorkshopDAO::getWorkshopsLikeName($name,false);
				}
				if(isset($_POST["search-user"]))
				{
					$this->results = UsersDAO::getUsersLikeType($name,$_POST["type"]);
				}
				if(isset($_POST["search-member"]))
				{
					$this->results = FamilyDAO::getFamilyLikeType($name,$_POST["type"]);
				}
				if(isset($_POST["robots"]))
				{
					$this->results = RobotDAO::getRobotsAndDifficultiesByNAME($name);

				}
			}

		}
	}