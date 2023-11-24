<?php
/*
 *  Used to write in ..env file
 *  @param
 *  $data as array of ..env key & value
 *  @return nothing
 */

function generateSelectOption($data): array
{
	$options = array();
	foreach ($data as $key => $value) {
		$options[] = ['title' => $value, 'value' => $key];
	}
	return $options;
}

function removeAcentos($string): array|string|null
{
    return preg_replace(
        [
            "/(á|à|ã|â|ä)/",
            "/(Á|À|Ã|Â|Ä)/",
            "/(é|è|ê|ë)/",
            "/(É|È|Ê|Ë)/",
            "/(í|ì|î|ï)/",
            "/(Í|Ì|Î|Ï)/",
            "/(ó|ò|õ|ô|ö)/",
            "/(Ó|Ò|Õ|Ô|Ö)/",
            "/(ú|ù|û|ü)/",
            "/(Ú|Ù|Û|Ü)/",
            "/(ñ)/",
            "/(Ñ)/",
            "/(ç)/",
            "/(Ç)/"
    ],
    explode(" ","a A e E i I o O u U n N c C"), $string);
}

function getEloquentSqlWithBindings($query)
{
    return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
        return is_numeric($binding) ? $binding : "'{$binding}'";
    })->toArray());
}
