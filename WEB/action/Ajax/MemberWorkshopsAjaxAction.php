<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/CommonAction.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/WorkshopDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/MemberWorkshopDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/MemberDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/FilterDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/RobotDAO.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/action/DAO/BadgeDAO.php");
	class MemberWorkshopsAjaxAction extends CommonAction {
		public $results;
		public function __construct() {
			parent::__construct(CommonAction::$VISIBILITY_ADMIN_USER,'member-workshops-ajax');
			$this->results = [];
		}

		protected function executeAction() {
			$id_member =$_SESSION["member_id"];
			$this->results['member'] = $id_member;
			if(!empty($_POST["id_workshop"]) &&
			!empty($_POST["category"]))
			{
				$this->results['workshop'] = $_POST['id_workshop'];
				$this->results['category'] = $_POST['category'];

				$workshops = MemberWorkshopDAO::selectMemberWorkshop($id_member);
				switch ($_POST["category"]) {
					case 'new':
						$statut = 1;
							break;
					case 'in-progress':
						$statut = 2;
						break;
					case 'complete':
						$statut = 3;
						break;
					default:
						$statut = 1;
						break;
				}
				if(!empty($workshops[intval($_POST["id_workshop"])]))
				{
					$this->results['state'] = "existe";
					MemberWorkshopDAO::updateMemberWorkshop($id_member,intval($_POST["id_workshop"]), $statut);
				}
				else
				{
					$this->results['state'] = "nouveau";
					$this->results['state_working'] = MemberWorkshopDAO::addMemberWorkshop($id_member,intval($_POST["id_workshop"]), $statut);
				}

				$workshops = MemberWorkshopDAO::selectMemberWorkshop($id_member);
				if($statut == 3)
				{
					$filters = FilterDAO::getWorkshopFilters(intval($_POST["id_workshop"]));
					if(!empty($filters[FilterDAO::getFilterTypeIdByName("difficulty")]) &&
					!empty($filters[FilterDAO::getFilterTypeIdByName("robot")]))
					{
						$difficulties = $filters[FilterDAO::getFilterTypeIdByName("difficulty")];
						$robots = $filters[FilterDAO::getFilterTypeIdByName("robot")];
							
						$score = 0;
						if(!empty($robots) || !empty($difficulties))
						{
							foreach ($robots as $robot) {
								$this->results['robots'][] = $robot;
								foreach ($difficulties as $diff) {
									$this->results['difficulties'][] = $diff;
									$score += RobotDAO::getScoreOfRobotByDifficulty($robot["id_filter"],$diff["id_filter"]);
									# code...
								}
							}
						}

						$this->results['added_score'] = $score;
						MemberDAO::addScore($id_member,$score);
						
						$member = MemberDAO::selectMember($id_member);
						$member_badges = BadgeDAO::getMemberBadge($id_member);
						$badges = BadgeDAO::getBadges(1);
						$this->results['score'] = $member['score'];
						
						foreach ($badges as $badge) {
							
							if($member["score"] >= $badge["value_needed"] )
							{
								if(!array_key_exists($badge["id"],$member_badges))
								{
									$this->results[] = $member["firstname"] . " just won the " . $badge["name"] . " badge";
									BadgeDAO::addBadgeToMember($badge["id"],$member["id"],$member["id_user"]);
								}
								else
								{
									$this->results[] = $member["firstname"] . " already have the " . $badge["name"] . " badge";
								}
							}
							else
							{
								$this->results[] = $member["firstname"] . " need to have " . $badge["value_needed"] . " to have the badge. He only have " . $member["score"] . " pts";
								
							}
						}
					}


				
					
				}

			}
		}
	}