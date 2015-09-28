<?php
/*
Plugin Name: Readability Index
Plugin URI: https://tas2580.net/downloads/download-18.html
Description: Calculate and display the Flesch-Reading-Ease-Score of an article
Version: 1.0.0
Author: tas2580
Author URI: https://tas2580.net
License: GPLv2
*/


add_filter('the_content', 'tas2580_flesch_index');

/**
 * Display the Flesch-Reading-Ease-Score to the article
 *
 * @param	string	$content	The page content
 */
if(!function_exists('tas2580_flesch_index'))
{
	function tas2580_flesch_index($content)
	{
		if(is_single())
		{
			$content = $content . '<p>Flesch-Reading-Ease-Score: <b>' . analyze_flesch($content) . '</b></p>';
		}
		return $content;
	}
}

/**
 * ACalculates the Flasch Index
 *
 * @param	string	$text	The text for which the Flasch Index is to be calculated
 */
if(!function_exists('analyze_flesch'))
{
	function analyze_flesch($text)
	{
		$num_words = $syllables = 0;
		$text = strip_tags($text);
		preg_match_all('/[.!?](\s|$)/', $text, $matches);
		$sentences = count($matches[0]);
		if ($sentences == 0)
		{
			$sentences = 1;
		}

		$words = str_word_count($text, 1, 'äüöß');
		foreach ($words as $word)
		{
			$s = 0;
			$scratch = preg_replace("/e$/", "", strtolower($word));
			$fragments = preg_split("/[^aeiouy]+/", $scratch);
			if (!$fragments[0])
			{
				array_shift($fragments);
			}
			if($fragments && !$fragments[count($fragments) - 1])
			{
				array_pop($fragments);
			}
			if(count($fragments))
			{
				$s += count($fragments);
			}
			else
			{
				$s = 1;
			}
			$syllables += $s;
			$num_words++;
		}
		return round(206.835 - 1.015 * ($num_words / $sentences) - 84.6 * ($syllables / $num_words));
	}
}
