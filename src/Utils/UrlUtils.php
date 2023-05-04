<?php
namespace App\Utils;

class UrlUtils
{
    public static function hashUrl(string $url): string {
        $normalized_url = UrlUtils::normalizeUrl($url);
        return md5($normalized_url);
    }

    public static function normalizeUrl(string $url): string
    {
        $parsed_url = parse_url($url);

        // Convert scheme and host to lowercase
        $parsed_url['scheme'] = strtolower($parsed_url['scheme']);
        $parsed_url['host'] = strtolower($parsed_url['host']);

        // Remove default port
        if (isset($parsed_url['port'])) {
            if ($parsed_url['port'] == 80 && $parsed_url['scheme'] == 'http') {
                unset($parsed_url['port']);
            } elseif ($parsed_url['port'] == 443 && $parsed_url['scheme'] == 'https') {
                unset($parsed_url['port']);
            }
        }

        // Remove trailing slashes from path
        if (isset($parsed_url['path'])) {
            $parsed_url['path'] = rtrim($parsed_url['path'], '/');
        }

        // Normalize query parameters and values
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $params);
            $params = array_map('urldecode', $params);
            $params = array_unique($params);
            ksort($params);
            $parsed_url['query'] = http_build_query($params);
        }

        // Reconstruct normalized URL
        $normalized_url = '';
        if (isset($parsed_url['scheme'])) {
            $normalized_url .= $parsed_url['scheme'] . '://';
        }
        if (isset($parsed_url['user'])) {
            $normalized_url .= $parsed_url['user'];
            if (isset($parsed_url['pass'])) {
                $normalized_url .= ':' . $parsed_url['pass'];
            }
            $normalized_url .= '@';
        }
        if (isset($parsed_url['host'])) {
            $normalized_url .= $parsed_url['host'];
        }
        if (isset($parsed_url['port'])) {
            $normalized_url .= ':' . $parsed_url['port'];
        }
        if (isset($parsed_url['path'])) {
            $normalized_url .= $parsed_url['path'];
        }
        if (isset($parsed_url['query']) && strlen($parsed_url['query']) > 0) {
            $normalized_url .= '?' . $parsed_url['query'];
        }
        if (isset($parsed_url['fragment']) && strlen($parsed_url['fragment']) > 0) {
            $normalized_url .= '#' . $parsed_url['fragment'];
        }

        return $normalized_url;
    }
}