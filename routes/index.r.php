<?php

return [
	['POST', '/[*]/api/auth/login', 'Auth.login'],
	['GET', '/[*]/api/auth/check-auth', 'Auth.checkAuth'],

    ['GET', '/[*]/api/autochecks/domains-search', 'AutoChecks.DomainsSearch'],
    ['GET', '/[*]/api/autochecks/get-domains-rows', 'AutoChecks.getDomainsRows'],
    ['GET', '/[*]/api/autochecks/get-domains-rows/stat', 'AutoChecks.getDomainsStat'],
    ['POST', '/[*]/api/autochecks/save-domain-check', 'AutoChecks.saveDomainCheck'],

    ['GET', '/[*]/api/autochecks/get-urls-rows', 'AutoChecks.getUrlsRows'],
    ['GET', '/[*]/api/autochecks/get-urls-rows/stat', 'AutoChecks.getUrlsStat'],
    ['POST', '/[*]/api/autochecks/save-url-check', 'AutoChecks.saveUrlCheck'],

    ['POST', '/[*]/api/autochecks/delete-check', 'AutoChecks.deleteCheck'],
    ['POST', '/[*]/api/autochecks/robot/check', 'AutoChecks.robotChecker'],
    ['GET', '/[*]/api/autochecks/robot/check-all', 'AutoChecks.robotChecker'],
];