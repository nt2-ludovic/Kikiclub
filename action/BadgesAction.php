<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/CommonAction.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/BadgeDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/FamilyDAO.php");

	class BadgesAction extends CommonAction {
		public $badges;
		public function __construct() {
			parent::__construct(CommonAction::$VISIBILITY_CUSTOMER_USER,'family-badges','Badges');
		}

		protected function executeAction() {
			//Get all badges
			$this->badges = BadgeDAO::getBadges();
		}
	}