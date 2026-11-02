<?php
defined('MOODLE_INTERNAL') || die();

$plugin->release   = 'v5.0-r10 (2026-11-02)';
$plugin->maturity  = MATURITY_BETA;
$plugin->version   = 2026110200;
$plugin->requires  = 2024100715; // Moodle 4.5.15 minimum.
$plugin->supported = [405, 503]; // Moodle 4.5 to 5.3 inclusive.
$plugin->incompatible = 504; // Block Moodle 5.4 and later.
$plugin->cron      = 0;
$plugin->component = 'mod_labelcollapsed';