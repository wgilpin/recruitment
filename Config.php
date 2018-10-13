<?php
//          This has to be filled in by the User
//_________________________________________________________________________________________________________\\
//EVE RELATED INFORMATION

//Client ID Puller
$Client_ID = "da1c48bb9a65422e9ed3dd0ff86afa10";
//Client password Puller
$Client_Pass = "lSnWqD7bOXiFMIVQTKKN6n4hAnFBgd9L7ackf3ia";
//Client ID Login
$Client_IDLogin = "38fbeb6476884018a2ba2dbbbb47f961";
//Client password Login
$Client_PassLogin = "VdLdUFdf8rhoTnHWz0c5yM8ssUGvPMJNc2EDzpnb";


//DATABASE INFORMATION\\


//Database Host
$Host = "172.30.11.100:3306";
//Database Login
$dbName = "md436580db426616";
//Database Password
$dbPass = "Bier123";
//Database Charset
$Charset = "utf8";

//LOCAL EVE DATABASE INFORMATION\\

//Database Host
$EVEDBHost = "172.30.11.100:3306";
//Database Login
$EVEDBdbName = "md436580db428778";
//Database Password
$EVEDBdbPass = "Bier123";
//Database Charset
$EVEDBCharset = "utf8";

// Table names
$EVEDBinventoryType = "invTypes";   //Inventory type table name
$EVEDBstations = "staStations";     //Stations table name





//          All the frequently used non changing variables
//_________________________________________________________________________________________________________\\
//Client Basic
$Client_Basic = base64_encode(($Client_ID . ":" . $Client_Pass));
$Client_BasicLogin =  base64_encode(($Client_IDLogin . ":" . $Client_PassLogin));


//Date Calc
$connect = new PDO("mysql:host=$Host;dbname=$dbName;charset=$Charset", $dbName, $dbPass);
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = "SELECT * FROM config";
$stmt = $connect->query($query);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$cnf = $row[0];
//print_r($cnf);

//$cnf = readOut($Host, $dbName, $dbPass, $Charset);
$daysCharacter = "-$cnf[cacheTimeChar] days";
$daysCorporation = "-$cnf[cacheTimeCorp] days";
$daysAlliance = "-$cnf[cacheTimeAlly] days";
$daysStructure = "-$cnf[cacheTimeStruct] days";


//        allrequired scopes
//_________________________________________________________________________________________________________\\
$scopes = "publicData esi-calendar.respond_calendar_events.v1 esi-calendar.read_calendar_events.v1 esi-location.read_location.v1 esi-location.read_ship_type.v1 esi-mail.organize_mail.v1 esi-mail.read_mail.v1 esi-skills.read_skills.v1 esi-skills.read_skillqueue.v1 esi-wallet.read_character_wallet.v1 esi-wallet.read_corporation_wallet.v1 esi-search.search_structures.v1 esi-clones.read_clones.v1 esi-characters.read_contacts.v1 esi-universe.read_structures.v1 esi-bookmarks.read_character_bookmarks.v1 esi-killmails.read_killmails.v1 esi-corporations.read_corporation_membership.v1 esi-assets.read_assets.v1 esi-planets.manage_planets.v1 esi-fleets.read_fleet.v1 esi-fittings.read_fittings.v1 esi-markets.structure_markets.v1 esi-corporations.read_structures.v1 esi-corporations.write_structures.v1 esi-characters.read_loyalty.v1 esi-characters.read_opportunities.v1 esi-characters.read_chat_channels.v1 esi-characters.read_medals.v1 esi-characters.read_standings.v1 esi-characters.read_agents_research.v1 esi-industry.read_character_jobs.v1 esi-markets.read_character_orders.v1 esi-characters.read_blueprints.v1 esi-characters.read_corporation_roles.v1 esi-location.read_online.v1 esi-contracts.read_character_contracts.v1 esi-clones.read_implants.v1 esi-characters.read_fatigue.v1 esi-killmails.read_corporation_killmails.v1 esi-corporations.track_members.v1 esi-wallet.read_corporation_wallets.v1 esi-characters.read_notifications.v1 esi-corporations.read_divisions.v1 esi-corporations.read_contacts.v1 esi-assets.read_corporation_assets.v1 esi-corporations.read_titles.v1 esi-corporations.read_blueprints.v1 esi-bookmarks.read_corporation_bookmarks.v1 esi-contracts.read_corporation_contracts.v1 esi-corporations.read_standings.v1 esi-corporations.read_starbases.v1 esi-industry.read_corporation_jobs.v1 esi-markets.read_corporation_orders.v1 esi-corporations.read_container_logs.v1 esi-industry.read_character_mining.v1 esi-industry.read_corporation_mining.v1 esi-planets.read_customs_offices.v1 esi-corporations.read_facilities.v1 esi-corporations.read_medals.v1 esi-characters.read_titles.v1 esi-alliances.read_contacts.v1 esi-characters.read_fw_stats.v1 esi-corporations.read_fw_stats.v1 esi-corporations.read_outposts.v1 esi-characterstats.read.v1 esi-mail.send_mail.v1";
