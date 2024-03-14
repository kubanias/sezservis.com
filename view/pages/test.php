<?php if (!defined('B2')) exit(0);
/**
 *  Файл для тестирования, на релизе удалить.
 */

__use('doc');

exit(0);
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('60', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '60')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('62', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '62')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('70', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '70')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('71', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '71')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('75', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '75')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('76', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '76')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('78', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '78')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('79.01', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '79.01')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('79.02', '00')['id']];
	$operation_type_relations[] = ['doc_type' => 'NETTING', 'operation_activity_id' => null, 'operation_status_id' => null,
		'operation_type_id' => operation_type::get_by_accounts('00', '79.02')['id']];

	foreach ($operation_type_relations as $key => $item) {
		page_log_same_line('Seeding ' . $key . ' of ' . count($operation_type_relations));

		$sql = "INSERT INTO `operation_type_relation` (`doc_type`, `forward_type`, `operation_activity_id`, `operation_status_id`, " .
			"`operation_type_id`, `operation_type_id_secondary`) VALUES (" .
			"'" . db::escape($item['doc_type']) . "', " .
			((!isset($item['forward_type']) || $item['forward_type'] === null) ? "'NONE'" : ("'" . db::escape($item['forward_type']) . "'")) . ", " .
			($item['operation_activity_id'] === null ? 'NULL' : intval($item['operation_activity_id'])) . ", " .
			($item['operation_status_id'] === null ? 'NULL' : intval($item['operation_status_id'])) . ", " .
			($item['operation_type_id'] === null ? 'NULL' : intval($item['operation_type_id'])) . ", " .
			((!isset($item['operation_type_id_secondary']) || $item['operation_type_id_secondary'] === null) ?
				'NULL' : intval($item['operation_type_id_secondary'])) . " " .
		");";
		if (!db::query($sql)) return page_transaction_error($sql . ' Unable to add operation type relation ' . $item['name']);
	}

exit(0);
//----------------------------------------------------------------- LOG -----------------------------------------------------------------------------
function page_log(string $message, bool $return_value = true) : bool {
	echo $message . "\n";
	if ($return_value === false) readline('Press ENTER to quit');
	return $return_value;
}

//------------------------------------------------------------ LOG SAME LINE ------------------------------------------------------------------------
function page_log_same_line(string $message, bool $return_value = true) : bool {
	echo $message . "\r";
	return $return_value;
}

//---------------------------------------------------------- TRANSACTION ERROR ----------------------------------------------------------------------
function page_transaction_error(string $message) : bool {
	echo $message . "\n";
	db::rollback();
	readline('Press ENTER to quit');
	return false;
}

//---------------------------------------------------------------- ACCOUNTS -------------------------------------------------------------------------
function page_accounts() : bool {
	page_log('Seeding accounts');

	db::start_transaction();

	if (!db::query("DELETE FROM `account2`;")) return page_transaction_error('Unable to clear `account2` table');
	if (!db::query("ALTER TABLE `account2` AUTO_INCREMENT = 1;")) return page_transaction_error('Unable to reset `account2` auto increment index');

	// Accounts data
	$accounts = [];
	$accounts[] = ['number' => '00', 'name' => 'Account for initial balances', 'properties_json' => '{}'];
	$accounts[] = ['number' => '20', 'name' => 'Apportioned expenses',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_expense') . ']}'];
	$accounts[] = ['number' => '23', 'name' => 'Redirection expenses to intragroup',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_expense') . ']}'];
	$accounts[] = ['number' => '24', 'name' => 'Redirection expenses to counterparties',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_expense') . ']}'];
	$accounts[] = ['number' => '25', 'name' => 'Debatable expenses',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_expense') . ']}'];
	$accounts[] = ['number' => '26', 'name' => 'General expenses',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_expense') . ']}'];
	$accounts[] = ['number' => '51', 'name' => 'Bank/cash accounts', 'properties_json' =>
		'{"subconto":[' . page_get_subconto_json('bank_account') . ',' .
		page_get_subconto_json('operation_cash_flow') . ', ' .
		page_get_subconto_json('doc_unpaid') . ']}'];
	$accounts[] = ['number' => '57', 'name' => 'Suspense account', 'properties_json' => '{}'];
	$accounts[] = ['number' => '60', 'name' => 'Suppliers settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_supplier') . ']}'];
	$accounts[] = ['number' => '62', 'name' => 'Clients settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_client') . ']}'];
	$accounts[] = ['number' => '66', 'name' => 'Lenders and borrowers settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_borrower') . ']}'];
	$accounts[] = ['number' => '66.1', 'name' => 'Loans settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_borrower') . ']}'];
	$accounts[] = ['number' => '66.2', 'name' => 'Interests settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_borrower') . ']}'];
	$accounts[] = ['number' => '68', 'name' => 'Taxes and fees settlements', 'properties_json' => '{}'];
	$accounts[] = ['number' => '68.1', 'name' => 'Income tax from salary', 'properties_json' => '{"subconto":[' . page_get_subconto_json('doc_unpaid') . ',' .
		page_get_subconto_json('entity') . ']}'];
	$accounts[] = ['number' => '68.2', 'name' => 'VAT', 'properties_json' => '{"subconto":[' . page_get_subconto_json('doc_unpaid') . ',' .
		page_get_subconto_json('entity') . ']}'];
	$accounts[] = ['number' => '68.4', 'name' => 'Income tax', 'properties_json' => '{"subconto":[' . page_get_subconto_json('doc_unpaid') . ',' .
		page_get_subconto_json('entity') . ']}'];
	$accounts[] = ['number' => '68.5', 'name' => 'Social tax', 'properties_json' => '{"subconto":[' . page_get_subconto_json('doc_unpaid') . ',' .
		page_get_subconto_json('entity') . ']}'];
	$accounts[] = ['number' => '68.6', 'name' => 'Administrative fines and penalties', 'properties_json' => '{"subconto":[' . page_get_subconto_json('doc_unpaid') . ',' .
		page_get_subconto_json('entity') . ']}'];
	$accounts[] = ['number' => '70', 'name' => 'Employees salary & extra settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_employee') . ']}'];
	$accounts[] = ['number' => '71', 'name' => 'Employees other operations settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_employee') . ']}'];
	$accounts[] = ['number' => '75', 'name' => 'Owners settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_stockholder') . ']}'];
	$accounts[] = ['number' => '76', 'name' => 'FO counterparties settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_financial') . ',' .
		page_get_subconto_json('project') . ']}'];
	$accounts[] = ['number' => '77', 'name' => 'Technical settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity') . ']}'];
	$accounts[] = ['number' => '79', 'name' => 'Intragroup legal entities settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_intragroup') . ']}'];
	$accounts[] = ['number' => '79.01', 'name' => 'Intragroup settlements for payments',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_intragroup') . ']}'];
	$accounts[] = ['number' => '79.02', 'name' => 'Intragroup settlements for services',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_intragroup') . ']}'];
	$accounts[] = ['number' => '80', 'name' => 'Share capital', 'properties_json' => '{}'];
	$accounts[] = ['number' => '84', 'name' => 'Retained earnings', 'properties_json' => '{}'];
	$accounts[] = ['number' => '90', 'name' => 'Sales', 'properties_json' => '{}'];
	$accounts[] = ['number' => '90.01', 'name' => 'Sales charter flight brokerage',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_income') . ']}'];
	$accounts[] = ['number' => '90.02', 'name' => 'Sales concierge service',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_income') . ']}'];
	$accounts[] = ['number' => '90.03', 'name' => 'Sales attracting clients',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ']}'];
	$accounts[] = ['number' => '90.04', 'name' => 'Incomes aircraft management',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_aircraft_management') . ']}'];
	$accounts[] = ['number' => '90.05', 'name' => 'Incomes sale of aircraft',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_income') . ']}'];
	$accounts[] = ['number' => '90.06', 'name' => 'Sales advertising activities',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_income') . ']}'];
	$accounts[] = ['number' => '91', 'name' => 'Cost', 'properties_json' => '{}'];
	$accounts[] = ['number' => '91.01', 'name' => 'Cost charter flight brokerage',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '91.02', 'name' => 'Cost concierge service',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('operation_context_concierge') . ']}'];
	$accounts[] = ['number' => '91.03', 'name' => 'Cost attracting clients',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ']}'];
	$accounts[] = ['number' => '91.04', 'name' => 'Cost aircraft management',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '91.05', 'name' => 'Cost sale of aircraft',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_cost') . ']}'];
	$accounts[] = ['number' => '91.06', 'name' => 'Cost advertising activities',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_cost') . ']}'];
	$accounts[] = ['number' => '91.10', 'name' => 'Other costs',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_income_cost') . ']}'];
	$accounts[] = ['number' => '92', 'name' => 'Other incomes and costs', 'properties_json' => '{}'];
	$accounts[] = ['number' => '92.07', 'name' => 'Incomes and costs from FO',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ']}'];
	$accounts[] = ['number' => '92.08', 'name' => 'Incomes and costs from exchange rate differences', 'properties_json' => '{}'];
	$accounts[] = ['number' => '92.09', 'name' => 'Incomes and costs from exchange rate differences', 'properties_json' => '{}'];
	$accounts[] = ['number' => '92.10', 'name' => 'Incomes and costs from other operations',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_income_cost') . ']}'];
	$accounts[] = ['number' => '93', 'name' => 'Redirection cost to intragroup', 'properties_json' => '{}'];
	$accounts[] = ['number' => '93.01', 'name' => 'Redirection cost charter flight brokerage',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '93.04', 'name' => 'Redirection cost aircraft management',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_cost') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '94', 'name' => 'Redirection cost to client', 'properties_json' => '{}'];
	$accounts[] = ['number' => '94.01', 'name' => 'Redirection cost to client charter flight brokerage',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '94.04', 'name' => 'Redirection cost to client aircraft management',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_income_cost') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '95', 'name' => 'Debatable cost', 'properties_json' => '{}'];
	$accounts[] = ['number' => '95.01', 'name' => 'Debatable cost charter flight brokerage',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '95.02', 'name' => 'Debatable cost concierge service',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('operation_context_concierge') . ']}'];
	$accounts[] = ['number' => '95.04', 'name' => 'Debatable cost aircraft management',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_cost') . ',' .
		page_get_subconto_json('operation_context') . ']}'];
	$accounts[] = ['number' => '96', 'name' => 'Services rendered, but no invoice issued', 'properties_json' => '{}'];
	$accounts[] = ['number' => '96.04', 'name' => 'Services rendered, aircraft management',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ',' .
		page_get_subconto_json('operation_context_aircraft_management') . ']}'];
	$accounts[] = ['number' => '97', 'name' => 'Deferred expense',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('operation_context_expense') . ']}'];
	$accounts[] = ['number' => '99', 'name' => 'Profits and losses', 'properties_json' => '{}'];
	$accounts[] = ['number' => '99.01', 'name' => 'Financial result from charter flight brokerage',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ',' .
		page_get_subconto_json('aircraft') . ']}'];
	$accounts[] = ['number' => '99.02', 'name' => 'Financial result from concierge service',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ']}'];
	$accounts[] = ['number' => '99.03', 'name' => 'Financial result from attracting clients',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ']}'];
	$accounts[] = ['number' => '99.04', 'name' => 'Financial result from aircraft management',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ']}'];
	$accounts[] = ['number' => '99.05', 'name' => 'Financial result from sale of aircraft',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('aircraft') . ']}'];
	$accounts[] = ['number' => '99.06', 'name' => 'Financial result advertising activities', 'properties_json' => '{}'];
	$accounts[] = ['number' => '99.07', 'name' => 'Financial result from FO',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('project') . ']}'];
	$accounts[] = ['number' => '99.08', 'name' => 'Financial result from exchange rate differences', 'properties_json' => '{}'];
	$accounts[] = ['number' => '99.09', 'name' => 'Financial result  from asset and liability revaluation', 'properties_json' => '{}'];
	$accounts[] = ['number' => '99.10', 'name' => 'Financial result  from other operations', 'properties_json' => '{}'];
	$accounts[] = ['number' => '100500.1', 'name' => 'Legacy income', 'properties_json' => '{}'];
	$accounts[] = ['number' => '100500.2', 'name' => 'Legacy outcome', 'properties_json' => '{}'];
	$accounts[] = ['number' => '78', 'name' => 'Department settlements',
		'properties_json' => '{"subconto":[' . page_get_subconto_json('entity_department') . ']}'];

	// Check JSON
	foreach ($accounts as $key => $item) {
		if ($item['properties_json'] == '{}') continue;
		if (json_decode($item['properties_json']) === null) return page_transaction_error('Unable to parse account JSON properties of ' . $item['number']);
	}

	// Add accounts
	foreach ($accounts as $key => $item) {
		page_log_same_line('Seeding ' . $key . ' of ' . count($accounts));

		$sql = "INSERT INTO `account2` (`number`, `name`, `name_ru`, `properties_json`) VALUES (" .
			"'" . db::escape($item['number']) . "', " .
			"'" . db::escape($item['name']) . "', " .
			"'" . db::escape($item['name']) . "', " .
			($item['properties_json'] === null ? 'NULL' : ("'" . db::escape($item['properties_json']) . "'")) .
			");";
		if (!db::query($sql)) return page_transaction_error('Unable to add account ' . $item['number']);
	}

	if (!db::commit()) return page_transaction_error('Unable to commit accounts transaction');

	page_log(count($accounts) . ' accounts successfully seeded');

	return true;
}

//------------------------------------------------------------- GET SUBCONTO JSON -------------------------------------------------------------------
function page_get_subconto_json($subconto_name) : string {
	$subconto_json = [
		'operation_context' => '{"name":"operation_context","field":{"debit":"operation_context_id","credit":"operation_context_id"},' .
			'"name_key":"account.income_type","type":"context_dynamic_list",' .
			'"directory":"operation_contexts","select":"name","filter":{"is_tip":1,"is_active":1}}',
		'operation_context_expense' => '{"name":"expense_type_general","field":{"debit":"operation_context_id","credit":"operation_context_id"},' .
			'"name_key":"account.expense_type","type":"context_dynamic_list",' .
			'"directory":"operation_contexts","select":"name","is_required":true,"filter":{"is_tip":1,"is_expense":1,"is_active":1}}',
		'bank_account' => '{"name":"bank_account","field":{"debit":"bank_account_id_debit","credit":"bank_account_id_credit"},' .
			'"name_key":"account.bank_account","directory":"bank_accounts","select":"name"}',
		'operation_cash_flow' => '{"name":"operation_cash_flow","field":{"debit":"operation_cash_flow_id","credit":"operation_cash_flow_id"},' .
			'"name_key":"account.cash_flow_type",' .
			'"directory":"operation_cash_flows","select":"name"}',
		'entity' => '{"name":"entity_supplier","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_active":1}}',
		'entity_supplier' => '{"name":"entity_supplier","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_supplier":1,"is_active":1}}',
		'entity_client' => '{"name":"entity_client","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_client":1,"is_active":1}}',
		'entity_borrower' => '{"name":"entity_borrower","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_borrower":1,"is_active":1}}',
		'entity_employee' => '{"name":"entity_employee","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_internal":1,"is_legal_entity":0,"is_active":1}}',
		'entity_stockholder' => '{"name":"entity_stockholder","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_stockholder":1,"is_active":1}}',
		'entity_financial' => '{"name":"entity_financial","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_financial":1,"is_active":1}}',
		'entity_intragroup' => '{"name":"entity_intragroup","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"is_internal":1,"is_legal_entity":1,"is_active":1}}',
		'entity_department' => '{"name":"entity_department","field":{"debit":"entity_id_counterparty","credit":"entity_id_counterparty"},' .
			'"name_key":"account.counterparty","directory":"entities","select":"name","filter":{"tenant_associated_id[not]":null,"is_active":1}}',
		'project' => '{"name":"project","field":{"debit":"project_id","credit":"project_id"},' .
			'"name_key":"account.project","directory":"projects","select":"id"}',
		'aircraft' => '{"name":"aircraft","field":{"debit":"aircraft_id","credit":"aircraft_id"},' .
			'"name_key":"account.aircraft","directory":"aircrafts","select":"tail_number","filter":{"is_active":1}}',
		'operation_context_income' => '{"name":"operation_context_income","field":{"debit":"operation_context_id","credit":"operation_context_id"},' .
			'"name_key":"account.income_type","type":"context_dynamic_list",' .
			'"directory":"operation_contexts","select":"name","filter":{"is_tip":1,"is_income":1,"is_active":1}}',
		'operation_context_cost' => '{"name":"operation_context_cost","field":{"debit":"operation_context_id","credit":"operation_context_id"},' .
			'"name_key":"account.cost_type","type":"context_dynamic_list",' .
			'"directory":"operation_contexts","select":"name","filter":{"is_tip":1,"is_cost":1,"is_active":1}}',
		'operation_context_income_cost' => '{"name":"operation_context_income_cost","field":{"debit":"operation_context_id","credit":"operation_context_id"},' .
			'"name_key":"account.cost_type","type":"context_dynamic_list",' .
			'"directory":"operation_contexts","select":"name","filter":{"is_tip":1,"is_income_cost":1,"is_active":1}}',
		'operation_context_aircraft_management' => '{"name":"operation_context_aircraft_management","field":{"debit":"operation_context_id","credit":"operation_context_id"},' .
			'"name_key":"account.cost_type","type":"context_dynamic_list",' .
			'"directory":"operation_contexts","select":"name","filter":{"is_tip":1,"is_aircraft_management":1,"is_active":1}}',
		'operation_context_concierge' => '{"name":"operation_context_aircraft_management","field":{"debit":"operation_context_id","credit":"operation_context_id"},' .
			'"name_key":"account.cost_type","type":"context_dynamic_list",' .
			'"directory":"operation_contexts","select":"name","filter":{"is_tip":1,"is_concierge":1,"is_active":1}}',
		'doc_unpaid' => '{"type":"doc_unpaid_dynamic_list","name":"doc_unpaid","field":{"debit":"doc_id_associated","credit":"doc_id_associated"},"name_key":"account.doc_unpaid",' .
			'"directory":"docs","select":"number","is_required":false,' .
			'"filter":{"entity_id":"%entity_id%","entity_id_counterparty":"%entity_id_counterparty%","extended":"unpaid_with_operations","currency_id":"%currency_id%"},' .
			'"filter_incoming_payment":{"type":"outgoing_invoice,services_rendered"},"filter_outgoing_payment":{"type":"incoming_invoice,advance_report,payroll_first,payroll_second,tax"}}',

		// CONTEXTS SUBCONTO
		'context_entity_internal_not_legal_entity' => '{"name":"context_entity_internal_not_legal_entity","field":{"debit":"context_entity_id","credit":"context_entity_id"},' .
			'"name_key":"operation.entity_employee","type":"dynamic_list",' .
			'"directory":"entities","select":"name","filter":{"is_internal":1,"is_legal_entity":0,"is_active":1}}',
		'start_date' => '{"name":"start_date","field":{"debit":"context_start_date","credit":"context_start_date"},"name_key":"operation.start_date","type":"date"}',
		'end_date' => '{"name":"end_date","field":{"debit":"context_end_date","credit":"context_end_date"},"name_key":"operation.end_date","type":"date"}',
		'airport_departure' => '{"name":"airport_departure","field":{"debit":"context_airport_id_departure","credit":"context_airport_id_departure"},' .
			'"name_key":"operation.airport_departure","type":"airport_dynamic_list",' .
			'"directory":"airports","select":"icao"}',
		'airport_arrival' => '{"name":"airport_arrival","field":{"debit":"context_airport_id_arrival","credit":"context_airport_id_arrival"},' .
			'"name_key":"operation.airport_arrival","type":"airport_dynamic_list",' .
			'"directory":"airports","select":"icao"}',
		'context_date' => '{"name":"context_date","field":{"debit":"context_date","credit":"context_date"},"name_key":"operation.context_date","type":"date"}',
		'project_leg' => '{"name":"project_leg","field":{"debit":"project_leg_id","credit":"project_leg_id"},"name_key":"operation.project_leg_id",' .
			'"type":"leg_dynamic_list","directory":"project_legs","select":"complex_leg_info"}'
	];

	if (!isset($subconto_json[$subconto_name])) {
		page_log('Unable to get subconto ' . $subconto_name);
		readline('ERROR! Press ENTER to quit');
		exit(1);
	}

	return $subconto_json[$subconto_name];
}

//---------------------------------------------------------------- IMPORT CONTEXTS ----------------------------------------------------------------
function page_import_contexts() {
	$contexts = file_get_contents('n:/4/contexts.txt');
	$context_lines = explode("\r\n", $contexts);
	$context_rows = [];
	unset($context_lines[0]);
	foreach ($context_lines as $item)
		$context_rows[] = explode("\t", $item);

	// Form contexts tree
	$contexts_tree = [];
	foreach ($context_rows as $item) {
		if (count($item) == 1 || $item[0] == '') continue;
		if (!isset($contexts_tree[$item[0]])) $contexts_tree[$item[0]] = [];
		if ($item[1] == '') continue;
		if (!isset($contexts_tree[$item[0]][$item[1]])) $contexts_tree[$item[0]][$item[1]] = [];
		if ($item[2] == '') continue;

		$contexts_tree[$item[0]][$item[1]][] = $item;
	}

	// Insert contexts
	foreach ($contexts_tree as $key_1 => $level_1) {
		$sql = "INSERT INTO `operation_context` (`name`, `is_active`) VALUES (" .
			"'" . db::escape(trim($key_1)) . "', " .
			"1" .
			");";
		db::query($sql);
		$level_1_id = db::last_insert_id();
		foreach ($level_1 as $key_2 => $level_2) {
			$sql = "INSERT INTO `operation_context` (`parent_id`, `name`, `is_active`) VALUES (" .
				intval($level_1_id) . ", " .
				"'" . db::escape(trim($key_2)) . "', " .
				"1" .
				");";
			db::query($sql);
			$level_2_id = db::last_insert_id();
			foreach ($level_2 as $key_3 => $level_3) {
				$sql = "INSERT INTO `operation_context` (`parent_id`, `name`, `desc`, " .
					"`is_income`, `is_cost`, `is_expense`, `is_fixed`, `is_concierge`, `is_charter`, `is_aircraft_management`, " .
					"`is_advertisement`, `is_other`, `is_aircraft_sale`, `is_attracting_clients`, `is_financial`, `is_active`) VALUES (" .
					intval($level_2_id) . ", " .
					"'" . db::escape(trim($level_3[2])) . "', " .
					"'" . ($level_3[3] == '' ? null : db::escape((mb_strtoupper(mb_substr($level_3[3], 0, 1, 'utf8')) .  mb_substr($level_3[3], 1, null, 'utf8')))) . "', " .
					($level_3[4] == '' ? 0 : 1) . ", " .
					($level_3[5] == '' ? 0 : 1) . ", " .
					($level_3[6] == '' ? 0 : 1) . ", " .
					($level_3[7] == '' ? 0 : 1) . ", " .
					($level_3[9] == '' ? 0 : 1) . ", " .
					($level_3[10] == '' ? 0 : 1) . ", " .
					($level_3[11] == '' ? 0 : 1) . ", " .
					($level_3[12] == '' ? 0 : 1) . ", " .
					($level_3[13] == '' ? 0 : 1) . ", " .
					($level_3[14] == '' ? 0 : 1) . ", " .
					($level_3[15] == '' ? 0 : 1) . ", " .
					($level_3[19] == '' ? 0 : 1) . ", " .
					($level_3[20] == '' ? 0 : 1) .
					");";
				db::query($sql);
			}
		}
	}
}