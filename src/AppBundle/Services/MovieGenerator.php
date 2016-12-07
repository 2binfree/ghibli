<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 28/11/16
 * Time: 16:16
 */

namespace AppBundle\Services;


class MovieGenerator
{
    /**
     * @param $search string
     * @return string
     */
    public function getMovies($search)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.omdbapi.com/?type=movie&r=json&s=" . $search);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($ch));
        curl_close($ch);

        return $output->Search;
    }
}