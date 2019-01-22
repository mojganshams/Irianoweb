<?php

const IS_PRODUCTION = false;

// Optional - Recommended way of organizing app files
const AREAS_DIR = 'areas/front';
const MODELS_DIR = 'areas/models';

// Twig template engine options
const TWIG_CACHE = false;
const TWIG_DEBUG = true;

const SITE_NAME_TO_SHOW = 'ایریانو وب';

const MAX_UPLOAD_SIZE = 200;

const CLOUD_FLARE = false;

const MAIN_DOMAIN = 'irianoweb:8180';
const ID_SITE_URL = 'http://id.' . MAIN_DOMAIN;
const SITE_HOME_URL = 'http://' . MAIN_DOMAIN;
const AUTO_SITE_HOME_URL = SITE_HOME_URL;

// Default database connection info
const DB_TYPE = 'PDO'; // Available options = ['PDO', 'MongoDB']
const DB_DRIVER = 'mysql';
const DB_HOST = 'localhost';
const DB_PORT = 27017; // Required for MongoDB
const DB_NAME = 'irianoweb';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';