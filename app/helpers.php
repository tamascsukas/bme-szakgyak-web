<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Checks whether a geographical coordinate is in the given bounds
 *
 * @param  double  $pointLat The coordinate's latitude that must be checked
 * @param  double  $pointLong The coordinate's longitude that must be checked
 * @param  double  $boundsNElat The North-East corner's coordinate's latitude
 * @param  double  $boundsNElong The North-East corner's coordinate's longitude
 * @param  double  $boundsSWlat The South-West corner's coordinate's latitude
 * @param  double  $boundsSWlong The South-West corner's coordinate's longitude
 * @return boolean
 */
function GEO_inBounds($pointLat, $pointLong, $boundsNElat, $boundsNElong, $boundsSWlat, $boundsSWlong) {
    $eastBound = $pointLong < $boundsNElong;
    $westBound = $pointLong > $boundsSWlong;

    if ($boundsNElong < $boundsSWlong) {
        $inLong = $eastBound || $westBound;
    } else {
        $inLong = $eastBound && $westBound;
    }

    $inLat = $pointLat > $boundsSWlat && $pointLat < $boundsNElat;
    return $inLat && $inLong;
}

/**
 * Make pagination from laravel collection
 *
 * @param  Illuminate\Support\Collection  $items The coordinate's latitude that must be checked
 * @param  int  $perPage The limit of items per page
 * @return Illuminate\Pagination\LengthAwarePaginator
 */
function COLLECTION_paginate($items, $perPage)
{
    $pageStart = request('page', 1);
    $offSet    = ($pageStart * $perPage) - $perPage;
    $itemsForCurrentPage = $items->slice($offSet, $perPage);

    return new Illuminate\Pagination\LengthAwarePaginator(
        $itemsForCurrentPage, $items->count(), $perPage,
        Illuminate\Pagination\Paginator::resolveCurrentPage(),
        ['path' => Illuminate\Pagination\Paginator::resolveCurrentPath()]
    );
}