<?php

$routes = [
	['POST', '/api.php/auth/login', 'Auth.login'],
	['GET', '/api.php/auth/check-auth', 'Auth.checkAuth'],
    ['GET', '/api.php/auth/check-admin', 'Auth.checkAdmin'],

    ['GET', '/api.php/autochecks/domains-search', 'AutoChecks.DomainsSearch'],
    ['GET', '/api.php/autochecks/get-domains-rows', 'AutoChecks.getDomainsRows'],
    ['GET', '/api.php/autochecks/get-domains-rows/stat', 'AutoChecks.getDomainsStat'],
    ['POST', '/api.php/autochecks/save-domain-check', 'AutoChecks.saveDomainCheck'],

    ['GET', '/api.php/autochecks/get-urls-rows', 'AutoChecks.getUrlsRows'],
    ['GET', '/api.php/autochecks/get-urls-rows/stat', 'AutoChecks.getUrlsStat'],
    ['POST', '/api.php/autochecks/save-url-check', 'AutoChecks.saveUrlCheck'],

    ['POST', '/api.php/autochecks/delete-check', 'AutoChecks.deleteCheck'],
    ['POST', '/api.php/autochecks/robot/check', 'AutoChecks.robotChecker'],
    ['GET', '/api.php/autochecks/robot/check-all', 'AutoChecks.robotChecker'],
];

if (DOMAIN !== 'localhost') return $routes;

foreach ($routes as $key => $route) {
    $routes[$key][1] = '/[*]'.$route[1];
}

return $routes;