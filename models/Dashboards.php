<?php

/**
 * Created by PhpStorm.
 * User: TNS Programmer
 * Date: 2022-02-16
 * Time: 4:39 PM
 */

namespace sprint\models;

use \sprint\database\Model;
use \sprint\helpers\Alias;
use \sprint\helpers\DataTable;

class Dashboards extends Model
{

	private $tableName = "users";

	private function filter($alias)
	{
		$filter = [$alias . ".id > 0"];

		$provinceArray = $_SESSION['user']['isSeller'] > 0 ? [$_SESSION['user']['province']] : $_POST["province"] ?? [];
		$districtArray = $_SESSION['user']['isSeller'] > 0 ? [$_SESSION['user']['district']] : $_POST["district"] ?? [];
		$dateFrom = $_POST["dateFrom"] ?? null;
		$dateTo = $_POST["dateTo"] ?? null;
		$campaign = $_POST["campaign"] ?? null;

		$province = array_map(function ($v) {
			return "'{$v}'";
		}, $provinceArray);

		$district = array_map(function ($v) {
			return "'{$v}'";
		}, $districtArray);

		$filter[] = !empty($provinceArray) ? $alias . ".province IN(" . implode(", ", $province) . ")" : null;
		$filter[] = !empty($districtArray) ? $alias . ".district IN(" . implode(", ", $district) . ")"  : null;
		#Filtering By Campain
		if ($campaign != null && $campaign != "All") {
			$dates = explode("=", $campaign);
			$filter[] = $alias . ".created_at >= DATE('" . substr($dates[0], 0, 10) . "')";
			$filter[] = $alias . ".created_at <= DATE('" . substr($dates[1], 0, 10) . "')";;
		}
		$filter[] = !empty($dateFrom) ? $alias . ".created_at >= '" . $dateFrom . "'" : null;
		$filter[] = !empty($dateTo) ? $alias . ".created_at <= '" . $dateTo . "'" : null;

		$filter = array_filter($filter);

		return !empty($filter) ? implode(" AND ", $filter) : "";
	}



	public function activeSprayersUsers()
	{
		$activeUsers = $this->select("users u")->columns("
			COUNT(*) AS activeUsers
		")->where($this->filter('u'))->result();
		return $activeUsers["activeUsers"];
	}

	/**
	 * Brings the number of sprayers based on the profile name
	 * @param $profile - name of the profile
	 */
	public function activeSprayersUsersByProfile($profile)
	{
		$activeUsers = $this->select("users u")->columns("
			COUNT(*) AS activeUsers
		")->join("profiles as p ON p.id = u.profile_id")->where($this->filter('u') . " AND p.name = '{$profile}'")->result();
		return $activeUsers["activeUsers"];
	}

	public function registeredFarmers()
	{
		$registeredFarmers = $this->select("farmers f")->columns("
			COUNT(*) AS registeredFarmers
		")->where($this->filter('f'))->result();

		return $registeredFarmers["registeredFarmers"];
	}

	public function assistedFarmers()
	{
		$assistedFarmers = $this->select("farmers f")->columns("
			COUNT(*) AS assistedFarmers
		")->where("f.farmer_uid IN(SELECT farmer_uid FROM chemical_application) AND {$this->filter('f')}")->result();

		return $assistedFarmers["assistedFarmers"];
	}

	public function chemicalProvenance()
	{
		$chemicalProvenance = $this->select("chemical_acquisition cha ")->columns("GROUP_CONCAT(CONCAT(\"'\",chemical_name,\"'\")) AS 'Chemical Name', chemical_supplier AS 'Chemical Supplier',GROUP_CONCAT(chemical_quantity) 'Quantity'
		")->join("users u ON cha.user_uid=u.user_uid")->where($this->filter('u'))->group("chemical_supplier")->results();

		return $chemicalProvenance;
	}

	public function rcnCollected()
	{
		$rcnCollected = $this->select("payments p INNER JOIN users u ON p.user_uid=u.user_uid")->columns("
			SUM(paid) AS rcnCollected
		")->where("p.payment_type IN ('Kg') AND {$this->filter('u')}")->result();

		return $rcnCollected["rcnCollected"];
	}

	public function cashCollected()
	{
		$cashCollected = $this->select("payments p INNER JOIN users u ON p.user_uid=u.user_uid")->columns("
			SUM(paid) AS cashCollected
		")->where("p.payment_type IN ('Mzn') AND {$this->filter('u')}")->result();

		return $cashCollected["cashCollected"];
	}

	public function rcnCollectedPerSprayers()
	{
		return $this->rcnCollected() / $this->activeSprayersUsers();
	}


	public function netIncomePerSprayers() //Formula for net Income
	{
		return ($this->TotalIncome() - $this->TotalExpenses()) / $this->activeSprayersUsers();
	}

	public function TotalExpenses() //Total Expenses Calculator
	{

		return $this->TotalFuelCost() + $this->TotalEmployeeCost() + $this->TotalOtherCost() + $this->TotalChemicalCost();
	}

	public function TotalFuelCost() //Total Fuel Expenses Calculator
	{
		$this->query = "
        SELECT CASE WHEN
            expenses_income_payment_type = 'Kg' THEN SUM(price * 37) ELSE SUM(price)
        END AS `Total Fuel Value`
        FROM
            expenses_incomes
        GROUP BY
            expenses_income_type,
            expenses_income_payment_type,
            category
    HAVING category IN ('Fuels', 'Combustivel')
        ";
		$result = $this->results();
		$total = 0;
		foreach ($result as $value) {
			$total += $value['Total Fuel Value'];
		}
		return $total;
	}

	public function TotalOtherCost() //Total Miscellaneous Expenses Calculator
	{
		$this->query = "
        SELECT CASE WHEN
            expenses_income_payment_type = 'Kg' THEN SUM(price * 37) ELSE SUM(price)
        END AS `Total Other Value`
        FROM
            expenses_incomes
        GROUP BY
            expenses_income_type,
            expenses_income_payment_type,
            category
    HAVING category IN ('Compra de Equipamento', 'Equipment acquisition', 'Equipment maintenance','Manutenção de Equipamento')
        ";
		$result = $this->results();
		$total = 0;
		foreach ($result as $value) {
			$total += $value['Total Other Value'];
		}
		return $total;
	}

	public function TotalEmployeeCost() //Total Employee Expenses Calculator
	{
		$this->query = "
        SELECT CASE WHEN
            expenses_income_payment_type = 'Kg' THEN SUM(price * 37) ELSE SUM(price)
        END AS `Total Employee Value`
        FROM
            expenses_incomes
        GROUP BY
            expenses_income_type,
            expenses_income_payment_type,
            category
    HAVING category IN ('Trabalhadores', 'Employees')
        ";
		$result = $this->results();
		$total = 0;
		foreach ($result as $value) {
			$total += $value['Total Employee Value'];
		}
		return $total;
	}

	public function TotalIncome() //Total Revenue Calculator
	{
		return ($this->cashCollected() + ($this->rcnCollected() * 37));
	}


	public function TotalChemicalCost() //Total Chemical Cost Calculator
	{
		$result = $this->select("chemical_acquisition chq")->columns("
		SUM(chq.chemical_price) AS total")->result();

		return $result["total"];
	}

	public function genderDistribution() //Total Chemical Cost Calculator
	{
		$result = $this->select("farmers")->columns("SUM(CASE WHEN gender='Female' THEN 1 ELSE 0 END) as totalFemale, SUM(CASE WHEN gender='Male' THEN 1 ELSE 0 END) as totalMale")->where("deleted_at IS NULL")->result();

		return array($result["totalFemale"], $result["totalMale"]);
	}

	public function numberOfTreesSprayedPerApplication()
	{
		$numberOfTreesSprayedPerApplication = $this->select("chemical_application ca")->columns("
			SUM(ca.number_of_trees_sprayed) AS metrics,
			CASE WHEN ca.application_number = 1 THEN '1st application' 
			WHEN ca.application_number = 2 THEN '2nd application' 
			WHEN ca.application_number = 3 THEN '3rd application' ELSE '' END AS 'dimensions'
		")->join("farmers f ON f.farmer_uid = ca.farmer_uid")->where($this->filter('f'))->group("ca.application_number")->results();

		$metrics = array_column($numberOfTreesSprayedPerApplication, "metrics");
		$metrics = implode(",", $metrics);

		$dimensions = array_column($numberOfTreesSprayedPerApplication, "dimensions");
		$dimensions = "'" . implode("', '", $dimensions) . "'";

		return array(
			"metrics" => $metrics,
			"dimensions" => $dimensions
		);
	}

	private function baseSelectsprayers($filterCondition, $filterHaveCondition)
	{
		return $this->select($this->table . " " . Alias::sprayers)
			->columns("

                        SQL_CALC_FOUND_ROWS

                        " . Alias::sprayers . ".id,
                        CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name) AS name,
                        " . Alias::sprayers . ".gender,
                        " . Alias::sprayers . ".mobile_number,
                        " . Alias::sprayers . ".province,
                        " . Alias::sprayers . ".district,
                        " . Alias::sprayers . ".last_sync_at,
						
						(SELECT SUM(number_of_trees_sprayed) FROM chemical_application WHERE user_uid = " . Alias::sprayers . ".user_uid AND application_number = 1) AS first_application,
						
						(SELECT SUM(number_of_trees_sprayed) FROM chemical_application WHERE user_uid = " . Alias::sprayers . ".user_uid AND application_number = 2) AS second_application,
						
						(SELECT SUM(number_of_trees_sprayed) FROM chemical_application WHERE user_uid = " . Alias::sprayers . ".user_uid AND application_number = 3) AS third_application,
						
						(SELECT SUM(number_of_trees_sprayed) FROM chemical_application WHERE user_uid = " . Alias::sprayers . ".user_uid) AS TreesSprayed,
						
						(SELECT SUM(chemical_price) FROM chemical_acquisition WHERE user_uid = " . Alias::sprayers . ".user_uid) AS chemical_cost,
						
						(SELECT COUNT(*) FROM equipments WHERE user_uid = " . Alias::sprayers . ".user_uid) AS equipments_count,
						
						(SELECT COUNT(*) FROM farmers WHERE farmer_uid IN (SELECT farmer_uid FROM chemical_application) AND user_uid = " . Alias::sprayers . ".user_uid) AS farmersAssisted,
						(SELECT SUM(paid) FROM payments WHERE payment_type IN ('Kg') AND user_uid = " . Alias::sprayers . ".user_uid) AS rcn_collected,
						(SELECT SUM(paid) FROM payments WHERE payment_type IN ('Mzn') AND user_uid = " . Alias::sprayers . ".user_uid) AS cash_collected,
                        (SELECT SUM(price) FROM expenses_incomes WHERE category IN ('Fuels', 'Combustivel') AND expenses_income_payment_type IN ('Mzn') AND user_uid = " . Alias::sprayers . ".user_uid) AS expense_fuel_mzn,
                        (SELECT SUM(price*37) FROM expenses_incomes WHERE category IN ('Fuels', 'Combustivel')AND expenses_income_payment_type IN ('Kg') AND user_uid = " . Alias::sprayers . ".user_uid) AS expense_fuel_kg,
                        (SELECT SUM(price) FROM expenses_incomes WHERE category IN ('Employees', 'Trabalhadores') AND expenses_income_payment_type IN ('Mzn') AND user_uid = " . Alias::sprayers . ".user_uid) AS expense_workers_mzn,
                        (SELECT SUM(price*37) FROM expenses_incomes WHERE category IN ('Employees', 'Trabalhadores') AND expenses_income_payment_type IN ('Kg') AND user_uid = " . Alias::sprayers . ".user_uid) AS expense_workers_kg,
                        (SELECT SUM(price*37) FROM expenses_incomes WHERE expenses_income_type IN ('Despesa', 'Expense') AND category NOT IN ('Employees', 'Fuels', 'Trabalhadores', 'Combustivel') AND expenses_income_payment_type IN ('Kg')  AND user_uid = " . Alias::sprayers . ".user_uid) AS other_costs_kg,
                        (SELECT SUM(price) FROM expenses_incomes WHERE expenses_income_type IN ('Despesa', 'Expense') AND category NOT IN ('Employees', 'Fuels', 'Trabalhadores', 'Combustivel') AND expenses_income_payment_type IN ('Mzn') AND user_uid = " . Alias::sprayers . ".user_uid) AS other_costs_mzn
                ")
			->where("

                   ( 

                       {$this->filter(Alias::sprayers)}

                   )

                {$filterCondition}

            ")
			->have("

                (

                    " . Alias::sprayers . ".id > 0

                )

                {$filterHaveCondition}

            ");
	}

	public function getListOfRegisteredSprayers()
	{

		$post = $_POST;

		$columnsFilter = array(
			array('db' => "CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
			array('db' => Alias::sprayers . ".gender", 'dt' => 1),
			array('db' => Alias::sprayers . ".mobile_number", 'dt' => 2),
			array('db' => Alias::sprayers . ".district", 'dt' => 3),
			array('db' => Alias::sprayers . ".district", 'dt' => 4),
			array('db' => Alias::sprayers . ".province", 'dt' => 5),
		);

		$columnsOrder = array(
			array('db' => "CONCAT(" . Alias::sprayers . ".first_name, ' '," . Alias::sprayers . ".last_name)", 'dt' => 0),
			array('db' => Alias::sprayers . ".gender", 'dt' => 1),
			array('db' => Alias::sprayers . ".mobile_number", 'dt' => 2),
			array('db' => Alias::sprayers . ".district", 'dt' => 3),
			array('db' => Alias::sprayers . ".district", 'dt' => 4),
			array('db' => Alias::sprayers . ".province", 'dt' => 5),
		);

		$filterCondition = DataTable::filterDt($post, $columnsFilter);

		$filterHaveCondition = "";

		if (isset($_POST["sql"]) && !empty($_POST["sql"])) {

			$filterHaveCondition = " AND (" . $_POST["sql"] . ")";
		}

		$order = ($post["order"][0]["column"] == -1) ? Alias::sprayers . ".id DESC" : DataTable::orderDt($post, $columnsOrder);

		$resultset = $this->baseSelectSprayers($filterCondition, $filterHaveCondition)
			->order($order)
			->limit($post["length"])
			->offset($post["start"])
			->results();

		$this->query = "SELECT FOUND_ROWS() AS totalRecords";

		$totalRecords = $this->result("totalRecords");

		$data = array();

		foreach ($resultset as $value) :

			$output = array();

			$output[] = $value["name"];
			$output[] = $value["gender"];
			$output[] = $value["mobile_number"];
			$output[] = $value["district"];
			$output[] = $value["district"];
			$output[] = $value["province"];
			$output[] = $value["equipments_count"] ?? 0;
			$output[] = $value["first_application"] ?? 0;
			$output[] = $value["second_application"] ?? 0;
			$output[] = $value["third_application"] ?? 0;
			$output[] = $value["farmersAssisted"];
			$output[] = $value["rcn_collected"];
			$output[] = $value["cash_collected"];
			$output[] = $value["expense_fuel_kg"] + $value["expense_fuel_mzn"];
			$output[] = $value["expense_workers_kg"] + $value["expense_workers_mzn"];
			$output[] = $value["chemical_cost"];
			$output[] = $value["other_costs_kg"] + $value["other_costs_mzn"];
			$output[] = $value["last_sync_at"];
			$data[] = $output;

		endforeach;

		echo json_encode(
			array(
				"draw" => isset($post['draw']) ? intval($post["draw"]) : 0,
				"recordsTotal" => count($resultset),
				"recordsFiltered" => $totalRecords,
				"data" => $data,
			)
		);
	}
}
