<?php

namespace PieCrust;


/**
 * Default values for various things.
 */
class PieCrustDefaults
{
    /**
     * The current version of PieCrust.
     */
    const VERSION = '0.9.0-dev';
    
    /**
     * The application's source code directory.
     */
    const APP_DIR = __DIR__;

    /**
     * Names for special pages.
     */
    const INDEX_PAGE_NAME = '_index';
    const CATEGORY_PAGE_NAME = '_category';
    const TAG_PAGE_NAME = '_tag';
    
    /**
     * Names for special directories and files.
     */
    const CONTENT_DIR = '_content/';
    const CONFIG_PATH = '_content/config.yml';
    const CONTENT_TEMPLATES_DIR = '_content/templates/';
    const CONTENT_PAGES_DIR = '_content/pages/';
    const CONTENT_POSTS_DIR = '_content/posts/';
    const CONTENT_PLUGINS_DIR = '_content/plugins/';
    const CACHE_DIR = '_cache/';
    const CACHE_INFO_FILENAME = 'cacheinfo';
    
    /**
     * Default values for configuration settings.
     */
    const DEFAULT_BLOG_KEY = 'blog';
    const DEFAULT_FORMAT = 'markdown';
    const DEFAULT_PAGE_TEMPLATE_NAME = 'default';
    const DEFAULT_POST_TEMPLATE_NAME = 'post';
    const DEFAULT_TEMPLATE_ENGINE = 'twig';
    const DEFAULT_POSTS_FS = 'flat';
    const DEFAULT_DATE_FORMAT = 'F j, Y';

    /**
     * Default values for commands and non-CMS stuff.
     */
    const DEFAULT_PLUGIN_SOURCE = 'http://bitbucket.org/ludovicchabant/';
}
