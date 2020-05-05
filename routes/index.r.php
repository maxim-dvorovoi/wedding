<?php

return [
	['POST', '/[*]/call/auth/login', 'Auth.login'],
	['GET', '/[*]/call/auth/check-auth', 'Auth.checkAuth'],
    ['GET', '/[*]/call/auth/check-admin', 'Auth.checkAdmin'],

    ['GET', '/[*]/call/autochecks/domains-search', 'AutoChecks.DomainsSearch'],
    ['GET', '/[*]/call/autochecks/get-domains-rows', 'AutoChecks.getDomainsRows'],
    ['GET', '/[*]/call/autochecks/get-domains-rows/stat', 'AutoChecks.getDomainsStat'],
    ['POST', '/[*]/call/autochecks/save-domain-check', 'AutoChecks.saveDomainCheck'],

    ['GET', '/[*]/call/autochecks/get-urls-rows', 'AutoChecks.getUrlsRows'],
    ['GET', '/[*]/call/autochecks/get-urls-rows/stat', 'AutoChecks.getUrlsStat'],
    ['POST', '/[*]/call/autochecks/save-url-check', 'AutoChecks.saveUrlCheck'],

    ['POST', '/[*]/call/autochecks/delete-check', 'AutoChecks.deleteCheck'],
    ['POST', '/[*]/call/autochecks/robot/check', 'AutoChecks.robotChecker'],
    ['GET', '/[*]/call/autochecks/robot/check-all', 'AutoChecks.robotChecker'],
];