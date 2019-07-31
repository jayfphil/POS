BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `transaction_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`user_id`	INTEGER NOT NULL DEFAULT 0,
	`cash_tender`	TEXT NOT NULL,
	`total_amt`	TEXT NOT NULL,
	"total_amtdisc"	TEXT NOT NULL,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`last_updated`	datetime,
	`tt_voided`	tinyint DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS `transactionitems_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`transact_id`	INTEGER NOT NULL DEFAULT 0,
	`product_id`	INTEGER NOT NULL DEFAULT 0,
	`quantity`	INTEGER NOT NULL DEFAULT 0,
	`price`	TEXT NOT NULL,
	`addons_metajson`	TEXT NOT NULL,
	`sugar_level`	TEXT NOT NULL,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`last_updated`	datetime,
	`ti_voided`	tinyint DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS `product_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`product_name`	TEXT NOT NULL,
	`price`	TEXT NULL,
	`quantity`	INTEGER NULL,
	`category_id`	INTEGER NOT NULL DEFAULT 0,
	`user_id`	INTEGER NOT NULL DEFAULT 0,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`last_updated`	datetime,
	`pt_deleted`	tinyint DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS `ingredients_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`ingredients_name`	TEXT NOT NULL,
	`quantity`	INTEGER NULL,
	`cups_serving`	INTEGER NULL,
	`measurement_type`	TEXT NULL,
	`products`	TEXT NULL,
	`user_id`	INTEGER NOT NULL DEFAULT 0,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`last_updated`	datetime,
	`it_deleted`	tinyint DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS `category_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`category_name`	TEXT NOT NULL,
	`user_id`	INTEGER NOT NULL DEFAULT 0,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`last_updated`	datetime,
	`ct_deleted`	tinyint DEFAULT NULL
);
CREATE TABLE `codelibrary_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`code_name`	TEXT NOT NULL,
	`code_description`	TEXT NOT NULL,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`last_updated`	datetime,
	`cl_deleted`	tinyint DEFAULT NULL
);
CREATE TABLE "productcode_tb" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	"codelibrary_id"	INTEGER NOT NULL DEFAULT 0,
	"product_id"	INTEGER NOT NULL DEFAULT 0,
	"date_created"	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"pc_deleted"	tinyint DEFAULT NULL
);
CREATE TABLE "users_tb" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	"username"	TEXT NOT NULL,
	"fullname"	TEXT NOT NULL,
	"password"	TEXT NOT NULL,
	"date_created"	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"last_updated"	datetime,
	"ut_active"	tinyint DEFAULT NULL
);
CREATE TABLE "usersrole_tb" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	"user_id"	INTEGER NOT NULL DEFAULT 0,
	"userlevel_id"	INTEGER NOT NULL DEFAULT 0,
	"date_created"	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"ur_deleted"	tinyint DEFAULT NULL
);
CREATE TABLE `userslevel_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`level_name`	TEXT NOT NULL,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`last_updated`	datetime,
	`ul_deleted`	tinyint DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS `branch_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`branch_name`	TEXT NOT NULL,
	`branch_city`	TEXT NOT NULL,
	`branch_region`	TEXT NOT NULL,
	`branch_areacode`	TEXT NOT NULL,
	`branch_postalcode`	TEXT NOT NULL,
	`branch_country`	TEXT NOT NULL,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `sales_management` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`branch`	TEXT NOT NULL,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`cashier1`	TEXT NOT NULL,
	`cashier2`	TEXT NOT NULL,
	`released_cups`	TEXT NOT NULL,
	`cups_income`	TEXT NOT NULL,
	`snacks_alacarte`	TEXT NOT NULL,
	`sold_cups`	TEXT NOT NULL,
	`snacks_unlimited`	TEXT NOT NULL,
	`rejected_cups`	TEXT NOT NULL,
	`cakes_income`	TEXT NOT NULL,
	`missing_cups`	TEXT NOT NULL,
	`merchandises`	TEXT NOT NULL,
	`compli_cups`	TEXT NOT NULL,
	`total_cups`	TEXT NOT NULL,
	`witness1`	TEXT NOT NULL,
	`witness2`	TEXT NOT NULL,
	`total_income`	TEXT NOT NULL,
	`groceries`	TEXT NOT NULL,
	`drinking_water`	TEXT NOT NULL,
	`general_merchandise`	TEXT NOT NULL,
	`iced`	TEXT NOT NULL,
	`discounts`	TEXT NOT NULL,
	`liquefied_gas`	TEXT NOT NULL,
	`communication`	TEXT NOT NULL,
	`total_expenses`	TEXT NOT NULL,
	`stocks_deliveries`	TEXT NOT NULL,
	`net_income`	TEXT NOT NULL,
	`grand_total`	TEXT NOT NULL,
	`audited_by`	TEXT NOT NULL,
	"print_flag"	tinyint DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS `sales_monthly_report_summary` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`month`	TEXT NOT NULL,
	`year`	TEXT NOT NULL,
	`team_leader`	TEXT NOT NULL,
	`total_gross_income`	TEXT NOT NULL,
	`space_rental`	TEXT NOT NULL,
	`total_ops_expenses`	TEXT NOT NULL,
	`payroll_fifth`	TEXT NOT NULL,
	`payroll_twenty`	TEXT NOT NULL,
	`total_net_income`	TEXT NOT NULL,
	`electric_bill`	TEXT NOT NULL,
	`total_bank_deposits`	TEXT NOT NULL,
	`water_bill`	TEXT NOT NULL,
	`taxes`	TEXT NOT NULL,
	`balance`	TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS `sales_monthly_report` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`sales_monthly_report_id`	INTEGER NOT NULL DEFAULT 0,
	`gross_income`	TEXT NOT NULL,
	`expenses`	TEXT NOT NULL,
	`net_income`	TEXT NOT NULL,
	`bank_deposit_slip`	TEXT NOT NULL,
	`remarks`	TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS `auditlog_tb` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`user_id`	INTEGER,
	`log_description`	TEXT NOT NULL,
	`log_sources`	TEXT NOT NULL,
	`date_created`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `units_tb` (
	`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`unit_name`	TEXT NOT NULL,
	`unit_code`	TEXT NOT NULL
);
CREATE TRIGGER [UpdateLastTimefortransaction]
AFTER UPDATE
ON transaction_tb
FOR EACH ROW
BEGIN
UPDATE transaction_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
CREATE TRIGGER [UpdateLastTimefortransactionitems]
AFTER UPDATE
ON transactionitems_tb
FOR EACH ROW
BEGIN
UPDATE transactionitems_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
CREATE TRIGGER [UpdateLastTimeforproduct]
AFTER UPDATE
ON product_tb
FOR EACH ROW
BEGIN
UPDATE product_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
CREATE TRIGGER [UpdateLastTimeforcategory]
AFTER UPDATE
ON category_tb
FOR EACH ROW
BEGIN
UPDATE category_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
CREATE TRIGGER [UpdateLastTimeforusers]
AFTER UPDATE
ON users_tb
FOR EACH ROW
BEGIN
UPDATE users_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
CREATE TRIGGER [UpdateLastTimeforuserslevel]
AFTER UPDATE
ON userslevel_tb
FOR EACH ROW
BEGIN
UPDATE userslevel_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
CREATE TRIGGER [UpdateLastTimeforcodelibrary]
AFTER UPDATE
ON codelibrary_tb
FOR EACH ROW
BEGIN
UPDATE codelibrary_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
CREATE TRIGGER [UpdateLastTimeforingredients]
AFTER UPDATE
ON ingredients_tb
FOR EACH ROW
BEGIN
UPDATE ingredients_tb SET last_updated = CURRENT_TIMESTAMP WHERE id = old.id;
END;
COMMIT;